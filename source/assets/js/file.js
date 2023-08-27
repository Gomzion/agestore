class FileList {
  constructor(...items) {
    // flatten rest parameter
    items = [].concat(...items);
    // check if every element of array is an instance of `File`
    if (items.length && !items.every(file => file instanceof File)) {
      throw new TypeError("expected argument to FileList is File or array of File objects");
    }
    // use `ClipboardEvent("").clipboardData` for Firefox, which returns `null` at Chromium
    // we just need the `DataTransfer` instance referenced by `.clipboardData`
    const dt = new ClipboardEvent("").clipboardData || new DataTransfer();
    // add `File` objects to `DataTransfer` `.items`
    for (let file of items) {
      dt.items.add(file)
    }
    return dt.files;
  }
}

$(function() {
  // 공통 폼 파일 이벤트
  $(document).on("change", ".file-box input[type=file]", function(e) {
    let parentBox = $(this).closest(".file-box"),
        inputName = $(this).attr("name"),
        files = e.target.files;

    if (files) {
      if (parentBox.find("ul").length === 0) {
        parentBox.append("<ul>");
      }

      for (let file of files) {
        if (parentBox.hasClass("multi")) {
          let listElement = `
              <p>${file.name}</p>
              <button type="button" class="delete-btn">파일 삭제</button>
          `;

          listElement = $("<li />", {html:listElement});

          let fileInput = document.createElement("input");

          fileInput.type = "file";
          fileInput.name = `${inputName}s[]`;
          fileInput.files = new FileList(file);
          
          listElement.prepend(fileInput);

          parentBox.find("ul").append(listElement);
        } else {
          parentBox.find(".input-delete-btn").remove();
          parentBox.find(".input-file").append(inputDeleteBtn);
          parentBox.find(".input-file p").removeClass("placeholder").text(file.name);
        }
      }

      if (parentBox.hasClass("multi")) {
        $(this).val("");
      } else {
        if ($(this).is('[required]')) {
          parentBox.removeClass("error-box").addClass("valid-box");
          parentBox.closest(".input-row").removeClass("error-row").addClass("valid-row");
        }
        
        $(this).trigger("changed", files);
      }
    }
  });  

  $(document).on("click", ".file-box .input-delete-btn", function() {
    let parentBox = $(this).closest(".file-box"),
        input = parentBox.find("input[type=file]");

    parentBox.find(".input-delete-btn").remove();
    parentBox.find(".input-file p").addClass("placeholder").text(parentBox.find(".input-file p").attr("placeholder"));

    if (input.is('[required]')) {
      parentBox.addClass("error-box").removeClass("valid-box");
      parentBox.closest(".input-row").addClass("error-row").removeClass("valid-row");
    }
    input.val("").trigger("deleted");
  });

  $(document).on("click", ".file-box .delete-btn", function() {
    let parentBox = $(this).closest(".file-box"),
        parentItem = $(this).closest("li"),
        fileList = parentBox.find("ul");

    parentItem.remove();

    if (fileList.children().length === 0) {
      fileList.empty();
    }      
  });
})