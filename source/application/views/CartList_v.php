
	<div class="article cart-list">
		<div class="article-title">
			<h6>장바구니 현황</h6>

			<div class="right-group">
				<button type="button" class="excel-btn">엑셀 다운로드</button>
				<a href="/SanGoods?page=<?php echo $bp;?>" class="btn type-black-line size-small">목록</a>
			</div>
		</div>

		<div class="section-box">
			<div class="section-title">
				<h6><?php echo $GOOD->GOODS_NM;?></h6>
			</div>

			<div class="section-content">
				<div class="table-sub-caption">
					<div class="left-group">
						<button type="button" class="btn size-small">선택 항목 삭제</button>

						<ul class="period">
							<li><a href="/SanGoods/CartList?g_id=<?php echo $g_id;?>&bp=<?php echo $bp;?>&sw=<?php echo $sw;?>&range=6" <?php if($range == 6) echo "class='on'";?>>최근 6개월</a></li>
							<?php for($i=2023;$i<=date('Y');$i++) { ?>
								<li><a href="/SanGoods/CartList?g_id=<?php echo $g_id;?>&bp=<?php echo $bp;?>&sw=<?php echo $sw;?>&range=<?php echo $i;?>" <?php if($range == $i) echo "class='on'";?>><?php echo $i;?></a></li>
							<?php } ?>
						</ul>
					</div>

					<div class="right-group">
						<form action="/SanGoods/CartList" method="get" id="search-form">
							<input type="hidden" name="g_id" value="<?php echo $g_id;?>">
							<input type="hidden" name="bp" value="<?php echo $bp;?>">
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
					<form action="/Order/delcartmulti" method="post" id="cart-form" target="frmTarget">
					<table>
						<colgroup>
							<col width="60" />
							<col width="200" />
							<col width="240" />
							<col width="160" />
							<col />
						</colgroup>

						<thead>
						<tr>
							<th>
								<div class="check-item">
									<label for="all-check"><input type="checkbox" id="all-check"  name="cart[]" value="0" /></label>
								</div>
							</th>
							<th><p>장바구니 추가 시간</p></th>
							<th><p>소속 기관</p></th>
							<th><p>이름</p></th>
							<th><p>세부 상품명</p></th>
						</tr>
						</thead>

						<tbody>
						<?php foreach ($CARTLIST as $row) {?>
						<tr>
							<td>
								<div class="check-item">
									<label>
										<input type="checkbox" name="cart[]" value="<?php echo $row->CART_SQ?>"/>
									</label>
								</div>
							</td>
							<td><p><?php echo $row->CART_REG_DATETIME;?></p></td>
							<td><p><?php echo $row->HOSPITAL_NM;?></p></td>
							<td><p><?php echo $row->USER_NM;?></p></td>
							<td><p><?php echo $row->GPRICE_AMT;?>BOX - <?php echo number_format($row->PAY_PAID);?>원 <?php echo $row->PAY_SALE;?>% 할인 상품</p></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
					</form>
				</div>
			</div>
		</div>

		<div class="paging">
			<?php echo $this->pagination->create_links(); ?>
		</div>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		$(function() {
			$(".excel-btn").click(function() {
				// Disable the download button
				$(this).prop('disabled', true);

				$(this).css('background-color', '#b6b6b6');

				// Set the URL of the file download
				const url = '/SanGoods/downExcelCartList?g_id=<?php echo $g_id;?>&sw=<?php echo $sw;?>&range=<?php echo $range;?>';

				// Fetch the file as a Blob
				fetch(url)
					.then(response => response.blob())
					.then(blob => {
						// Create a temporary download link
						const downloadLink = document.createElement('a');
						downloadLink.href = URL.createObjectURL(blob);
						downloadLink.download = 'AGeStore_cartlist_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '') + '.xlsx';

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

			$(".left-group button").click(function() {
				const checkLength = $(".check-item input[name='cart[]']:checked").length;
				if (checkLength === 0) {
					alert("삭제할 항목을 선택해주세요");

					return false;
				}

				$("#cart-form").submit();
			});

			$('#all-check').click(function() {
				if ($(this).is(':checked')) {
					// All checkboxes are checked
					$('input[name="cart[]"]').prop('checked', true);
				} else {
					// All checkboxes are unchecked
					$('input[name="cart[]"]').prop('checked', false);
				}
			});

		});
	</script>
