function customLoading () {
  let html = `
    <div class="loading-overlay key" style="display:none;">
      <span></span>
      <p>로딩중입니다.<br/>잠시만 기다려주세요.</p>
    </div>
  `;

  return {
    show: function(key, element) {
      if (!element) {
        element = $("body");
      }
      
      if (!key) {
        key = "loading-" + $(".loading-overlay").length;
      }

      html = html.replace(/key/g, key);

      element.addClass("no-scroll").append(html);

      $(".loading-overlay." + key).fadeIn();
    },
    hide: function(key) {
      if (!key) {
        key = "loading-" + $(".loading-overlay").length;
      }

      $(".loading-overlay." + key).fadeOut(400, function() {
        $(this).parent().removeClass("no-scroll");
        $(this).remove();
      });
    }
  }
};
/* example

파라메터 key 값으로 여러개의 로딩 생성 가능함.

loading.show("key", $(".somd-class"));

loading.hide("key");  

*/