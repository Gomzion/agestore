const multiSearch = `
<div class="list-search">
  <div class="input-box search">
    <input type="text" placeholder="검색어를 입력해 주세요." />
  </div>
</div>
`;

function dropdownHide(selector) {
  selector.find(".dropdown-list").slideUp();
  selector.addClass("open-out");

  setTimeout(function() {
    selector.removeClass("open open-out");
  }, 300);
};

function dropdownReset(selector) {
  let delay = 400;

  if (!selector.hasClass("open")) {
    delay = 0;
  }

  if (delay != 0) {
    selector.find(".dropdown-list").slideUp();
    selector.addClass("open-out");

    setTimeout(function() {
      selector.removeClass("open open-out");
    }, delay);
  }

  setTimeout(function() {
    selector.addClass("placeholder");
    selector.find("> input").val("");
    selector.find("> button").text(selector.find("> button").attr("placeholder"));

    if (selector.hasClass("multi")) {
      selector.find(".list-search input").val("");
      selector.find(".list-search .input-delete-btn").remove();
      selector.find(".dropdown-list li").show();
      selector.find(".dropdown-list li.empty").remove();
      selector.find(".dropdown-list li p").each(function() {
        $(this).text($(this).text());
      })
    } else {
      selector.find(".dropdown-list button").removeClass("selected");
    }
  }, delay);
};

function initDropdownMulti(selector) {
  selector.find(".dropdown-list li").each(function() {
    const btn = $(this).find("button");
    
    let checkBoxHtml = `
      <label>
        <input type="checkbox"
          value="${btn.data("value")}" 
        />
        <p>${btn.text()}</p>
      </label>
    `;

    $(this).html(checkBoxHtml);
  });

  selector.data("init", "true");
}

function dropdownRender(selector, list) {
  selector.addClass("placeholder");
  selector.removeClass("open error-row");
  selector.find("> button").text(selector.find("> button").attr("placeholder"));
  selector.find("> input").val("");
  selector.find(".dropdown-list li").remove();
  selector.find(".dropdown-list").hide();

  let listHtml = "";

  if (selector.hasClass("multi")) {
    selector.find(".list-search input").val("");

    for (var i = 0; i < list.length; i++) {
      listHtml += `
      <li>
        <label>
          <input type="checkbox"
            value="${list[i].value ? list[i].value : "list[i].value"}" 
          />
          <p>${list[i].label ? list[i].label : "list[i].label"}</p>
        </label>
      </li>
      `;
    }
  } else {
    for (var i = 0; i < list.length; i++) {
      listHtml += `
      <li>
        <button type="button" data-value="${list[i].value ? list[i].value : "list[i].value"}">${list[i].label ? list[i].label : "list[i].label"}</button>
      </li>
      `;
    }
  }

  selector.find(".dropdown-list ul").html(listHtml);
}

$(function() {
  $(document).on("click", ".dropdown-box > button", function(e) {
    const box = $(this).closest(".dropdown-box"),
          list = box.find(".dropdown-list"),
          _multiSearch = box.find(".list-search").length > 0 ? box.find(".list-search") : multiSearch;

    if ($(".dropdown-box.open").not(box).length > 0) {
      dropdownHide($(".dropdown-box.open").not(box));
    }

    if (box.hasClass("multi")) {
      if (box.data("init") != "true") {
        initDropdownMulti(box);
      }

      if (box.attr("direction") == "top") {
        list.find("ul").after(_multiSearch);
      } else {
        list.find("ul").before(_multiSearch);
      }
    }

    if (box.hasClass("open")) {
      dropdownHide(box);
    } else {
      list.slideDown();
      box.addClass("open open-in");

      setTimeout(function() {
        box.removeClass("open-in");
      }, 300);
    }
  });

  $(document).on("click", ".dropdown-list button", function(e) {
    const box = $(this).closest(".dropdown-box"),
          list = box.find(".dropdown-list"),
          label = $(this).html(),
          value = $(this).attr("data-value");

    if (box.hasClass("placeholder")) box.removeClass("placeholder");

    box.find("> input").val(value).trigger("change");
    box.find("> button").html(label);

    list.find("button").removeClass("selected");
    $(this).addClass("selected");

    list.slideUp();
    box.removeClass("error-box").addClass("open-out");

    setTimeout(function() {
      box.removeClass("open open-out");
    }, 300);
  });

  $(document).on("change", ".dropdown-list input[type=checkbox]", function() {
    const box = $(this).closest(".dropdown-box"),
          checkValues = [];

    box.find("input[type=checkbox]:checked").each(function() {
      checkValues.push({
        label:$(this).next("p").text(),
        value:$(this).val()
      });
    });
    
    if (checkValues.length === 0) {
      box.addClass("placeholder");
      box.find("> button").text(box.find("> button").attr("placeholder"));
    } else {
      box.removeClass("error-row placeholder");
      box.find("> button").text(checkValues.map(item => item.label).join(", "));
    }

    box.find("> input").val(checkValues.map(item => item.value).join(",")).trigger("change");
  });

  $(document).on("keydown", ".dropdown-box .list-search input", function(e) {
    if (e.keyCode === 13) {
      e.preventDefault();
    }
  });

  $(document).on("keyup", ".dropdown-box .list-search input", function(e) {
    const box = $(this).closest(".dropdown-box"),
          list = box.find(".dropdown-list li"),
          keyword = $(this).val(),
          regex = new RegExp(keyword, "gi");

    list.not(".empty").each(function() {
      const $text = $(this).find("p"),
            match = $text.text().match(regex) !== null;

      if (match) {
        $text.html($text.text().replace(regex, "<span>$&</span>"));

        $(this).show();
      } else {
        $(this).hide();
      } 
    })

    if (list.not(".empty").filter(":visible").length == 0) {
      if (list.filter(".empty").length == 0) {
        box.find(".dropdown-list ul").append("<li class='empty'>검색 결과가 없습니다.</li>");
      }
    } else {
      list.filter(".empty").remove();
    } 
  });

  $(window).keyup(function(e) {
    if (e.key === "Escape") {
      if ($(".dropdown-box.open").length > 0) {
        e.stopImmediatePropagation();

        dropdownHide($(".dropdown-box.open"));
      }
    }
  });

  $(document).click(function(e) {
    if (!$(e.target).is(".dropdown-box.open") && $(e.target).closest(".dropdown-box.open").length == 0) {
      dropdownHide($(".dropdown-box.open"));
    }
  });
});
