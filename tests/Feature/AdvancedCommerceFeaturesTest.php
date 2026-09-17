<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedCommerceFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_records_view_history_and_recommendations(): void
    {
        $category = Category::create(['name' => 'Phanh nâng cao']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Brembo detail', 'brand' => 'Brembo', 'price' => 1000000, 'stock' => 3]);
        Product::create(['category_id' => $category->id, 'name' => 'Brembo related', 'price' => 900000, 'stock' => 2]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Brembo detail')
            ->assertSee('Sản phẩm liên quan');

        $this->assertSame([$product->id], session('recently_viewed'));
    }

    public function test_order_detail_shows_payment_and_status_timeline(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->id,
            'name' => 'Khách hàng',
            'address' => 'Địa chỉ giao hàng',
            'phone' => '0901234567',
            'total_price' => 300000,
            'status' => 'confirmed',
        ]);
        PaymentTransaction::create(['order_id' => $order->id, 'gateway' => 'cod', 'amount' => 300000, 'status' => 'pending']);
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => 'pending', 'note' => 'Đơn hàng được tạo']);
        OrderStatusHistory::create(['order_id' => $order->id, 'status' => 'confirmed', 'note' => 'Đã xác nhận']);

        $this->actingAs($user)->get(route('user.orders.show', $order))
            ->assertOk()
            ->assertSee('Phương thức thanh toán')
            ->assertSee('Lịch sử trạng thái')
            ->assertSee('Đã xác nhận');
    }

    public function test_admin_can_view_inventory_log_and_export_revenue(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Kho test']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Sản phẩm kho', 'price' => 100000, 'stock' => 2]);
        InventoryMovement::create(['product_id' => $product->id, 'user_id' => $admin->id, 'type' => 'in', 'quantity' => 2, 'stock_after' => 2, 'note' => 'Nhập test']);

        $this->actingAs($admin)->get(route('admin.inventory.index'))
            ->assertOk()
            ->assertSee('Nhập test');
        $this->actingAs($admin)->get(route('admin.reports.revenue.csv'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_add_account_from_dashboard_users_section(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard', ['section' => 'users']))
            ->assertOk()
            ->assertSee('Thêm tài khoản');

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Quản trị viên mới',
            'email' => 'new-admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('admin.dashboard', ['section' => 'users']));

        $this->assertDatabaseHas('users', [
            'email' => 'new-admin@example.com',
            'role' => 'admin',
        ]);
    }
}
