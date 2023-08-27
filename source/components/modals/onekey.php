<div class="title-box">
  <h5>소속 기관 정보 변경</h5>

  <button type="button" class="close-btn">닫기</button>
</div>

<div class="form-box">
  <form action="" method="post" id="cart-form">
    <div class="input-row-group">
      <div class="input-row">
        <p class="label">소속 기관</p>
        
        <div class="dropdown-box placeholder agency">
          <input type="hidden" name="state" id="state" />

          <button type="button" tabindex="-1" placeholder="시/도">시/도 선택</button>

          <div class="dropdown-list">
            <ul>
              <li>
                <button type="button" data-value="경기도">경기도</button>
              </li>
              <li>
                <button type="button" data-value="서울시">서울시</button>
              </li>
              <li>
                <button type="button" data-value="인천광역시">인천광역시</button>
              </li>
            </ul>
          </div>
        </div>

        <div class="dropdown-box placeholder agency" data-field="region">
          <input type="hidden" name="region" id="region" />

          <button type="button" tabindex="-1" placeholder="시/구/군">시/구/군</button>

          <div class="dropdown-list">
            <ul>
              <li>
                <button type="button" data-value="수원시">수원시</button>
              </li>
              <li>
                <button type="button" data-value="성남시">성남시</button>
              </li>
              <li>
                <button type="button" data-value="용인시">용인시</button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="input-row not-label">
        <div class="input-box agency">
          <input type="text"
            name="agency"
            autocomplete="off"
            placeholder="소속 기관을 검색해 주세요."
          />

          <button type="button" id="onekey-search" tabindex="-1">검색</button>

          <div class="agency-list">
            <ul>
              <li>
                <button type="button">ABCDEFG12345678910</button>
              </li>
              <li>
                <button type="button">ABCDEFG12345678910</button>
              </li>
              <li>
                <button type="button">ABCDEFG12345678910</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="input-row">
        <label for="business_number">사업자 등록번호</label>

        <div class="input-box">
          <input type="text" 
            name="business_number" 
            id="business_number"
            class="business-number"
            maxlength="12"
            placeholder="사업자 등록번호를 입력해 주세요."
          />
        </div>
      </div>

      <div class="input-row">
        <p class="label">사업자 등록증 업로드</p>

        <div class="file-box">
          <div class="input-file">
            <label>
              <input type="file" name="business_license" required />  
              <p class="placeholder" placeholder="파일 선택">파일 선택</p>
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="submit-btn">
      <input type="submit" value="변경 사항 저장" />
    </div>
  </form>
</div>

<script>
$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
  content.find("#state").on("change", function() {
    const dummy = [
      {
        label : "경기도 수원시",
        value : "경기도 수원시"
      }, {
        label : "서울시 강남구",
        value : "경기도 수원시"
      }
    ]

    dropdownRender(content.find(".dropdown-box[data-field=region]"), dummy);
  });
});
</script>