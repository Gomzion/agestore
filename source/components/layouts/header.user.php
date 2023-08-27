<div id="header">
  <div class="inner-top">
    <a href="/Common/goAdmin" class="mode-btn">관리자 모드</a>

    <a href="#" class="return-to">AGMVP로 돌아가기</a>

    <div class="hello-group">
      <p class="hello"><strong><?php echo $this->session->userdata['name'];?></strong> 선생님 안녕하세요.</p>
      <a href="/Common/logout" class="logout-btn">로그아웃</a>
    </div>
  </div>

  <div class="inner-bottom">
    <button type="button" class="menu-btn">메뉴 열기/닫기</button>

    <h1 class="logo"><a href="<?php echo HOME_USER;?>">AG e-Store</a></h1>

    <div class="util-group">
      <a href="/Order/Cart" id="cart" class="cart"><?php if($CARTAMT) echo "<span>".$CARTAMT."</span>";?></a>
      <a href="/Dlvy" class="dlvy"><?php if($DLVYAMT) echo "<span>".$DLVYAMT."</span>";?></a>
      <a href="/My" class="mypage">마이페이지</a>
    </div>

    <ul class="gnb">
    <li>
        <a href="/Shop?category=" class="<?php if (isset($category) && $category == "") { ?>on<?php } ?>">전체 제품</a>
      </li>
		<?php foreach ($GTYPE as $type) {?>
		<li>
			<a href="/Shop?category=<?php echo $type->GTYPE_CD;?>" class="<?php if (isset($category) && $category == $type->GTYPE_CD) { ?>on<?php } ?>"><?php echo $type->GTYPE_NM;?></a>
		</li>
		<?php } ?>
    </ul>
  </div>
</div>
