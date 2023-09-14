
	<div class="find-box">
		<div class="inner">
			<div class="title-box">
				<h5>아이디 확인</h5>
			</div>

			<!-- 아이디 검색 결과가 있을때 -->
			<?php if(is_object($user_id)) {?>

			<div class="form-box">
				<p class="desc">안녕하세요. <?=$user_name?> 선생님<br/>아래에서 가입하신 이메일 주소를 확인해 주세요.</p>

				<div class="result-box">
					<p><?=$user_id->USER_ID?></p>
				</div>

				<div class="btn-row-group">
					<div class="btn-row">
						<a href="/" class="btn type-black size-big">로그인 페이지로 이동</a>
					</div>

					<div class="btn-row">
						<a href="/Common/InitPW" class="btn type-black-line size-big">비밀번호 초기화</a>
					</div>
				</div>
			</div>

			<?php } else { ?>

			<!-- 아이디 검색 결과가 없을때 -->

			<div class="form-box">
			  <p class="desc">안녕하세요. <?=$user_name?> 선생님<br/><strong>현재 등록된 정보가 없습니다.</strong><br/>관리자에게 회원 등록을 요청해 주세요.</p>

			  <div class="btn-row-group">
				<div class="btn-row">
				  <a href="/" class="btn type-black-line size-big">로그인 페이지로 이동</a>
				</div>
			  </div>
			</div>

			<?php } ?>
		</div>
	</div>
