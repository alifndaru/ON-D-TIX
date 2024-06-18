<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terminal = [
            [
                'name' => 'Kalideres',
                'city' => 'Jakarta Barat',
                'province' => 'Jakarta',
            ],
            [
                'name' => 'Pulo Gadung',
                'city' => 'Jakarta Timur',
                'province' => 'Jakarta',
            ],
            [
                'name' => 'Pekalongan',
                'city' => 'Pekalongan',
                'province' => 'Jawa Tengah',
            ],
        ];

        foreach ($terminal as $terminals) {
            DB::table('terminal')->insert([
                'name' => $terminals['name'],
                'province' => $terminals['province'],
                'city' => $terminals['city'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
