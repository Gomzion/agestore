$(function() {
  $(document).on("click", "#onekey-search", function(e) {
    e.stopPropagation();

    const form = $(this).closest("form"),
          box = form.find(".input-box.agency, .dropdown-box.agency"),
          state = form.find("input[name=state]"),
          region = form.find("input[name=region]"),
          agency = form.find("input[name=agency]");

    if (state.val() === "") {
      alert("시/도를 선택해 주세요.");
      if (!state.closest(".dropdown-box").hasClass("open")) state.next("button").trigger("click");
      return;
    }

    if (region.val() === "") {
      alert("시/구/군을 선택해 주세요.");
      if (!region.closest(".dropdown-box").hasClass("open")) region.next("button").trigger("click");
      return;
    }

    if (agency.val() === "") {
      alert("소속 기관 검색어를 입력해 주세요.");
      agency.focus();
      return;
    }

    $.ajax({
      //url:"/dummy/onekey.php",
	  url:"/Login/orch",
      data: {
        state: state.val(),
        region: region.val(),
        keyword: agency.val()
      },
      type:"get",
      dataType:"json",
      success:function(response){
        let listHtml = '';

        if (response.list.length > 0) {
          for (let i = 0; i < response.list.length; i++) {
            listHtml += `
              <li>
                <button type="button" id="${response.list[i].code}" cd="${response.list[i].corp_cd}">${response.list[i].value}</button>
              </li>
            `;
          }

          box.find(".agency-list ul").html(listHtml);

          const list = box.find(".agency-list li"),
                regex = new RegExp(agency.val(), "gi");

          list.each(function() {
            const $text = $(this).find("button"),
                  match = $text.text().match(regex) !== null;

            if (match) {
              $text.html($text.text().replace(regex, "<span>$&</span>"));
            }
          })
        } else {
          box.find(".agency-list ul").html("<li class='empty'>검색 결과가 없습니다.</li>");
        }

        agency.focus();
        box.find(".agency-list").slideDown();
        box.addClass("open");
      }
    });
  });

  $(document).on("click", ".agency-list button", function() {
    const box = $(this).closest(".input-box");

    $(".agency").closest(".input-row").removeClass("error-row").addClass("valid-row");
    box.find("input[name=agency]").val($(this).text());
	box.find("input[name=hospital]").val(this.getAttribute('id'));
	$("input[name=business_number]").val(this.getAttribute('cd'));

    box.find(".agency-list").slideUp();
    box.removeClass("open");
  });

  $(window).keyup(function(e) {
    if (e.key === "Escape") {
      if ($(".agency.open:not(.dropdown-box)").length > 0) {
        e.stopImmediatePropagation();

        $(".agency.open:not(.dropdown-box) .agency-list").slideUp();
        $(".agency.open:not(.dropdown-box)").removeClass("open");
      }
    }
  });

  $(document).click(function(e) {
    if (!$(e.target).hasClass(".agency-list") && $(e.target).closest(".agency-list").length == 0) {
      $(".agency-list").slideUp();
      $(".agency.open:not(.dropdown-box)").removeClass("open");
    }
  });
})
