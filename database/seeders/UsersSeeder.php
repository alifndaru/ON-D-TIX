<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'level' => 'Admin',
            ],
            [
                'name' => 'User',
                'username' => 'user',
                'password' => Hash::make('user123'),
                'level' => 'Penumpang',
            ],
            [
                'name' => 'Petugas',
                'username' => 'petugas',
                'password' => Hash::make('petugas123'),
                'level' => 'Petugas',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'username' => $user['username'],
                'password' => $user['password'],
                'level' => $user['level'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
