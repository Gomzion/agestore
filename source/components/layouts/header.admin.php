<div id="header">
  <div class="inner-top">
    <a href="/Common/goUser" class="mode-btn">사용자 모드</a>

    <a href="<?=GOBACK_AGMVP?>" class="return-to">AGMVP로 돌아가기</a>

    <div class="hello-group">
      <p class="hello"><strong><?php echo $this->session->userdata['name'];?></strong> 선생님 안녕하세요.</p>
      <a href="/Common/logout" class="logout-btn">로그아웃</a>
    </div>
  </div>

  <div class="inner-bottom">
    <h1 class="logo"><a href="<?php echo HOME_ADMIN;?>">AG e-Store</a></h1>

    <ul class="gnb">
      <li>
        <a href="/SanGoods" class="<?php if ($page_name == "product_setting") { ?>on<?php } ?>">상품 관리</a>
      </li>
      <li>
        <a href="/SanCustomer" class="<?php if ($page_name == "customer") { ?>on<?php } ?>">고객 정보</a>
      </li>
      <li>
        <a href="/SanPopup" class="<?php if ($page_name == "popup_setting") { ?>on<?php } ?>">팝업 관리</a>
      </li>
      <li>
        <a href="/SanTerm" class="<?php if ($page_name == "term_setting") { ?>on<?php } ?>">약관 관리</a>
      </li>
    </ul>
  </div>
</div>
