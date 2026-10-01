<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\GHNOrderService;
use App\Services\GHNService;
use App\Services\MomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private bool $shippingSucceeds = false;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Http::fake([
            '*shipping-order/fee' => Http::response(['code' => 200, 'data' => ['total' => 28000]]),
            '*shipping-order/create' => fn () => Http::response($this->shippingSucceeds
                ? ['code' => 200, 'data' => ['order_code' => 'GHN-created']]
                : ['code' => 400, 'message' => 'Unavailable']),
        ]);
    }

    private function product(int $stock = 5): Product
    {
        return Product::create([
            'category_id' => Category::create(['name' => 'Test'])->id,
            'name' => 'Test product', 'price' => 100000, 'stock' => $stock,
        ]);
    }

    private function order(Product $product, string $status = 'pending', bool $deducted = false): Order
    {
        $order = Order::create([
            'user_id' => User::factory()->create()->id, 'name' => 'Test customer',
            'address' => 'Test address', 'phone' => '0901234567', 'total_price' => 228000,
            'status' => $status, 'stock_deducted' => $deducted,
        ]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 2, 'price' => 100000]);

        return $order;
    }

    private function checkout(array $items): array
    {
        return [
            'name' => 'Test', 'phone' => '0901234567', 'address' => 'Test address',
            'to_district_id' => 1450, 'to_ward_code' => '1A0107',
            'payment_method' => 'cod', 'cart_items' => json_encode($items),
        ];
    }

    private function paymentPayload(Order $order): array
    {
        $order->paymentTransactions()->create([
            'gateway' => 'momo', 'gateway_order_id' => 'test-'.$order->id,
            'amount' => $order->total_price, 'status' => 'initiated',
        ]);
        $this->partialMock(MomoService::class, function ($mock) {
            $mock->shouldReceive('isValidSuccessfulResponse')->andReturn(true);
        });

        return ['orderId' => 'test-'.$order->id, 'amount' => $order->total_price, 'resultCode' => 0, 'transId' => 'test'];
    }

    public function test_unverified_customer_cannot_checkout(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->postJson(route('user.payment.process'), [])->assertForbidden();
        $this->assertDatabaseCount('orders', 0);
        Http::assertNothingSent();
    }

    public function test_duplicate_cart_lines_cannot_exceed_stock(): void
    {
        $product = $this->product();
        $this->actingAs(User::factory()->create())->postJson(route('user.payment.process'), $this->checkout([
            ['id' => $product->id, 'quantity' => 4], ['id' => $product->id, 'quantity' => 4],
        ]))->assertUnprocessable()->assertJsonValidationErrors('cart_items');
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_duplicate_cart_lines_are_merged_and_cod_payment_is_created(): void
    {
        $product = $this->product();
        $this->actingAs(User::factory()->create())->postJson(route('user.payment.process'), $this->checkout([
            ['id' => $product->id, 'quantity' => 1], ['id' => $product->id, 'quantity' => 2],
        ]))->assertOk();
        $this->assertEquals(2, $product->fresh()->stock);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('order_items', ['quantity' => 3]);
        $this->assertDatabaseHas('payment_transactions', ['gateway' => 'cod', 'amount' => 328000]);
    }

    public function test_malformed_cart_is_rejected_before_calling_shipping(): void
    {
        $this->actingAs(User::factory()->create())->postJson(route('user.payment.process'),
            $this->checkout([['id' => 1, 'quantity' => 1.5]]))
            ->assertUnprocessable();
        Http::assertNothingSent();
    }

    public function test_repeated_momo_notifications_deduct_only_once_even_when_shipping_fails(): void
    {
        $product = $this->product();
        $order = $this->order($product);
        $payload = $this->paymentPayload($order);
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->get(route('user.payment.momo.callback', $payload))->assertRedirect();
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertDatabaseCount('inventory_movements', 1);
        $this->assertDatabaseHas('payment_transactions', ['order_id' => $order->id, 'status' => 'paid']);
        Http::assertSentCount(1);
    }

    public function test_paid_notification_with_insufficient_stock_requires_review(): void
    {
        $product = $this->product(1);
        $order = $this->order($product);
        $payload = $this->paymentPayload($order);
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->assertEquals(1, $product->fresh()->stock);
        $this->assertEquals('payment_review', $order->fresh()->shipping_status);
        $this->assertDatabaseHas('payment_transactions', ['order_id' => $order->id, 'status' => 'paid']);
        Http::assertNothingSent();
    }

    public function test_cancelled_order_cannot_restart_payment_or_be_reopened_by_ipn(): void
    {
        $product = $this->product();
        $order = $this->order($product, 'cancelled');
        $this->actingAs($order->user)->get(route('user.orders.momo.pay', $order))
            ->assertRedirect()->assertSessionHas('error');
        $this->assertDatabaseCount('payment_transactions', 0);
        $payload = $this->paymentPayload($order);
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertDatabaseHas('payment_transactions', ['order_id' => $order->id, 'status' => 'paid']);
        Http::assertNothingSent();
    }

    public function test_admin_cancel_unpaid_momo_does_not_increase_stock(): void
    {
        $product = $this->product();
        $order = $this->order($product);
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('admin.orders.updateStatus', $order), ['status' => 'cancelled'])->assertRedirect();
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_confirmed_cod_cancellation_restores_stock_only_once(): void
    {
        $product = $this->product(3);
        $order = $this->order($product, 'confirmed', true);
        $order->paymentTransactions()->create(['gateway' => 'cod', 'amount' => 228000, 'status' => 'pending']);
        $this->actingAs($order->user)->post(route('user.orders.cancel', $order))->assertRedirect();
        $this->post(route('user.orders.cancel', $order))->assertRedirect();
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertDatabaseCount('inventory_movements', 1);
    }

    public function test_legacy_cod_uses_inventory_ledger_after_status_changes(): void
    {
        $product = $this->product(3);
        $order = $this->order($product, 'packaging');
        $order->update(['stock_deducted' => null]);
        InventoryMovement::create([
            'product_id' => $product->id, 'type' => 'out', 'quantity' => -2,
            'stock_after' => 3, 'note' => "Trừ kho cho đơn COD #{$order->id}",
        ]);
        $this->actingAs($order->user)->post(route('user.orders.cancel', $order))->assertRedirect();
        $this->assertEquals(5, $product->fresh()->stock);
    }

    public function test_shipping_cancellation_failure_preserves_stock_and_order(): void
    {
        $product = $this->product(3);
        $order = $this->order($product, 'confirmed', true);
        $order->update(['ghn_order_code' => 'GHN-test']);
        Http::fake(['*switch-status/cancel' => Http::response(['code' => 200, 'data' => [
            ['order_code' => 'GHN-test', 'result' => false],
        ]])]);
        $this->actingAs($order->user)->postJson(route('user.orders.cancel', $order))->assertUnprocessable();
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertEquals('confirmed', $order->fresh()->status);
    }

    public function test_cod_amount_is_not_truncated_and_shipping_is_not_charged_twice(): void
    {
        $order = $this->order($this->product(), 'cod_ordered', true);
        $order->update(['total_price' => 6028000]);
        $ghn = \Mockery::mock(GHNService::class);
        $ghn->shouldReceive('createOrder')->once()->withArgs(fn ($payload) => $payload['cod_amount'] === 6028000 && $payload['payment_type_id'] === 1
        )->andReturn(['code' => 400]);
        (new GHNOrderService($ghn))->create($order);
    }

    public function test_revenue_excludes_unpaid_and_cancelled_orders(): void
    {
        $product = $this->product();
        $paid = $this->order($product, 'paid', true);
        $paid->paymentTransactions()->create(['gateway' => 'momo', 'amount' => 228000, 'status' => 'paid']);
        $this->order($product, 'pending');
        $cancelled = $this->order($product, 'cancelled');
        $cancelled->paymentTransactions()->create(['gateway' => 'momo', 'amount' => 228000, 'status' => 'paid']);
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get(route('admin.dashboard'))
            ->assertOk()->assertViewHas('totalRevenue', 228000)
            ->assertViewHas('todayRevenue', 228000)->assertViewHas('monthRevenue', 228000);
        $this->get(route('admin.reports.revenue.print'))->assertOk()
            ->assertViewHas('orders', fn ($orders) => $orders->count() === 1 && $orders->first()->id === $paid->id);
    }

    public function test_successful_shipping_and_replayed_payment_do_not_create_duplicate_shipment(): void
    {
        $this->shippingSucceeds = true;
        $product = $this->product();
        $order = $this->order($product);
        $payload = $this->paymentPayload($order);
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertEquals('GHN-created', $order->fresh()->ghn_order_code);
        Http::assertSentCount(1);
    }

    public function test_wrong_payment_amount_cannot_deduct_stock_or_mark_paid(): void
    {
        $product = $this->product();
        $order = $this->order($product);
        $payload = $this->paymentPayload($order);
        $payload['amount'] = 1;
        $this->postJson(route('payment.momo.ipn'), $payload)->assertOk();
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertDatabaseHas('payment_transactions', ['order_id' => $order->id, 'status' => 'initiated']);
        Http::assertNothingSent();
    }

    public function test_customer_cannot_cancel_paid_order_after_status_changes(): void
    {
        $product = $this->product(3);
        $order = $this->order($product, 'confirmed', true);
        $order->paymentTransactions()->create(['gateway' => 'momo', 'amount' => 228000, 'status' => 'paid']);
        $this->actingAs($order->user)->postJson(route('user.orders.cancel', $order))->assertUnprocessable();
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertEquals('confirmed', $order->fresh()->status);
    }
}
