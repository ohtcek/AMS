<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function isPast($date)
    {
        // 過去の日付かチェックするメソッド
        return $date < now()->startOfDay();
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getDate = $request->getDate;
            $getPart = $request->getPart;
            $reserveDays = array_filter(array_combine($getDate, $getPart));

            // 予約をまとめて取れるように配列にする
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where(
                    'setting_part',
                    $value
                )->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
                // where('setting_reserve', $key)->where('setting_part'→ReserveSettingsモデルの
                // setting_reserveカラムの$key(送られてきたデータ(日付)を$keyにしてる　名前はなんでもいい)と、
                // setting_partカラムの$value(送られてきた部)で一致させる

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getDate;
            // $reserveDays = array_filter(array_combine($getDate, $getPart));
            // ここ3つで予約についてのデータを取得する
            // 予約の時はまとめていくつも予約を取るからペアにする必要があるけど、
            // キャンセルの時は一個ずつだから、ペアにする必要はない
            // foreach ($reserveDays as $key => $value) {
            // $reserveDays の各日付 ($key) と部数 ($value) のペアに対して繰り返し処理を行う。
            $reserve_settings = ReserveSettings::where('setting_reserve', $getDate)
                ->where('setting_part', $getPart)
                ->first();
            // 取得して持ってきたgetPart(部)がsetting_reserveと一緒かどうか
            // $reserve_settings->increment('limit_users');
            // reserveでdecrementを使う=予約の際予約枠の人数を減らす文
            // →予約キャンセルの場合は、予約枠が増えるので逆のincrementで増やす処理

            $reserve_settings->increment('limit_users');
            $reserve_settings->users()->detach(Auth::id());
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
