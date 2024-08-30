$(function () {
  // 予約済みの部のボタン（クラスml-3）が押されたら発火
  $('.ml-3').on('click', function () {
    // モーダルの表示
    $('.modal').fadeIn();
    console.log('jQuery is loaded');
    // 予約日や時間などのデータをモーダルに渡す
    var date = $(this).attr('data-date');
    var time = $(this).text(); // 部の情報を取得

    $('#reservationDate').text(date);
    $('#reservationTime').text(time);

    return false;
  });

  // モーダルの閉じるボタンを押したらモーダルを閉じる
  $('.close').on('click', function () {
    $('.modal').fadeOut();
    return false;
  });
});
