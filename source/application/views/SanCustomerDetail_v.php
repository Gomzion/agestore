
	<div class="article customer-detail">
		<div class="article-title">
			<h6><?php echo $HOSPITAL->HOSPITAL_NM;?></h6>

			<div class="right-group">
				<a href="/SanCustomer" class="btn type-black-line size-small">목록</a>
			</div>
		</div>

		<div class="section-box">
			<div class="section-title">
				<h6>기본 정보</h6>
			</div>

			<div class="section-content">
				<div class="row-info">
					<p>거래처 코드 (SAP) : </p>
					<?php echo $HOSPITAL->HOSPITAL_CD;?>
				</div>

				<div class="row-info">
					<p class="big">사업자 등록번호 : </p>
					<div class="business-box">
						<div class="input-box">
							<input type="text"
								   name="business_number"
								   id="business_number"
								   class="business-number"
								   maxlength="12"
								   placeholder="사업자 등록번호를 입력해 주세요."
								   value="<?php echo $HOSPITAL->HOSPITAL_CORP_CD;?>"
								   disabled
							/>
						</div>

						<div class="file-box">
							<div class="input-file">
								<label>
									<a href="/Common/dnFile?id=<?php echo $HOSPITAL->HOSPITAL_CD;?>"><p><?php echo $HOSPITAL->HOSPITAL_CORP_NM;?></p></a>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row-info">
					<p>담당자 : </p>
					<?php if($HOSPITAL->HOSPITAL_SALES_CD) {?><?php echo $HOSPITAL->HOSPITAL_SALES_NM;?>(<?php echo $HOSPITAL->HOSPITAL_SALES_CD;?>)<?php } ?>
				</div>
			</div>
		</div>

		<div class="section-box middle">
			<div class="section-title">
				<h6>소속 선생님</h6>
			</div>

			<div class="section-content">
				<div class="table-style-a">
					<table>
						<colgroup>
							<col width="160" />
							<col width="200" />
							<col width="120" />
							<col />
							<col />
							<col width="200" />
						</colgroup>

						<thead>
						<tr>
							<th><p>등록일</p></th>
							<th><p>소속 기관</p></th>
							<th><p>소속 선생님</p></th>
							<th><p>AGMVP ID</p></th>
							<th><p>연락처</p></th>
							<th><p>가입 정보</p></th>
						</tr>
						</thead>

						<tbody>
						<?php foreach ($USERS as $row) {
							$dayAndWeek = "";
							if($row->USER_REG_DT) {
								$date = substr($row->USER_REG_DT, 0, 10);
								$dayOfWeek = changeWeekNameKR(date("w", strtotime($date)));
								$dayAndWeek = $date." (".$dayOfWeek.")";
							}?>
						<tr>
							<td><p><?php echo $dayAndWeek;?></p></td>
							<td><p><?php echo $HOSPITAL->HOSPITAL_NM;?></p></td>
							<td><p><?php echo $row->USER_NM;?></p></td>
							<td><p><?php echo $row->USER_ID;?></p></td>
							<td><p><?php echo $row->USER_HPN_NO;?></p></td>
							<td><p><?php if($row->USER_SIGN_IL) { ?><a href="#">동의서</a><?php } else { ?>-<?php } ?></p></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>

		<div class="section-box">
			<div class="section-title">
				<h6>거래 내역</h6>
			</div>

			<div class="section-content">
				<div class="table-sub-caption">
					<div class="left-group">
						<ul class="period">
							<li><a href="/SanCustomer/Detail/<?php echo $HOSPITAL->HOSPITAL_CD;?>?range=6" <?php if($range == 6) echo "class='on'";?>>최근 6개월</a></li>
							<?php for($i=2023;$i<=date('Y');$i++) { ?>
								<li><a href="/SanCustomer/Detail/<?php echo $HOSPITAL->HOSPITAL_CD;?>?range=<?php echo $i;?>" <?php if($range == $i) echo "class='on'";?>><?php echo $i;?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>

				<div class="table-style-a">
					<table>
						<colgroup>
							<col width="200" />
							<col width="120" />
							<col width="160" />
							<col width="200" />
							<col width="120" />
							<col />
							<col width="120" />
						</colgroup>

						<thead>
						<tr>
							<th><p>상품명</p></th>
							<th><p>상태</p></th>
							<th><p>구매 일시</p></th>
							<th><p>소속 기관</p></th>
							<th><p>이름</p></th>
							<th><p>세부 상품명</p></th>
							<th><p>주문 가격</p></th>
						</tr>
						</thead>

						<tbody>
						<?php foreach ($PAYLIST as $row) {?>
						<tr>
							<td><p><strong><?php echo $row->GOODS_NM;?></strong></p></td>
							<?php if($row->PAYGP_ST == ST_READY) { ?>
								<td><p><span class="sticker ready">상품 준비중</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_DLVY_ING) { ?>
								<td><p><span class="sticker dlvy-ing">배송중</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_DLVY_FINISH) { ?>
								<td><p><span class="sticker dlvy-finish">배송 완료</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_CANCEL) { ?>
								<td><p><span class="sticker cancel">주문 취소</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_EXCHANGE) { ?>
								<td><p><span class="sticker clame">교환 신청</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_CLAME) { ?>
								<td><p><span class="sticker clame">반품 신청</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_EXCHANGE_FINISH) { ?>
								<td><p><span class="sticker clame-finish">교환 완료</span></p></td>
							<?php } else if($row->PAYGP_ST == ST_CLAME_FINISH) { ?>
								<td><p><span class="sticker clame-finish">반품 완료</span></p></td>
							<?php } ?>
							<td><p><?php echo substr($row->PAYGP_REG_DT, 0, 16);?></p></td>
							<td><p><?php echo $HOSPITAL->HOSPITAL_NM;?></p></td>
							<td><p><?php echo $row->USER_NM;?></p></td>
							<td><p><?php echo $row->GPRICE_AMT;?>BOX - <?php echo number_format($row->PAY_PAID);?>원 <?php echo $row->PAY_SALE;?>% 할인 상품</p></td>
							<td><p><?php echo number_format($row->PAY_PAID);?>원</p></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

				<div class="paging">
					<?php echo $this->pagination->create_links(); ?>
				</div>
			</div>
		</div>
	</div>
