<div class="title-box">
	<h5>소속 기관 정보 변경</h5>

	<button type="button" class="close-btn">닫기</button>
</div>

<div class="form-box">
	<form action="/My/updateOrgan" method="post" id="cart-form" target="frmTarget" enctype="multipart/form-data">
		<div class="input-row-group">
			<div class="input-row">
				<p class="label">소속 기관</p>

				<div class="dropdown-box placeholder agency">
					<input type="hidden" name="state" id="state" />

					<button type="button" tabindex="-1" placeholder="시/도">시/도 선택</button>

					<div class="dropdown-list" id="ul_state">
						<ul>
							<?php foreach ($hospital_sdo as $val) {?>
								<li>
									<button type="button" data-value="<?php echo $val;?>"><?php echo $val;?></button>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>

				<div class="dropdown-box placeholder agency" data-field="region">
					<input type="hidden" name="region" id="region" />

					<button type="button" tabindex="-1" placeholder="시/구/군">시/구/군</button>

					<div class="dropdown-list" id="ul_region">
						<ul></ul>
					</div>
				</div>
			</div>

			<div class="input-row not-label">
				<div class="input-box agency">
					<input type="text"
						   name="agency"
						   autocomplete="off"
						   placeholder="소속 기관을 검색해 주세요."
						   value="<?php echo $USER->HOSPITAL_NM; ?>"
					/>
					<input type="hidden" name="hospital" id="hospital" />

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
						   value="<?php echo $USER->HOSPITAL_CORP_CD;?>"
					/>
				</div>
			</div>

			<div class="input-row">
				<p class="label">사업자 등록증 업로드</p>

				<div class="file-box-v2">
					<div class="input-file">
						<p class="" placeholder="파일을 선택해 주세요.">
							<?php if($USER->HOSPITAL_CORP_NM) {?>
							<a href="/Common/dnFile?id=<?php echo $USER->HOSPITAL_CD;?>"><?php echo $USER->HOSPITAL_CORP_NM;?></a>
							<?php } ?>
						</p>

						<label>
							<input type="file" name="business_license" />
							파일 선택
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
	dummy = [];
	<?php foreach ($hospital_sdo as $val) {?>
	dummy['<?php echo $val;?>'] = [
		<?php foreach ($hospital_info[$val] as $sggNm => $sval) {?>
		{ label : "<?php echo $sggNm;?>", value : "<?php echo $sggNm;?>" },
		<?php } ?>
	]
	<?php } ?>

	$(document).ready(function() {
		$(document).on("change", "#state", function() {
			dropdownRender($(".dropdown-box[data-field=region]"), dummy[this.value]);
		});

		$("#ul_state button[data-value='<?php echo $USER->HOSPITAL_SDO_NM; ?>']").trigger("click");
		$("#state").trigger("change");
		$("#ul_region button[data-value='<?php echo $USER->HOSPITAL_SGG_NM; ?>']").trigger("click");
	});

	$("#<?=$_GET["modal"]?>").bind("modalShow", function(e, content) {
		content.find("#state").on("change", function() {
			dropdownRender(content.find(".dropdown-box[data-field=region]"), dummy[this.value]);
		});
	});

	$(function() {
		$("#department").on("change", function() {
			$(this).closest(".input-row").removeClass("error-row").addClass("valid-row");
		});

		$("#cart-form").validate({
			rules: {
				agency: {
					required: true
				},
				business_number: {
					required: true,
					pattern: /^[0-9]{3}-[0-9]{2}-[0-9]{5}$/
				},
			},
			messages: {
				agency : "소속 기관을 입력해 주세요.",
				business_number: {
					required:"사업자 등록번호를 입력해 주세요",
					pattern:"사업자 등록번호 형식을 확인해 주세요<br/>예시) 123-45-67890"
				},
			},
			submitHandler: function(form) {
				form.submit();
			}
		});
	});
</script>
