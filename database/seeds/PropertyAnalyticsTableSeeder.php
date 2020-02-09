<?php

use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\AnalyticType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PropertyAnalyticsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $csv = Reader::createFromPath(storage_path('app/public/property_analytics.csv'));
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $propertyAnalytics = [];
        foreach ($records as $record) {
            $propertyAnalytics[] = [
                'property_id' => $record['property_id'],
                'analytic_type_id' => $record['anaytic_type_id'],
                'value' => $record['value']
            ];
        }
        DB::table('property_analytics')
            ->insert($propertyAnalytics);
    }
}
