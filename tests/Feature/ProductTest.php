<?php

namespace Tests\Feature;

use App\Models\V1\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Type\Integer;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_returns_empty_list_when_no_products_exist(): void
    {
        $response = $this->getJson('api/v1/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'success',
                'data' => [],
            ])
            ->assertJsonCount(0, 'data');
    }

    public function test_can_get_product_list(): void
    {
        $this->createProducts();

        $response = $this->getJson('api/v1/products');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'image',
                        'short_description',
                        'brand',
                        'price',
                        'rating',
                        'discounted_price',
                        'discount_percent',
                        'is_featured',
                        'reviews'
                    ],
                ],
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_single_product_details(): void
    {
        $this->createProducts();

        $response = $this->getJson('api/v1/products/1');

        $response->assertOk();
    }

    public function test_returns_400_if_product_with_id_not_found(): void
    {
        $response = $this->getJson('api/v1/products/99999');

        $response->assertBadRequest()
            ->assertJson([
                'success' => false,
                'message' => 'error',
            ]);
    }

    private function createProducts(int $amount = 1, array $overrides = []): Product
    {
        return Product::factory($amount)->create($overrides);
    }

    /*
    public function test_returns_empty_list_if_no_products_match_price_filter(): void {}
    public function test_can_filter_products_by_price_range(): void {}
    public function test_can_filter_products_by_name(): void {}
    public function test_returns_empty_list_if_no_products_match_name_filter(): void {}

    public function test_can_filter_products_by_discount(): void {}
    public function test_returns_empty_list_if_no_products_match_discount_filter(): void {}

    public function test_can_list_products_by_category(): void {}
    public function test_returns_empty_list_if_no_products_in_category(): void {}
    public function test_returns_404_if_category_not_found(): void {}
    */
}
