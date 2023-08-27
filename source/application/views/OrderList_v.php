
	<div class="article order-list">
		<div class="article-title">
			<h6>구매 내역</h6>

			<div class="right-group">
				<button type="button" class="excel-btn">엑셀 다운로드</button>
				<a href="/SanGoods?page=<?php echo $bp;?>" class="btn type-black-line size-small">목록</a>
			</div>
		</div>

		<div class="section-box">
			<div class="section-title">
				<h6><?php echo $GOOD->GOODS_NM;?></h6>

				<h5 class="total-price">매출액 : <strong><?php echo number_format($ALLPAID);?>원</strong></h5>
			</div>

			<div class="section-content">
				<div class="table-sub-caption">
					<div class="left-group">
						<ul class="period">
							<li><a href="/SanGoods/OrderList?g_id=<?php echo $g_id;?>&bp=<?php echo $bp;?>&sw=<?php echo $sw;?>&st=<?php echo $st;?>&range=6" <?php if($range == 6) echo "class='on'";?>>최근 6개월</a></li>
							<?php for($i=2023;$i<=date('Y');$i++) { ?>
								<li><a href="/SanGoods/OrderList?g_id=<?php echo $g_id;?>&bp=<?php echo $bp;?>&sw=<?php echo $sw;?>&st=<?php echo $st;?>&range=<?php echo $i;?>" <?php if($range == $i) echo "class='on'";?>><?php echo $i;?></a></li>
							<?php } ?>
						</ul>
					</div>

					<div class="right-group">
						<form action="/SanGoods/OrderList" method="get" id="search-form">
							<input type="hidden" name="g_id" value="<?php echo $g_id;?>">
							<input type="hidden" name="bp" value="<?php echo $bp;?>">
							<div class="dropdown-box placeholder">
								<input type="hidden" name="st" id="status"/>

								<button type="button" tabindex="-1" placeholder="상태를 선택해 주세요.">상태를 선택해 주세요.</button>

								<div class="dropdown-list">
									<ul>
										<li>
											<button type="button" data-value="<?php echo ST_READY;?>">상품 준비중</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_DLVY_ING;?>">배송중</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_CANCEL;?>">주문 취소</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_DLVY_FINISH;?>">배송 완료</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_EXCHANGE;?>">교환 신청</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_EXCHANGE_FINISH;?>">교환 완료</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_CLAME;?>">반품 신청</button>
										</li>
										<li>
											<button type="button" data-value="<?php echo ST_CLAME_FINISH;?>">반품 완료</button>
										</li>
									</ul>
								</div>
							</div>

							<div class="input-box search">
								<input type="text"
									   name="sw"
									   placeholder="검색어를 입력해 주세요."
									   value="<?php echo $sw;?>"
								/>
							</div>

							<input type="submit" class="btn type-black size-small" value="검색" />
						</form>
					</div>
				</div>

				<div class="table-style-a">
					<table>
						<colgroup>
							<col width="160" />
							<col width="240" />
							<col width="200" />
							<col width="120" />
							<col />
							<col width="120" />
						</colgroup>

						<thead>
						<tr>
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
							<td><p><?php echo $row->HOSPITAL_NM;?></p></td>
							<td><p><?php echo $row->USER_NM;?></p></td>
							<td><p><?php echo $row->GPRICE_AMT;?>BOX - <?php echo number_format($row->PAY_PAID);?>원 <?php echo $row->PAY_SALE;?>% 할인 상품</p></td>
							<td><p><?php echo number_format($row->PAY_PAID);?>원</p></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="paging">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$(".dropdown-list button[data-value='<?php echo $st;?>']").trigger("click");
		});

		$(function() {
			$(".excel-btn").click(function() {
				// Disable the download button
				$(this).prop('disabled', true);

				$(this).css('background-color', '#b6b6b6');

				// Set the URL of the file download
				const url = '/SanGoods/downExcelPurchase?g_id=<?php echo $g_id;?>&st=<?php echo $st;?>&sw=<?php echo $sw;?>&range=<?php echo $range;?>';

				// Fetch the file as a Blob
				fetch(url)
					.then(response => response.blob())
					.then(blob => {
						// Create a temporary download link
						const downloadLink = document.createElement('a');
						downloadLink.href = URL.createObjectURL(blob);
						downloadLink.download = 'AGeStore_purchase_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '') + '.xlsx';

						// Trigger a click event on the download link
						downloadLink.click();

						// Listen for the load event to determine when the download is complete
						setTimeout(() => {
							alert('엑셀파일 다운로드 성공');

							// Enable the download button
							$(".excel-btn").prop('disabled', false);

							$(".excel-btn").css('background-color', '');
						}, 1000);
					})
					.catch(error => {
						console.error('Download error:', error);

						// Show the error alert
						alert('엑셀파일 실패');

						// Enable the download button
						$(".excel-btn").prop('disabled', false);

						$(".excel-btn").css('background-color', '');
					});
			});
		});
	</script>
