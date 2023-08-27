var good_id = "";

const defaultModal = {
  mode:"box",
  scroll:true,
  containerStyle: {
    "width":"100%",
  },
  addClass:[]
}

const modalMatch = {
  "login-popup" : {
    ...defaultModal,
    url: "/Modal/PopupLogin",
    containerStyle: {
      "width":"100%",
      "max-width":"600px"
    }
  },
  "term-service" : {
    ...defaultModal,
    url: "/modal/terms_service",
    containerStyle: {
      "width":"100%",
      "max-width":"600px"
    }
  },
  "term-privacy" : {
    ...defaultModal,
    url: "/modal/terms_privacy",
    containerStyle: {
      "width":"100%",
      "max-width":"600px"
    }
  },
  "term-dlvy" : {
    ...defaultModal,
    url: "/components/modals/term/dlvy.php",
    containerStyle: {
      "width":"100%",
      "max-width":"600px"
    }
  },
  "cart" : {
    ...defaultModal,
    url: "/Shop/Cart",
    containerStyle: {
      "width":"100%",
      "max-width":"420px"
    },
    data: function(target) {
      return {
        good_id: $(target).closest("li").data("idx")
      }
    }
  },
  "onekey" : {
    ...defaultModal,
    url: "/My/ChangeOrgan",
    containerStyle: {
      "width":"100%",
      "max-width":"500px"
    },
  },
}

let defaultIndex = 3000;

function modalShow(target, index) {
  //console.log(target);
  const data = isElement(target) ? $(target).data() : target;
  const modalOption = modalMatch[data["modal"]];
  const modalFrame = `
    <div class="modal `+modalOption.mode + ` ` + modalOption.addClass.join(" ") +`" id="`+data["modal"]+`">` + 
      (modalOption.scroll ? '<div class="modal-scroll"><div class="modal-container"></div></div>' : '<div class="modal-container"></div>')
    + `</div>`;

  let ajaxOption = {
    url:modalOption.url,
    data: {
      ...data,
      ...(modalOption.data ? modalOption.data(target) : null)
    }
  }

  $.ajax({
    ...ajaxOption,
    type:"get",
    dataType:"html",
    success:function(html){
      const 
      $modal = $(modalFrame),
      $modalContainer = $modal.find(".modal-container").css(modalOption.containerStyle).append(html);

      $("body").append($modal);

      setTimeout(function() {
        $("body").addClass("no-scroll");

        $modal.css({
          zIndex:index ? index : 2000
        }).addClass("open");
        $modal.trigger("modalShow", [$modalContainer]);
      }, 100);
    }	
  });
}

function modalHide(target) {
  var targetModal = target.hasClass("modal") ? target : target.closest(".modal");

  targetModal.addClass("open-out");

  setTimeout(function() {
    $("body").removeClass("no-scroll");
    targetModal.removeClass("open open-out");
    targetModal.remove();
  }, 300);
}

$(function() {
  // ESC 입력시 열린 모달창 닫기 이벤트
  $(window).keyup(function(event) {
    if (event.key === "Escape") {
      if ($(".modal.open").length == 0) {
        return;
      }

      var modalArray = [];

      $(".modal.open").each(function(index, element) {
        modalArray.push(element);
      });

      modalArray.sort(function(a, b) {
        var aIndex = getComputedStyle(a).zIndex;
        var bIndex = getComputedStyle(b).zIndex;

        return bIndex - aIndex
      });

      modalHide($(modalArray[0]));
    }
  });

  $(document).on("click", ".modal-btn", function(e) {
    e.stopPropagation();

	// 상품ID 받아옴
	good_id = $(this).closest('li').data('idx');

    let opendModalLength = $(".modal.open").length,
        currentIndex = defaultIndex + opendModalLength;

    modalShow($(this)[0], currentIndex);
  });

  $(document).on("click", ".modal", function(e) {
    if ($(e.target).hasClass(".modal-container") || $(e.target).closest(".modal-container").length > 0) {
      return;
    }

    modalHide($(this));
  });

  $(document).on("click", ".modal .close-btn", function(e) {
    modalHide($(this));
  });
});
