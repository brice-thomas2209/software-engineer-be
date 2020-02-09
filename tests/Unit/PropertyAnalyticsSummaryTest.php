<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Property;

class PropertyAnalyticsSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_property_analytics_summary_by_suburb() {
        $this->artisan('db:seed --class=AnalyticTypesTableSeeder');
        $this->artisan('db:seed --class=PropertiesTableSeeder');
        $this->artisan('db:seed --class=PropertyAnalyticsTableSeeder');

        $property = Property::inRandomOrder()->first();
        $params = [
            'type' => 'suburb',
            'value' => $property->suburb
        ];
        $data = $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->getOriginalContent();
        
        $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->assertJsonStructure([
                'data' => [
                    'min',
                    'max',
                    'median',
                    'per_with_value',
                    'per_without_value'
                ]
            ])
            ->assertSuccessful();
    }

    public function test_can_get_property_analytics_summary_by_state() {
        $this->artisan('db:seed --class=AnalyticTypesTableSeeder');
        $this->artisan('db:seed --class=PropertiesTableSeeder');
        $this->artisan('db:seed --class=PropertyAnalyticsTableSeeder');

        $property = Property::inRandomOrder()->first();
        $params = [
            'type' => 'state',
            'value' => $property->state
        ];
        $data = $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->getOriginalContent();
        
        $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->assertJsonStructure([
                'data' => [
                    'min',
                    'max',
                    'median',
                    'per_with_value',
                    'per_without_value'
                ]
            ])
            ->assertSuccessful();
    }


    public function test_can_get_property_analytics_summary_by_country() {
        $this->artisan('db:seed --class=AnalyticTypesTableSeeder');
        $this->artisan('db:seed --class=PropertiesTableSeeder');
        $this->artisan('db:seed --class=PropertyAnalyticsTableSeeder');

        $property = Property::inRandomOrder()->first();
        $params = [
            'type' => 'country',
            'value' => $property->country
        ];
        $data = $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->getOriginalContent();
        
        $this
            ->authGetJson(route('get.property.analytic.summary', $params))
            ->assertJsonStructure([
                'data' => [
                    'min',
                    'max',
                    'median',
                    'per_with_value',
                    'per_without_value'
                ]
            ])
            ->assertSuccessful();
    }
}
