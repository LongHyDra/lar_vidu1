<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_filters_products_by_search_and_price(): void
    {
        $category = Category::create(['name' => 'Phanh xe']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Brembo M4',
            'price' => 3500000,
            'stock' => 5,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Dây dầu phổ thông',
            'price' => 250000,
            'stock' => 5,
        ]);

        $response = $this->get('/?q=Brembo&min_price=3000000');

        $response->assertOk()->assertSee('Brembo M4')->assertDontSee('Dây dầu phổ thông');
    }

    public function test_customer_can_add_and_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Đèn xe']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Đèn LED test',
            'price' => 900000,
            'stock' => 3,
        ]);

        $this->actingAs($user);

        $this->post(route('user.wishlist.store', $product))
            ->assertRedirect();
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->delete(route('user.wishlist.destroy', $product))
            ->assertRedirect();
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_is_available_in_homepage_modal_data(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Phụ kiện modal']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sản phẩm yêu thích trong modal',
            'price' => 500000,
            'stock' => 2,
        ]);

        $user->wishlists()->create(['product_id' => $product->id]);

        $this->actingAs($user)
            ->get(route('welcome'))
            ->assertOk()
            ->assertSee('Sản phẩm yêu thích trong modal')
            ->assertSee('wishlistModal');
    }

    public function test_faq_page_is_public(): void
    {
        $this->get(route('faq'))
            ->assertOk()
            ->assertSee('Câu hỏi thường gặp');
    }
}
