<?php

namespace Database\Seeders;


use App\Services\PfeService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/csv/data.csv"), "r");
        $itemLines= [];
        $firstLine = true;
        while (($dataMigrationItems = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if(!$firstLine){
                $obj = new \stdClass();
                $obj->full_name = $dataMigrationItems[0];
                $obj->area = $dataMigrationItems[1];
                $obj->specialty = $dataMigrationItems[2];
                $obj->age = $dataMigrationItems[3];
                $obj->email = $dataMigrationItems[4];
                $obj->phone = $dataMigrationItems[5];
                $obj->sex = $dataMigrationItems[6];
                $obj->freq_max = $dataMigrationItems[7];
                $obj->docs = $dataMigrationItems[8];
                $obj->terms = $dataMigrationItems[9];
                $obj->events = $dataMigrationItems[10];
                $itemLines[] = $obj;
            }
            $firstLine = false;
        }
        (new PfeService())->storeData($itemLines);
    }
}
