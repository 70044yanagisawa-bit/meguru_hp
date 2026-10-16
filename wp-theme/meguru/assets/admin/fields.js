/* めぐるHP制作 — 管理画面の入力欄（写真を選ぶ・行を足す／消す／並べ替える） */
(function ($) {
  'use strict';

  /* ---------- 写真 ---------- */
  $(document).on('click', '.meguru-image__pick', function (e) {
    e.preventDefault();
    var box = $(this).closest('.meguru-image');
    var frame = wp.media({ title: '写真を選ぶ', button: { text: 'この写真にする' }, library: { type: 'image' }, multiple: false });
    frame.on('select', function () {
      var a = frame.state().get('selection').first().toJSON();
      var url = (a.sizes && a.sizes.medium ? a.sizes.medium : a).url;
      box.find('input[type=hidden]').first().val(a.id);
      box.find('.meguru-image__preview').html($('<img alt="">').attr('src', url));
    });
    frame.open();
  });

  $(document).on('click', '.meguru-image__clear', function (e) {
    e.preventDefault();
    var box = $(this).closest('.meguru-image');
    box.find('input[type=hidden]').first().val('');
    box.find('.meguru-image__preview').html($('<span>').text(box.data('empty')));
  });

  /* ---------- 行を足す・消す・並べ替える ---------- */
  function renumber(rep) {
    rep.find('> .meguru-rep__rows > .meguru-rep__row').each(function (i) {
      $(this).find('> .meguru-rep__bar .meguru-rep__no').text((i + 1) + '.');
    });
  }

  $('.meguru-rep').each(function () { renumber($(this)); });

  $(document).on('click', '.meguru-rep__add', function (e) {
    e.preventDefault();
    var rep = $(this).closest('.meguru-rep');
    var key = 'n' + Date.now();
    var html = rep.find('> .meguru-rep__tpl').html().split('__i__').join(key);
    rep.find('> .meguru-rep__rows').append(html);
    renumber(rep);
  });

  $(document).on('click', '.meguru-rep__del', function (e) {
    e.preventDefault();
    if (!window.confirm('この行を削除しますか？（「更新」を押すまでは保存されません）')) return;
    var rep = $(this).closest('.meguru-rep');
    $(this).closest('.meguru-rep__row').remove();
    renumber(rep);
  });

  $(document).on('click', '.meguru-rep__up, .meguru-rep__down', function (e) {
    e.preventDefault();
    var row = $(this).closest('.meguru-rep__row');
    if ($(this).hasClass('meguru-rep__up')) row.prev('.meguru-rep__row').before(row);
    else row.next('.meguru-rep__row').after(row);
    renumber($(this).closest('.meguru-rep'));
  });
})(jQuery);
