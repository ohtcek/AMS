<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;

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
        $subject1 = Subject::where('subject', '国語')->first();
        $subject2 = Subject::where('subject', '数学')->first();
        $subject3 = Subject::where('subject', '英語')->first();

        // ユーザーと科目の関連付け
        if ($user && $subject1 && $subject2 && $subject3) {
            $user->subjects()->attach([$subject1->id, $subject2->id, $subject3->id]);
        }
    }
}
