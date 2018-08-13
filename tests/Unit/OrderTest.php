<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
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

/*    public function testCreateOrder()
    {
        $data = [
            'product' => 1,
            'quantity' => 5,
            'address' => 'Some street on some place',
        ];

        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/orders', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([$data['quantity']]);
    }*/

    public function testGetAllOrders()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'product_id',
                'quantity',
                'address',
            ]
        ]);
    }

    public function testUpdateOrder()
    {
        $user = User::find(9);
        $orders = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $orders->assertStatus(200);
        $order = $orders->getData()[0];
        $updateOrder = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/orders/' . $order->id, ['quantity' => 10]);
        $updateOrder->assertStatus(200);
        $updateOrder->assertJson(['message' => 'Order Updated!']);
    }
}
