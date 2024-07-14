<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'mail_address' => 'tropius09@gmail.com',
        ], [
            'over_name' => '大畠',
            'under_name' => 'うるちゃん',
            'over_name_kana' => 'オオハタ',
            'under_name_kana' => 'ウルチャン',
            'sex' => 2,
            'birth_day' => '1994-12-11',
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('0000')
        ]);
    }
}
