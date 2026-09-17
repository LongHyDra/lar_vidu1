<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_reports_exclude_cancelled_sales(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Phụ kiện báo cáo']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sản phẩm bán chạy',
            'price' => 100000,
            'stock' => 10,
        ]);

        $activeOrder = Order::create([
            'user_id' => $customer->id,
            'name' => 'Khách hàng',
            'address' => 'Địa chỉ test',
            'phone' => '0901234567',
            'total_price' => 200000,
            'status' => 'delivered',
        ]);
        OrderItem::create([
            'order_id' => $activeOrder->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100000,
        ]);

        $cancelledOrder = Order::create([
            'user_id' => $customer->id,
            'name' => 'Khách hàng',
            'address' => 'Địa chỉ test',
            'phone' => '0901234567',
            'total_price' => 900000,
            'status' => 'cancelled',
        ]);
        OrderItem::create([
            'order_id' => $cancelledOrder->id,
            'product_id' => $product->id,
            'quantity' => 9,
            'price' => 100000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['period' => 7]));

        $response->assertOk()
            ->assertViewHas('totalRevenue', 200000)
            ->assertViewHas('totalSoldQty', 2)
            ->assertViewHas('cancelledOrders', 1)
            ->assertSee('Doanh thu theo ngày')
            ->assertSee('Sản phẩm bán chạy');
    }

    public function test_cancelled_order_cannot_be_reopened(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $customer->id,
            'name' => 'Khách hàng',
            'address' => 'Địa chỉ test',
            'phone' => '0901234567',
            'total_price' => 100000,
            'status' => 'cancelled',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.orders.updateStatus', $order), ['status' => 'confirmed'])
            ->assertRedirect(route('admin.dashboard', ['section' => 'orders']));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }
}
