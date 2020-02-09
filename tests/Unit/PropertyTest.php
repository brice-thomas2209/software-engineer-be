<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Property;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_property() {

        $data = [
            'suburb' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country
        ];
        
        $this
            ->authPostJson(route('post.property'), $data)
            ->assertJsonFragment($data)
            ->assertSuccessful();

        $this->assertDatabaseHas((new Property)->getTable(), $data);
    }
}
