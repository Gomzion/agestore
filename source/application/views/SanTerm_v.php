

	<div class="article">
		<div class="article-title">
			<h6>약관 관리</h6>
		</div>

		<div class="section-wrapper">
			<div class="section-aside">
				<div class="table-sub-caption">
					<div class="left-group">
						<button type="button" class="btn type-black size-small" id="term-add-btn">+ 약관 추가</button>
					</div>
				</div>

				<div class="table-style-a not-full" id="term-list">
					<table>
						<colgroup>
							<col width="160" />
							<col width="160" />
						</colgroup>

						<thead>
						<tr>
							<th><p>구분</p></th>
							<th><p>적용일</p></th>
						</tr>
						</thead>

						<tbody>
						<?php foreach ($terms as $ts) {?>
						<tr class="select-mode">
							<td>
								<input type="hidden" name="idx" value="<?php echo $ts->TERMS_SQ?>" />
								<p><?php if($ts->TERMS_FL == 1) echo "개인정보 취급방침"; else echo "서비스 이용약관";?></p>
							</td>
							<td><p><?php echo $ts->TERMS_APY_DA?></p></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="section-main">
				<form action="/SanTerm/updateTerm" method="post" id="term-form" target="frmTarget">
					<input type="hidden" name="term_sq" />
					<div class="table-sub-caption">
						<div class="left-group">
							<div class="dropdown-box placeholder" data-field="category">
								<input type="hidden" name="category" id="category"/>

								<button type="button" tabindex="-1" placeholder="구분 선택">구분 선택</button>

								<div class="dropdown-list">
									<ul>
										<li>
											<button type="button" data-value="1">개인정보 취급방침</button>
										</li>
										<li>
											<button type="button" data-value="2">서비스 이용약관</button>
										</li>
									</ul>
								</div>
							</div>

							<div class="input-box date-picker">
								<input type="text"
									   name="apply_date"
									   id="apply_date"
									   placeholder="적용일"
									   readonly
								/>
							</div>

							<div class="dropdown-box size-auto" data-field="status">
								<input type="hidden" name="status" id="status" value="1"/>

								<button type="button" tabindex="-1">사용</button>

								<div class="dropdown-list">
									<ul>
										<li>
											<button type="button" data-value="Y">사용</button>
										</li>
										<li>
											<button type="button" data-value="N">미사용</button>
										</li>
									</ul>
								</div>
							</div>

							<button type="button" class="btn size-small" id="term-delete-btn">삭제</button>
						</div>

						<div class="right-group">
							<input type="submit" class="btn type-primary size-small" value="저장" />
						</div>
					</div>

					<div class="editor-area">
						<textarea name="term_content"></textarea>
					</div>
				</form>
			</div>
		</div>
	</div>

	<iframe id="frmHiddenTarget" name="frmTarget" style="display:none;border:1px solid;width:800px;height:300px"></iframe>

	<script type="text/html" id="term-list-template">
		<tr class="select-mode">
			<td>
				<input type="hidden" name="idx" value="0" />
				<p><%=termName%></p>
			</td>
			<td><p></p></td>
		</tr>
	</script>

	<script>
		var G_editor = null;

		// 우측 데이터 갱신
		function getTermComplete(json) {
			//console.log(json);

			// Parse the JSON data
			var termData = JSON.parse(json);

			// Access the values in the termData object
			var termId = termData.TERMS_SQ;
			var termFl = termData.TERMS_FL;
			var termText = termData.TERMS_TX;
			var termUseFlag = termData.TERMS_USE_FL;
			var termApplyDate = termData.TERMS_APY_DA;

			$("[data-field=category] .dropdown-list button").filter("[data-value='"+termFl+"']").trigger("click");
			$("[data-field=status] .dropdown-list button").filter("[data-value='"+termUseFlag+"']").trigger("click");
			datePicker["apply_date"].setDate(new Date(termApplyDate));

			// Decode HTML entities in termText
			var decodedTermText = $("<div>").html(termText).text();

			G_editor.setData(decodedTermText);
		}

		// 좌측 데이터 갱신
		function getTermUpdate(json) {
			// Parse the JSON data
			var termData = JSON.parse(json);

			// Access the values in the termData object
			var termId = termData.TERMS_SQ;
			var termFlText = termData.TERMS_FL_NM;
			var termApplyDate = termData.TERMS_APY_DA;

			$("input[name=term_sq]").val(termId);
			$("#term-list .select-mode.current p").eq(0).text(termFlText);
			$("#term-list .select-mode.current p").eq(1).text(termApplyDate);
		}

		// 삭제 데이터 갱신
		function getTermDel(rst) {
			if(rst == 'YES') {
				const current = $("#term-list .select-mode.current");
				current.remove();

				initForm();
			} else {
				alert('삭제에 실패 했습니다. 최초의 개인정보 취급방침과 서비스 이용약관은 삭제가 불가능합니다');
			}
		}

		// 폼의 새약관이거나 이럴때 폼 초기화
		function initForm() {
			$("input[name=term_sq]").val(0);
			dropdownReset($(".dropdown-box[data-field=category]"));
			$(".dropdown-box[data-field=status] .dropdown-list button").filter("[data-value='Y']").trigger("click");
			datePicker["apply_date"].setDate();

			G_editor.setData("");
		}

		$(function() {
			const termListTemplate = _.template($("#term-list-template").html());

			datePicker["apply_date"] = customDatepicker("#apply_date", {
				onSelect:function() {
					$("#apply_date").closest(".input-box").removeClass("error-box");
				}
			});

			//let editor = null;

			ClassicEditor.create($("textarea[name=term_content]")[0], {
				...ckeditorDefaultOptions,
				simpleUpload: {
					// The URL that the images are uploaded to.
					uploadUrl: '/upload/image',

					// Enable the XMLHttpRequest.withCredentials property.
					withCredentials: true,

					// Set the maximum file size to 0 for unlimited size.
					maxFileSize: 5 * 1024 * 1024
				}
			})
				.then(newEditor => {
					G_editor = newEditor;
				});

			$("#term-add-btn").click(function() {
				// 새로 추가된거 처리
				const termNameNum = $("#term-list td p:contains('새 약관'):eq(0)").length > 0 ?
						leftPad(parseInt($("#term-list td p:contains('새 약관'):eq(0)").text().replace(/[^0-9]/g, "")) + 1) : "01",
					termName = "새 약관" + termNameNum,
					termListTemplateHtml = termListTemplate({termName});

				$("#term-list tbody").prepend(termListTemplateHtml);

				$("#term-list .select-mode").removeClass("current");
				$("#term-list .select-mode").eq(0).addClass("current");

				initForm();
			});

			$("#term-delete-btn").click(function() {
				const idx = $("input[name=term_sq]").val();
				if(idx > 0) {
					// iframe에 넘겨서 받아온다.
					// Get the reference to the iframe element
					var iframe = document.getElementById('frmHiddenTarget');

					// Set the URL of the iframe
					iframe.src = '/SanTerm/delTerm/' + idx;
				}
				// const current = $("#term-list .select-mode.current");
				// current.remove();
				//
				// initForm();
			});

			$(document).on("click", "#term-list .select-mode", function() {
				const idx = $(this).find("input[name=idx]").val();
				$("input[name=term_sq]").val(idx);

				$("#term-list .select-mode").removeClass("current");
				$(this).addClass("current");

				$("#term-form .input-box").removeClass("error-box");

				if(idx > 0) {
					// iframe에 넘겨서 받아온다.
					// Get the reference to the iframe element
					var iframe = document.getElementById('frmHiddenTarget');

					// Set the URL of the iframe
					iframe.src = '/SanTerm/getTerm/'+idx;
				} else {
					initForm();
				}
			});

			$("#term-form").validate({
				rules: {
					category: {
						required: true
					},
					apply_date: {
						required: true
					},
					status: {
						required: true,
					},
				},
				messages: {
					category : "구분을 선택해 주세요.",
					apply_date : "적용일을 입력해 주세요.",
					status : "사용 여부를 선택해 주세요.",
				},
				submitHandler: function(form) {
					G_editor.updateSourceElement();

					setTimeout(function() {
						form.submit();
					}, 100);
				}
			});
		});
	</script>
