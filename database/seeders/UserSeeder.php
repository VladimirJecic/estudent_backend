<?php

namespace Database\Seeders;
use App\estudent\domain\model\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = 'test@example.com';
        $user->indexNum = '2023/0000';
        $user->password = Hash::make('test');
        $user->role = 'admin';
        $user->save();
        User::factory()->count(10)->create();
    }
}
