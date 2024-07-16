<?php

// namespace Database\Seeds;

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use App\Models\Users\Subjects;

class UserSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::where('mail_address', 'tropius09@gmail.com')->first();
        $subject1 = Subjects::where('subject', '国語')->first();
        $subject2 = Subjects::where('subject', '数学')->first();
        $subject3 = Subjects::where('subject', '英語')->first();

        if ($user && $subject1 && $subject2 && $subject3) {
            $user->subjects()->attach([$subject1->id, $subject2->id, $subject3->id]);
        }
    }
}
