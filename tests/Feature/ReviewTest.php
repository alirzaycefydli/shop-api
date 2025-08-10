<?php

namespace Tests\Feature;

use App\Models\V1\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_empty_list_if_product_has_no_review(): void
    {
        $product = $this->createProduct();

        $response = $this->get('api/v1/reviews/' . $product->id);

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);
    }

    public function test_returns_product_reviews(): void
    {

        $user = $this->postJson('api/v1/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $product = $this->createProduct();

        $response = $this->postJson('api/v1/reviews', [
            'rating' => 5,
            'review' => 'Product review',
            'title' => 'Title',
            'user_id' => $user['data']['user']['id'],
            'product_id' => $product->id,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'success',
                'data' => []
            ]);
    }

    public function test_unauthenticated_user_cannot_add_review()
    {
        $product = $this->createProduct();

        $response = $this->postJson('api/v1/reviews', [
            'rating' => 5,
            'review' => 'Product review',
            'title' => 'Title',
            'product_id' => $product->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_id']);
    }


    private function createProduct(): Product
    {
        return Product::factory()->create();
    }

}
