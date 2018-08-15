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

    public function testCreateProduct()
    {
        $data = [
            'name'        => "Coffee shop",
            'description' => "This is a product",
            'units'       => 20,
            'price'       => 10,
            'image'       => "https://images.pexels.com/photos/1000084/pexels-photo-1000084.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
        ];
        $controller = $this->getProductController();
        $request = $request = $this->getRequest($data);;
        $response = ($controller->store($request))->getData();
        $this->assertEquals($data['name'], $response->data->name);
        $this->assertTrue($response->status);
    }

    public function testGetAllProducts()
    {
        $controller = $this->getProductController();
        $response = ($controller->index())->getData();
        $this->assertGreaterThan(0, $response);
    }

    public function testUpdateProduct()
    {
        $controller = $this->getProductController();
        $product = Product::all()->first();
        $data = ['name' => 'Shoes again!'];
        $request = $this->getRequest($data);
        $response = ($controller->update($request, $product))->getData();
        $this->assertEquals('Product Updated!', $response->message);
        $this->assertTrue($response->status);
    }

    public function testDeleteProduct()
    {
        $controller = $this->getProductController();
        $product = Product::all()->first();
        $response = ($controller->destroy($product))->getData();
        $this->assertTrue($response->status);
    }

/*    public function testUploadImage()
    {
        $file = UploadedFile::fake()->image('product.jpeg');
        $controller = $this->getProductController();
        $data = ['image' => $file];
        $request = $this->getRequest($data);
        $response = $controller->uploadFile($request);
        return;

    }*/
}
