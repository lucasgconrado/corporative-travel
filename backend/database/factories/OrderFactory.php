<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'solicitante' => $this->faker->name(),
            'destino' => $this->faker->city(),
            'data_ida' => $this->faker->date(),
            'data_volta' => $this->faker->date(),
            'status' => 'solicitado',
        ];
    }
}
