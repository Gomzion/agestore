function customDrawer() {
  const $drawer = $("#drawer");

  return {
    show: function() {
      $("body").addClass("no-scroll");

      $drawer.addClass("open");
      $drawer.trigger("drawerShow");
    },
    hide: function() {
      $drawer.addClass("open-out");

      setTimeout(function() {
        $("body").removeClass("no-scroll");
        $drawer.removeClass("open open-out");
      }, 300);
    }
  }
}

$(function() {
  const drawer = customDrawer();

  // drawer 이벤트
  $(".menu-btn").click(function() {
    drawer.show();
  });

  $(window).resize(function() {
    if ($(this).width() >= 1200 && $("#drawer").hasClass("open")) {
      drawer.hide();
    }
  });

  $(document).on("click", "#drawer", function(e) {
    if ($(e.target).hasClass(".inner") || $(e.target).closest(".inner").length > 0) {
      return;
    }

    drawer.hide();
  });
});