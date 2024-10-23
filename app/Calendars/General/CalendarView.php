<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th style="color: blue;">土</th>';
    $html[] = '<th style="color: red;">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $startDay = $this->carbon->copy()->startOfMonth();
    $today = Carbon::today();
    // 今日の日付を定義

    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();
      foreach ($days as $day) {
        // $dayDate = Carbon::parse($day->everyDay());
        // 日付をCarbonインスタンスに変換
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        $isSaturday = $day->getClassName() == 'day-sat'; // 土曜日かどうかを確認
        $isSunday = $day->getClassName() == 'day-sun';

        $class = $day->getClassName();

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $class = 'past-day';
          if ($isSaturday) $class .= ' day-sat';
          if ($isSunday) $class .= ' day-sun';
          $html[] = '<td class="calendar-td  ' . $class . '  past-day">';
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . ' calendar-cell">';
        }
        $html[] = $day->render();
        // 過去日なら背景色をグレーにする

        // 予約している場合(今後も過去も含めて)
        if (in_array($day->everyDay(), $day->authReserveDay())) {
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          $reserveDate = $day->authReserveDate($day->everyDay())->first()->setting_reserve;
          if ($reservePart == 1) {
            $reservePart = "リモ1部";
          } else if ($reservePart == 2) {
            $reservePart = "リモ2部";
          } else if ($reservePart == 3) {
            $reservePart = "リモ3部";
          }

          // 過去日
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $partWithoutPrefix = str_replace('リモ', '', $reservePart);
            // リモ〜部の「リモ」部分をなくして表示させる定義
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; margin-top: 10px !important;">' . $partWithoutPrefix . '参加</p>';
            // 参加した部数を表示させる　$reservePartに代入させる
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }

          // 今日以降
          else {
            $html[] = '<button type="submit"
            class="btn btn-danger calendar-modal p-0 w-75 calendar-cell"
            name="delete_date"
            style="font-size:12px;  margin-top: 10px !important;"

            cancel-date="' . $reserveDate . '"
            cancel-time="' .
              $day->authReserveDate($day->everyDay())->first()->setting_part . '"

            date="予約日:' . $reserveDate . '"
            time="時間:' . $reservePart . '"
            >'
              . $reservePart .
              '</button>';
            $html[] = '<input type="hidden" name="getPart[]" class="calendar-cell" value="" form="reserveParts">';
          }
          // valueで送ってるからjsでvalue
          // cancel-date="' . $reserveDate . '"はキャンセルするときに、カラムに入っている[数字のみ]と一致させる必要があるため、文字列にしない状態の数字を取得するもの。モーダルに表示させる予約日：⚪︎⚪︎は文字列なので、[date]として別の行で定義してる
          // cancel-timeも、文字列ではなく数字のみの取得が必要なので、文字列にする前に取得していたやつをそのまま記述
        }

        // 予約してない場合
        else {
          // ↓このif文で過去だったら受付終了と表示させ、過去じゃなかったら(elseだったら)partの四角を表示させる
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="m-auto p-0 w-75 calendar-reserve-end" style="margin-top: 10px !important;">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]"  value="" form="reserveParts">';
            // 過去の「予約している日」と「過去の予約していない日」それぞれでgetPartを送らないと受け取れない
          } else {
            $html[] = $day->selectPart($day->everyDay());
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  public function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
