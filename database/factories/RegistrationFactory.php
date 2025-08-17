<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition()
    {
        return [
            'username'     => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
            'token'        => Str::uuid(),
            'expired_at'   => Carbon::now()->addDays(7),
            'is_active'    => true,
        ];
    }
}
