(function ($) {
  $.extend({
      uploadPreview: function (options) {

        // Options + Defaults
        var settings = $.extend({
          input_field: ".image-input",
          preview_box: ".image-preview",
          label_field: ".image-label",
          label_default: "Choose File",
          label_selected: "Change File",
          no_label: false,
          success_callback: null,
        }, options);

        // Check if FileReader is available
        if (window.File && window.FileList && window.FileReader) {
          for (let i = 0; i < settings.input_field.length; i++) {
            if (typeof ($(settings.input_field[i])) !== 'undefined' && $(settings.input_field[i]) !== null) {
              $(settings.input_field[i]).change(function () {
                var files = this.files;

                if (files.length > 0) {
                  var file = files[0];
                  var reader = new FileReader();

                  // Load file
                  reader.addEventListener("load", function (event) {
                    var loadedFile = event.target;

                    // Check format
                    if (file.type.match('image')) {
                      // Image
                      $(settings.preview_box[i]).css("background-image", "url(" + loadedFile.result + ")");
                      $(settings.preview_box[i]).css("background-size", "cover");
                      $(settings.preview_box[i]).css("background-position", "center center");
                    } else if (file.type.match('audio')) {
                      // Audio
                      $(settings.preview_box[i]).html("<audio controls><source src='" + loadedFile.result + "' type='" + file.type + "' />Your browser does not support the audio element.</audio>");
                    } else {
                      alert("This file type is not supported yet.");
                    }
                  });

                  if (settings.no_label == false) {
                    // Change label
                    $(settings.label_field).html(settings.label_selected);
                  }

                  // Read the file
                  reader.readAsDataURL(file);

                  // Success callback function call
                  if (settings.success_callback) {
                    settings.success_callback();
                  }
                } else {
                  if (settings.no_label == false) {
                    // Change label
                    $(settings.label_field).html(settings.label_default);
                  }

                  // Clear background
                  $(settings.preview_box[i]).css("background-image", "none");

                  // Remove Audio
                  $(settings.preview_box[i] + " audio").remove();
                }
              });
            }
          }
        } else {
          alert("You need a browser with file reader support, to use this form properly.");
          return false;
        }
      }
    }
  )
})
(jQuery);
