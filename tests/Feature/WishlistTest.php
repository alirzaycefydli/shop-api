<?php

namespace Tests\Feature;

use App\Models\V1\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cannot_fetch_wishlist(): void
    {
        $response = $this->getJson('api/v1/wishlist');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' => null,
            ]);
    }

    public function test_authorized_user_can_fetch_empty_wishlist(): void
    {
        $user = $this->createUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['data']['token'],
        ])->getJson('api/v1/wishlist');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [],
            ]);
    }

    public function test_user_can_wishlist_product(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['data']['token'],
        ])->postJson('api/v1/wishlist', [
            'product_id' => $product->id,
            'user_id' => $user['data']['user']['id'],
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [],
            ]);
    }

    public function test_unauthorized_user_cannot_wishlist_product(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson('api/v1/wishlist', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' => null,
            ]);
    }

    public function test_user_can_delete_wishlist_product(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['data']['token'],
        ])->postJson('api/v1/wishlist', [
            'product_id' => $product->id,
            'user_id' => $user['data']['user']['id'],
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['data']['token'],
        ])->deleteJson('api/v1/wishlist/' . $product->id);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => null,
            ]);
    }

    private function createUser(): \Illuminate\Testing\TestResponse
    {
        return $this->postJson('api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    }

    private function createProduct(): Product
    {
        return Product::factory()->create();
    }
}
