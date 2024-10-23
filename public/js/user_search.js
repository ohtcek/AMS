// ユーザー検索画面の条件追加
$(function () {
  $('.search_conditions, .v').click(function () {
    $('.search_conditions_inner').slideToggle();
    $('.v').toggleClass('active');
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
  });
});


// プロフィール画面の選択科目
$(function () {
  $('.profile-v').click(function () {
    $('.subject_inner').slideToggle();
    $('.profile-v').toggleClass('active');
  });
});

// 投稿のカテゴリー
$(function () {
  $('.main-category').click(function () {
    var $subCategoryList = $(this).next('.sub-category-list');
    var $categoryArrow = $(this).find('.category-v'); // 矢印部分を取得

    // 他のサブカテゴリーリストを閉じる
    $('.sub-category-list').not($subCategoryList).slideUp();
    $('.category-v').not($categoryArrow).removeClass('active'); // 他の矢印を元に戻す

    // 押されたサブカテゴリーリストをスライドトグル
    $subCategoryList.slideToggle();
    $categoryArrow.toggleClass('active'); // 矢印を反転させる
  });
});
