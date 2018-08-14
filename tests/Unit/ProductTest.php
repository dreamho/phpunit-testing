<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Product;
use App\User;
use http\Env\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testCreateProductWithMiddleware()
    {
        $data = [
            'name'        => "New Product",
            'description' => "This is a product",
            'units'       => 20,
            'price'       => 10,
            'image'       => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];

        $response = $this->json('POST', '/api/products', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Unauthenticated."]);
    }

    public function testCreateProduct()
    {
        $data = [
            'name'        => "Coffee Cup",
            'description' => "This is a product description",
            'units'       => 20,
            'price'       => 10,
            'image'       => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];
        //$user = factory(\App\User::class)->create();
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')->json('POST', '/api/products', $data);
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
        $response->assertJson(['message' => "Product Created!"]);
        $response->assertJson(['data' => $data]);
    }

    public function testGetAllProducts()
    {
        $response = $this->json('GET', 'api/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'description',
                'units',
                'price',
                'image',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testUpdateProduct()
    {
        $products = $this->json('GET', 'api/products');
        $product = $products->getData()[3];
        $user = factory(User::class)->create();
        $updateProduct = $this->actingAs($user, 'api')
            ->json('PATCH', 'api/products/' . $product->id, ['price' => 100]);
        $updateProduct->assertStatus(200);
    }

    public function testDeleteProduct()
    {
        $products = $this->json('GET', '/api/products');
        $products->assertStatus(200);
        $product = $products->getData()[4];
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')->json('DELETE', '/api/products/' . $product->id);
        $response->assertStatus(200);
    }

    public function testUploadImage()
    {
        $response = $this->json('POST', '/api/upload-file', [
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);
        $response->assertStatus(201);

    }

    public function testCreate()
    {
        $data = [
            'name'        => "New Product",
            'description' => "This is a product",
            'units'       => 20,
            'price'       => 10,
            'image'       => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];
        $controller = new ProductController();
        $request = new \Illuminate\Http\Request($data);
        $response = $controller->store($request);
        $decoded = json_decode($response->getContent());
        $product_name = $decoded->data->name;
        $this->assertEquals($data['name'], $product_name);
        $this->assertTrue($decoded->status);
    }

    public function testGetAll()
    {
        $controller = new ProductController();
        $response = json_decode($controller->index()->getContent());
        $this->assertGreaterThan(0, $response);
    }

    public function testUpdate()
    {
        $controller = new ProductController();
        $product = Product::findOrFail(1);
        $data = ['price' => 10000];
        $request = new \Illuminate\Http\Request($data);
        $updated = json_decode($controller->update($request, $product)->getContent());
        $this->assertEquals('Product Updated!', $updated->message);
        $this->assertTrue($updated->status);
    }

    public function testDelete()
    {
        $controller = new ProductController();
        $product = Product::all()->first();
        $deleted = json_decode($controller->destroy($product)->getContent());
        $this->assertTrue($deleted->status);
    }
}
