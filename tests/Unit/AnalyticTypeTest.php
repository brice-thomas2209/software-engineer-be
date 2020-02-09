<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Property;
use App\AnalyticType;

class AnalyticTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_analytic_to_property() {
        $property = factory(Property::class)->create();
        
        // serialize factory class to array
        $data = factory(AnalyticType::class)->make()->toArray();
        $value = $this->faker->randomDigit;     

        $response = $this
            ->authPostJson(
                route('post.property.analytic', $property->id)
                , $data + [ 'value' => $value]);

        $analyticType = $response->getOriginalContent();

        $response->assertJsonFragment($data)
            ->assertSuccessful();
        
        // check if the record is in the database
        $this->assertDatabaseHas((new AnalyticType)->getTable(), [
            'id' => $analyticType->id
        ]);

        // check if the relationship in the database
        $this->assertDatabaseHas((new Property)->analyticTypes()->getTable(), [
            'property_id' => $property->id,
            'analytic_type_id' => $analyticType->id,
            'value' => $value
        ]);
    }

    public function test_can_update_analytic_to_property() {
        $property = factory(Property::class)->create();
        $analyticType = factory(AnalyticType::class)->create();
        // serialize factory class to array
        $data = [
            'name' => collect(['max_Bld_Height_m', 'min_lot_size_m2', 'fsr'])->random(),
            'num_decimal_places' => $this->faker->randomDigit
        ];
        $value = $this->faker->randomDigit;

        $response = $this
            ->authPatchJson(
                route('patch.property.analytic', [$property->id, $analyticType->id])
                , $data + ['value' => $value]);

        $analyticType = $response->getOriginalContent();

        $response->assertJsonFragment($data)
            ->assertSuccessful();
        
        // check if the record is in the database
        $this->assertDatabaseHas((new AnalyticType)->getTable(), [
            'id' => $analyticType->id
        ] + $data);

        // check if the relationship in the database
        $this->assertDatabaseHas((new Property)->analyticTypes()->getTable(), [
            'property_id' => $property->id,
            'analytic_type_id' => $analyticType->id,
            'value' => $value
        ]);
    }

    public function test_can_get_analytics_from_property() {
        $property = factory(Property::class)->create();
        factory(AnalyticType::class, 5)
            ->create()
            ->each(function($at) use ($property) {
                $at->properties()->attach($property);
            });
        $this
            ->authGetJson(
                route('get.property.analytic', $property->id))
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'created_at',
                        'updated_at',
                        'name',
                        'units',
                        'is_numeric',
                        'num_decimal_places',
                        'pivot' => [
                            'property_id',
                            'analytic_type_id'
                        ]
                    ]
                ]
            ])
            ->assertSuccessful();
        
        // // check if the record is in the database
        $this
            ->assertCount(
                5,
                $property->analyticTypes()->get()
            );
    }
}
