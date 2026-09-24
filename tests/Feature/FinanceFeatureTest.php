<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_filtered_finance_report_without_duplicate_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $order = $this->makeOrder($customer, 420000, 'Nguyen Van A');

        PaymentTransaction::create(['order_id' => $order->id, 'gateway' => 'momo', 'amount' => 420000, 'status' => 'paid']);
        PaymentTransaction::create(['order_id' => $order->id, 'gateway' => 'momo', 'amount' => 420000, 'status' => 'pending']);

        $this->actingAs($admin)
            ->get(route('admin.finance.index', ['search' => 'Nguyen Van A', 'payment_status' => 'paid']))
            ->assertOk()
            ->assertSee('420.000');

        $this->actingAs($admin)
            ->get(route('admin.finance.transactions', ['gateway' => 'momo']))
            ->assertOk()
            ->assertSee('#' . $order->id)
            ->assertSee('Đã thanh toán');
    }

    public function test_admin_can_mark_a_cod_order_as_paid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();
        $order = $this->makeOrder($customer, 230000, 'Khach COD');
        $payment = PaymentTransaction::create(['order_id' => $order->id, 'gateway' => 'cod', 'amount' => 230000, 'status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.finance.update-status', $order), [
                'payment_status' => 'paid',
                'current_payment_status' => 'pending',
                'current_order_status' => 'cod_ordered',
                'current_payment_id' => $payment->id,
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payment_transactions', ['id' => $payment->id, 'status' => 'paid']);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cod_paid']);
    }

    private function makeOrder(User $customer, int $amount, string $name): Order
    {
        return Order::create([
            'user_id' => $customer->id,
            'name' => $name,
            'address' => 'Dia chi test',
            'phone' => '0900000000',
            'total_price' => $amount,
            'status' => 'cod_ordered',
            'shipping_status' => 'ready_to_pick',
        ]);
    }
}
