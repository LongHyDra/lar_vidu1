<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_to_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user);

        $response = $this->postJson(route('user.chat.send'), [
            'message' => 'Xin chào admin',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'receiver_id' => $admin->id,
            'content' => 'Xin chào admin',
        ]);
    }

    public function test_admin_can_fetch_chat_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        Message::create([
            'sender_id' => $customer->id,
            'receiver_id' => $admin->id,
            'content' => 'Chào admin',
        ]);

        $this->actingAs($admin);

        $response = $this->getJson(route('admin.chat.users'));

        $response->assertOk()
            ->assertJsonFragment(['id' => $customer->id]);
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('welcome'));
    }

    public function test_customer_cannot_access_product_management_index(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer);

        $response = $this->get(route('products.index'));

        $response->assertRedirect(route('welcome'));
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create([
            'name' => 'Phụ kiện test',
            'description' => 'Danh mục mẫu cho test',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Phuộc xe máy test',
            'price' => 1500000,
            'stock' => 10,
            'description' => 'Test product',
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'name' => 'Khách hàng test',
            'address' => '123 Đường Test',
            'phone' => '0901234567',
            'total_price' => 1500000,
            'status' => 'pending',
            'shipping_status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 1500000,
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.orders.updateStatus', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('admin.dashboard', ['section' => 'orders']));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_customer_can_view_and_update_profile(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Khách hàng cũ',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($customer);

        $profileResponse = $this->get(route('user.profile'));
        $profileResponse->assertOk();

        $updateResponse = $this->put(route('user.profile.update'), [
            'name' => 'Khách hàng mới',
            'email' => 'customer.updated@example.com',
        ]);

        $updateResponse->assertRedirect(route('user.profile'));
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Khách hàng mới',
            'email' => 'customer.updated@example.com',
        ]);
    }
}
