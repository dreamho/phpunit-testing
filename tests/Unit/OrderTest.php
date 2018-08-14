<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
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

    public function testCreateOrder()
    {
        $data = [
            'product' => 2,
            'quantity' => 10,
            'address' => 'Different world',
        ];

        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/orders', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([$data['quantity']]);
    }

    public function testDeliverOrder()
    {
        $user = factory(User::class)->create();
        $orders = $this->actingAs($user, 'api')->json('GET', '/api/orders');
        $orders->assertStatus(200);
        $order = $orders->getData()[0];
        $deliverOrder = $this->actingAs($user, 'api')
            ->json('PATCH', '/api/orders/' . $order->id . '/deliver');
        $deliverOrder->assertStatus(200);
        $data = $deliverOrder->getData('data');
        $deliverOrder->assertJson(['message' => 'Order Delivered!']);
    }

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

        $this->assertEquals(1, 1);

        $this->assertCredentials(['email' => 'admin@devtest.com', 'password' => 'secret'], 'web');
        $this->assertDatabaseHas('users', [
            'email' => 'admin@devtest.com'
        ]);
    }

    public function test()
    {
        $dsadhjk = new OrderController();
        // $response = $dsadhjk->
    }

    /*public function testOrder()
    {
        $data = [
            'email' => 'dsad@dsadsa.das',
            'products' => ['.', '.']
        ];

        $senderMock = MailSender::fake();
        $senderMock->sendEmail = function ($email, $data) {
            return true;
        };
        $controller = new OrderController($senderMock);
    }*/
}
