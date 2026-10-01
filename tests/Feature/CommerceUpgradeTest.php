<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CommerceUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            '*shipping-order/fee' => Http::response(['code' => 200, 'data' => ['total' => 28000]]),
            '*shipping-order/create' => Http::response(['code' => 400, 'message' => 'deferred']),
        ]);
    }

    public function test_variant_checkout_uses_variant_price_and_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['category_id' => Category::create(['name' => 'Phụ tùng'])->id, 'name' => 'Gương xe', 'price' => 100000, 'stock' => 99]);
        $variant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'GUONG-DEN', 'variant_name' => 'Đen', 'price' => 125000, 'stock' => 3, 'weight' => 300]);

        $this->actingAs($user)->postJson(route('user.payment.process'), [
            'name' => 'Khách hàng', 'phone' => '0901234567', 'address' => 'Địa chỉ', 'to_district_id' => 1450, 'to_ward_code' => '1A0107', 'payment_method' => 'cod',
            'cart_items' => json_encode([['id' => $product->id, 'variant_id' => $variant->id, 'quantity' => 1]]),
        ])->assertOk();

        $this->assertSame(2, $variant->fresh()->stock);
        $this->assertSame(99, $product->fresh()->stock);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'variant_id' => $variant->id, 'price' => 125000]);
    }

    public function test_coupon_is_applied_once_to_checkout(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['category_id' => Category::create(['name' => 'Phụ tùng'])->id, 'name' => 'Bao tay', 'price' => 100000, 'stock' => 5]);
        Coupon::create(['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'minimum_order' => 0, 'is_active' => true]);

        $this->actingAs($user)->postJson(route('user.payment.process'), [
            'name' => 'Khách hàng', 'phone' => '0901234567', 'address' => 'Địa chỉ', 'to_district_id' => 1450, 'to_ward_code' => '1A0107', 'payment_method' => 'cod', 'coupon_code' => 'welcome10',
            'cart_items' => json_encode([['id' => $product->id, 'quantity' => 1]]),
        ])->assertOk();

        $this->assertDatabaseHas('orders', ['coupon_code' => 'WELCOME10', 'discount_amount' => 10000, 'total_price' => 118000]);
        $this->assertDatabaseHas('coupons', ['code' => 'WELCOME10', 'used_count' => 1]);
    }

    public function test_authenticated_cart_and_address_are_persisted(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->putJson(route('user.cart.sync'), ['items' => [['id' => 4, 'quantity' => 2]]])->assertOk()->assertJsonPath('items.0.id', 4);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->actingAs($user)->post(route('user.addresses.store'), ['recipient_name' => 'Người nhận', 'phone' => '0901234567', 'address' => 'Địa chỉ', 'is_default' => 1])->assertRedirect();
        $this->assertDatabaseHas('user_addresses', ['user_id' => $user->id, 'is_default' => 1]);
    }

    public function test_sitemap_is_public(): void
    {
        $this->get(route('seo.sitemap'))->assertOk()->assertHeader('Content-Type', 'application/xml')->assertSee('<urlset', false);
    }

    public function test_delivered_order_awards_loyalty_points_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $product = Product::create(['category_id' => Category::create(['name' => 'Đồ chơi'])->id, 'name' => 'Đèn LED', 'price' => 100000, 'stock' => 3]);
        $order = Order::create(['user_id' => $customer->id, 'name' => 'Khách', 'address' => 'Địa chỉ', 'phone' => '0901234567', 'total_price' => 128000, 'status' => 'shipping', 'stock_deducted' => true]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 1, 'price' => 100000]);

        $this->actingAs($admin)->post(route('admin.orders.updateStatus', $order), ['status' => 'delivered'])->assertRedirect();
        $this->actingAs($admin)->post(route('admin.orders.updateStatus', $order), ['status' => 'delivered'])->assertRedirect();

        $this->assertSame(12, $customer->fresh()->loyalty_points);
        $this->assertDatabaseCount('loyalty_transactions', 1);
    }

    public function test_customer_can_redeem_loyalty_points_for_coupon(): void
    {
        $customer = User::factory()->create(['loyalty_points' => 200]);
        $this->actingAs($customer)->post(route('user.loyalty.redeem'), ['points' => 200])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'loyalty_points' => 0]);
        $this->assertDatabaseHas('coupons', ['type' => 'fixed', 'value' => 20000]);
    }
}
