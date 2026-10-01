<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhnWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_ghn_delivered_webhook_marks_order_delivered_and_collects_cod(): void
    {
        config()->set('services.ghn.webhook_token', 'secret-webhook-token');
        $customer = User::factory()->create();
        $order = Order::create([
            'user_id' => $customer->id,
            'name' => 'Khach hang',
            'address' => 'Dia chi',
            'phone' => '0900000000',
            'total_price' => 150000,
            'status' => 'shipping',
            'shipping_status' => 'delivering',
            'ghn_order_code' => 'GHN-123',
        ]);
        $payment = PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'amount' => 150000,
            'status' => 'pending',
        ]);

        $this->postJson(route('shipping.ghn.webhook', 'secret-webhook-token'), [
            'OrderCode' => 'GHN-123',
            'Status' => 'delivered',
        ])->assertOk();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'delivered', 'shipping_status' => 'delivered']);
        $this->assertDatabaseHas('payment_transactions', ['id' => $payment->id, 'status' => 'paid']);
    }

    public function test_ghn_webhook_rejects_an_invalid_token(): void
    {
        config()->set('services.ghn.webhook_token', 'secret-webhook-token');

        $this->postJson(route('shipping.ghn.webhook', 'wrong-token'), [
            'OrderCode' => 'GHN-123',
            'Status' => 'delivered',
        ])->assertForbidden();
    }
}
