let canvas = {}, sign = {};

function resizeCanvas(){
  $(".form-sign").each(function() {
    var canvas = $(this).find("canvas")[0];

    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);    
  });
}

$(function() {
  $(".form-sign").each(function() {
    const _this = $(this),
          id = _this.attr("id");

    canvas[id] = _this.find("canvas")[0];
    sign[id] = new SignaturePad(canvas[id], {
        minWidth: 2,
        maxWidth: 2,
        penColor: "rgb(0, 0, 0)"
    });
  
    sign[id].addEventListener("beginStroke", () => {
      _this.find(".placeholder").hide();
    });

  });

  $(window).on("resize", function(){
    resizeCanvas();
  });

  resizeCanvas();
})
