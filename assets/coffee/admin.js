// Generated by CoffeeScript 1.8.0
(function() {
  var $;

  $ = jQuery;

  $(function() {
    $(window).on('hashchange', function() {
      $('.tab-wrapper .tab-content').removeClass('active');
      $('.tab-wrapper ' + window.location.hash).addClass('active');
      $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
      $('.nav-tab-wrapper a[href="' + window.location.hash + '"]').addClass('nav-tab-active');
      return false;
    });
    if (0 < window.location.hash.length) {
      $(window).trigger('hashchange');
    }
    $('.tp-periods table .dashicons-trash').click(function() {
      return confirm(TP_Opening_Hours['periods_trash_confirm']);
    });
    return $('.tp-special table .dashicons-trash').click(function() {
      return confirm(TP_Opening_Hours['special_date_trash_confirm']);
    });
  });

}).call(this);
