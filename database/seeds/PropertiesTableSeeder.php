<?php

use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Property;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $csv = Reader::createFromPath(storage_path('app/public/properties.csv'));
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        foreach ($records as $record) {
            Property::create([
                'id' => $record['Property Id'],
                'guid' => Uuid::uuid1(),
                'suburb' => $record['Suburb'],
                'state' => $record['State'],
                'country' => $record['Counrty']
            ]);
        }
    }
}
