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
                'email' => 'admin@on.dtix.com',
                'password' => Hash::make('admin123'),
                'level' => 'Admin',
            ],
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@on.dtix.com',
                'password' => Hash::make('user123'),
                'level' => 'Penumpang',
            ],
            [
                'name' => 'Petugas',
                'username' => 'petugas',
                'email' => 'petugas@on.dtix.com',
                'password' => Hash::make('petugas123'),
                'level' => 'Petugas',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'username' => $user['username'],
                'password' => $user['password'],
                'level' => $user['level'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
