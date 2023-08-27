<div class="title-box">
  <h5>장바구니 담기</h5>

  <button type="button" class="close-btn">닫기</button>
</div>

<div class="form-box">
  <form action="" method="post" id="cart-form">
    <div class="input-row-group">
      <div class="input-row not-label">
        <div class="dropdown-box placeholder">
          <input type="hidden" name="option" id="option" />

          <button type="button" tabindex="-1" placeholder="옵션을 선택해 주세요.">옵션을 선택해 주세요.</button>

          <div class="dropdown-list">
            <ul>
              <li>
                <button type="button" data-value="1BOX - 10,000원">1BOX - 10,000원</button>
              </li>
              <li>
                <button type="button" data-value="1BOX - 7,900원 20% 할인 상품">1BOX - 7,900원 20% 할인 상품</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="submit-btn">
      <input type="submit" value="장바구니 담기" />
    </div>
  </form>
</div>

<script>
$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
  content.find("#cart-form").submit(function() {
    if (this.option.value === "") {
      alert("옵션을 선택해 주세요.");

      return false;
    }
  });
});
</script>