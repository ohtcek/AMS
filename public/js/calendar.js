$(function () {
  // 予約済みの部のボタン（クラスml-3）が押されたら発火
  $('.btn-danger').on('click', function () {
    // モーダルの表示
    $('.modal').fadeIn();

    // 予約日や時間などのデータをモーダルに渡す
    var date = $(this).attr('date');
    // var date = $(this).val();
    var time = $(this).attr('time'); // 部の情報を取得
    // 変数の定義

    $('.reservationDate').text(date);
    // span idだから#,classなら.で
    $('.reservationTime').text(time);

    return false;
  });

  // モーダルの閉じるボタンを押したらモーダルを閉じる
  $('.close').on('click', function () {
    $('.modal').fadeOut();
    return false;
  });
});
