<?php

use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\AnalyticType;
use Illuminate\Database\Eloquent\Model;

class AnalyticTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $csv = Reader::createFromPath(storage_path('app/public/analytic_types.csv'));
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        foreach ($records as $record) {
            var_dump($record['is_numeric']);
            AnalyticType::create([
                'id' => $record['id'],
                'name' => $record['name'],
                'units' => $record['units'],
                'is_numeric' => ($record['is_numeric'] === 'TRUE'),
                'num_decimal_places' => $record['num_decimal_places']
            ]);
        }
    }
}
