<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // ลบข้อมูลเก่าออกไปก่อน
        DB::table('users')->delete();
        
        $data = [
            'fullname' => 'Sanon Taweekoon',
            'username' => 'iamsanon',
            'email' => 'sanon@email.com',
            'password' => Hash::make('123456'),
            'tel' => '0623845661',
            'avatar' => 'https://avatars.githubusercontent.com/u/97165289',
            'role' => '1',
            'remember_token' => Str::random(10)
        ];

        User::create($data);

        // ทำการเรียก UserFactory มาโหลดใน UserSeeder
        User::factory(99)->create();
    }
}
