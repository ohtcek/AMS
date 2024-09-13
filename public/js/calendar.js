$(function () {
  // 予約済みの部のボタン（クラスml-3）が押されたら発火
  $('.calendar-modal').on('click', function () {
    // モーダルの表示
    $('.modal').fadeIn();

    // 予約日や時間などのデータをモーダルに渡す
    var date = $(this).attr('date');
    // var date = $(this).val();
    var time = $(this).attr('time'); // 部の情報を取得
    // 変数の定義

    var cancelDate = $(this).attr('cancel-date'); // 数字だけを取得（予約日）
    var cancelTime = $(this).attr('cancel-time');

    $('.reservationDate').text(date);
    // span idだから#,classなら.で
    $('.reservationTime').text(time);

    $('.hidden-date').val(cancelDate);
    $('.hidden-part').val(cancelTime);

    return false;
  });

  // モーダルの閉じるボタンを押したらモーダルを閉じる
  $('.btn-close, .cross').on('click', function () {
    $('.modal').fadeOut();
    return false;
  });
});
