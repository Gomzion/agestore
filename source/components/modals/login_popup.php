<div class="notice-box ck-content">
  <img src="https://placeimg.com/640/640/any" alt="">

  <p>asdfasdfsadfasd공지사항</p>
  <p>asdfasdfsadfasd공지사항</p>
  <p>asdfasdfsadfasd공지사항</p>
  <p>asdfasdfsadfasd공지사항</p>
  <p>asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항</p>
  <p>asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항 asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항 asdfasdfsadfasd공지사항asdfasdfsadfasd공지사항</p>
</div>

<div class="btn-box">
  <button type="button" class="today-close-btn">1일 동안 보지 않음</button>
  <button type="button" class="close-btn">닫기</button>
</div>

<script>
$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
  content.find(".today-close-btn").click(function() {
    setCookie("login-popup", "1", 1);

    modalHide(content);
  });
});
</script>