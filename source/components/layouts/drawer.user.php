<div id="drawer">
  <div class="inner">
    <h4><strong><?php echo $this->session->userdata['name'];?></strong> 선생님 안녕하세요.</h4>

    <a href="/Common/logout" class="logout-btn">로그아웃</a>

    <ul class="quick-menu">
      <li>
        <a href="/Order/Cart" class="cart" id="cartd"><?php if($CARTAMT) echo "<span>".$CARTAMT."</span>";?></a>
      </li>
      <li>
        <a href="/Dlvy" class="dlvy"><?php if($DLVYAMT) echo "<span>".$DLVYAMT."</span>";?></a>
      </li>
      <li>
        <a href="/My" class="mypage">마이페이지</a>
      </li>
    </ul>

    <ul class="lnb">
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
