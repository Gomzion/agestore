
	<div class="article customer">
		<div class="article-title">
			<h6>고객 정보</h6>

			<div class="right-group">
				<button type="button" class="excel-btn">엑셀 다운로드</button>
			</div>
		</div>

		<div class="section-box">
			<div class="section-content">
				<div class="table-sub-caption">
					<div class="left-group">
					</div>

					<div class="right-group">
						<form action="/SanCustomer" method="get" id="search-form">
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
							<col width="160" />
							<col width="160" />
							<col width="200" />
							<col width="120" />
							<col />
							<col width="100" />
						</colgroup>

						<thead>
						<tr>
							<th><p>거래처 코드 (SAP)</p></th>
							<th><p>소속 기관</p></th>
							<th><p>소속 선생님</p></th>
							<th><p>사업자 등록번호</p></th>
							<th><p>담당자</p></th>
							<th><p>누적 구매 금액</p></th>
							<th><p>정보 변경</p></th>
						</tr>
						</thead>

						<tbody>
						<?php foreach ($customer as $cd => $ct) {?>
						<tr>
							<td><p><?php echo $cd;?></p></td>
							<td><p><?php echo $ct->HOSPITAL_NM;?></p></td>
							<td><p><?php echo $ct->USER_NM;?></p></td>
							<td><p>
									<?php if($ct->HOSPITAL_CORP_NM) {?>
										<a href="/Common/dnFile?id=<?php echo $cd;?>"><?php echo $ct->HOSPITAL_CORP_CD;?></a>
									<?php } else { ?>
										<?php echo $ct->HOSPITAL_CORP_CD;?>
									<?php } ?>
								</p></td>
							<td><p><?php if($ct->HOSPITAL_SALES_CD) echo "$ct->HOSPITAL_SALES_NM($ct->HOSPITAL_SALES_CD)"; else echo "N";?></p></td>
							<td><p><?php echo number_format($ct->TOTAL_PAY_PAID);?>원</p></td>
							<td><p><a href="/SanCustomer/Detail/<?php echo $cd;?>" class="modify-btn">정보 변경</a></p></td>
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
		$(function() {
			$(".excel-btn").click(function() {
				// Disable the download button
				$(this).prop('disabled', true);

				$(this).css('background-color', '#b6b6b6');

				// Set the URL of the file download
				const url = '/SanCustomer/downExcel?sw=<?php echo $sw;?>';

				// Fetch the file as a Blob
				fetch(url)
					.then(response => response.blob())
					.then(blob => {
						// Create a temporary download link
						const downloadLink = document.createElement('a');
						downloadLink.href = URL.createObjectURL(blob);
						downloadLink.download = 'AGeStore_customer_' + new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '') + '.xlsx';

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
