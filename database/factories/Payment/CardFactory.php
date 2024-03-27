<?php

namespace Database\Factories\Payment;

use App\Models\Payment\Card;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class CardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'type' => function () {
                do {
                    $cart_type = strtolower($this->faker->creditCardType());
                } while( ! in_array($cart_type, ['visa', 'mastercard']));

                return $cart_type;
            },
            'last4digits' => $this->faker->randomNumber(4, true),
            'token' => $this->faker->sha1(),
            'token_lifetime' => Carbon::now(),
            'comment' => $this->faker->words(rand(0,9), true),
        ];
    }
}
