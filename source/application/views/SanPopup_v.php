

	<div class="article">
		<form action="/SanPopup/updatePopup" method="post" id="popup-form" target="frmTarget">
			<div class="article-title">
				<h6>팝업 관리</h6>
			</div>

			<div class="section-box">
				<div class="section-title">
					<h6>로그인 팝업</h6>

					<div class="right-group">
						<div class="radio-box">
							<div class="radio-item">
								<label>
									<input type="radio" name="login_popup" value="Y" <?php if($popup['L']->POPUP_USE_FL == 'Y') echo "checked";?>/>
									<p>사용</p>
								</label>
							</div>

							<div class="radio-item">
								<label>
									<input type="radio" name="login_popup" value="N" <?php if($popup['L']->POPUP_USE_FL == 'N') echo "checked";?>/>
									<p>미사용</p>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="section-content">
					<textarea name="login_popup_content">
						<?php echo $popup['L']->POPUP_TX; ?>
					</textarea>
				</div>
			</div>

			<div class="section-box">
				<div class="section-title">
					<h6>메인 팝업</h6>

					<div class="right-group">
						<div class="radio-box">
							<div class="radio-item">
								<label>
									<input type="radio" name="main_popup" value="Y" <?php if($popup['M']->POPUP_USE_FL == 'Y') echo "checked";?>/>
									<p>사용</p>
								</label>
							</div>

							<div class="radio-item">
								<label>
									<input type="radio" name="main_popup" value="N"  <?php if($popup['M']->POPUP_USE_FL == 'N') echo "checked";?>/>
									<p>미사용</p>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="section-content">
					<textarea name="main_popup_content">
						<?php echo $popup['M']->POPUP_TX; ?>
					</textarea>
				</div>
			</div>

			<div class="btn-row-group">
				<div class="btn-row">
					<input type="submit" class="btn type-primary size-big" value="저장" />
				</div>
			</div>
		</form>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script>
		$(function() {
			let loginEditor = null, mainEditor = null;

			ClassicEditor.create($("textarea[name=login_popup_content]")[0], {
				...ckeditorDefaultOptions,
				simpleUpload: {
					// The URL that the images are uploaded to.
					uploadUrl: '/upload/image',

					// Enable the XMLHttpRequest.withCredentials property.
					withCredentials: true,

					// Set the maximum file size to 0 for unlimited size.
					maxFileSize: 5 * 1024 * 1024
				}
			}).then(newEditor => {
				loginEditor = newEditor;
			});

			ClassicEditor.create($("textarea[name=main_popup_content]")[0], {
				...ckeditorDefaultOptions,
				simpleUpload: {
					// The URL that the images are uploaded to.
					uploadUrl: '/upload/image',

					// Enable the XMLHttpRequest.withCredentials property.
					withCredentials: true,

					// Set the maximum file size to 0 for unlimited size.
					maxFileSize: 5 * 1024 * 1024
				}
			}).then(newEditor => {
				mainEditor = newEditor;
			});

			$("#popup-form").validate({
				submitHandler: function(form) {
					loginEditor.updateSourceElement();
					mainEditor.updateSourceElement();

					setTimeout(function() {
						form.submit();
					}, 100);
				}
			});
		});
	</script>
