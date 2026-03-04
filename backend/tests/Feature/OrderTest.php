<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/orders', [
            'solicitante' => 'Lucas',
            'destino' => 'São Paulo',
            'data_ida' => '2026-03-10',
            'data_volta' => '2026-03-15',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'destino' => 'São Paulo',
            'solicitante' => 'Lucas',
            'status' => 'solicitado',
        ]);
    }

    public function test_non_admin_cannot_update_status()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'solicitado']);

        $response = $this->putJson("/api/orders/{$order->id}/approve");

        $response->assertStatus(403);
    }

    public function test_admin_can_update_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $order = Order::factory()->create(['user_id' => $admin->id, 'status' => 'solicitado']);

        $response = $this->putJson("/api/orders/{$order->id}/approve");

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'aprovado']);
    }

    public function test_user_sees_only_his_orders()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create();

        Order::factory()->create(['user_id' => $user->id, 'destino' => 'São Paulo']);
        Order::factory()->create(['user_id' => $otherUser->id, 'destino' => 'Rio de Janeiro']);

        $this->actingAs($user);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonFragment(['destino' => 'São Paulo']);
        $response->assertJsonMissing(['destino' => 'Rio de Janeiro']);
    }

    public function test_admin_sees_all_orders()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Order::factory()->create(['destino' => 'São Paulo']);
        Order::factory()->create(['destino' => 'Rio de Janeiro']);

        $this->actingAs($admin);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonFragment(['destino' => 'São Paulo']);
        $response->assertJsonFragment(['destino' => 'Rio de Janeiro']);
    }

    public function test_admin_can_filter_orders_by_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Order::factory()->create(['status' => 'aprovado']);
        Order::factory()->create(['status' => 'solicitado']);

        $this->actingAs($admin);

        $response = $this->getJson('/api/orders?status=aprovado');

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'aprovado']);
        $response->assertJsonMissing(['status' => 'solicitado']);
    }

    public function test_admin_can_filter_orders_by_destination()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Order::factory()->create(['destino' => 'São Paulo']);
        Order::factory()->create(['destino' => 'Rio de Janeiro']);

        $this->actingAs($admin);

        $response = $this->getJson('/api/orders?destino=São Paulo');

        $response->assertStatus(200);
        $response->assertJsonFragment(['destino' => 'São Paulo']);
        $response->assertJsonMissing(['destino' => 'Rio de Janeiro']);
    }

    public function test_admin_can_filter_orders_by_date_range()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Order::factory()->create(['data_ida' => '2026-03-10']);
        Order::factory()->create(['data_ida' => '2026-04-01']);

        $this->actingAs($admin);

        $response = $this->getJson('/api/orders?data_inicio=2026-03-01&data_fim=2026-03-31');

        $response->assertStatus(200);
        $response->assertJsonFragment(['data_ida' => '2026-03-10']);
        $response->assertJsonMissing(['data_ida' => '2026-04-01']);
    }

    public function test_order_creation_fails_without_destination()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/orders', [
            'solicitante' => 'Lucas',
            'data_ida' => '2026-03-10',
            'data_volta' => '2026-03-15',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['destino']);
    }
}
