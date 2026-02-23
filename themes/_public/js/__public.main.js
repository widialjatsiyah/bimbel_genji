$(document).ajaxStart(function () {
  $(".spinner").css("display", "flex");
  $(".spinner-action").css("display", "inline-block");
  $(".spinner-action-button").attr("disabled", true);
});

$(document).ajaxStop(function () {
  $(".spinner").css("display", "none");
  $(".spinner-action").css("display", "none");
  $(".spinner-action-button").removeAttr("disabled");
});

$(document).ajaxError(function (event, request, settings) {
  $(".spinner").css("display", "none");
  $(".spinner-action").css("display", "none");
  $(".spinner-action-button").removeAttr("disabled");

  swal({
    title: "Error " + request.status,
    text: "An error occurred while processing the request.",
    type: "error",
    showCancelButton: false,
    confirmButtonColor: '#39bbb0',
    confirmButtonText: "OK",
    closeOnConfirm: false
  });
});

// Initialize select2

// Initialize select2
if ($("select.select2-partial")[0]) {
	var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
	if ($("select.select2-partial").hasClass("select2-hidden-accessible")) {
		$("select.select2-partial").select2("destroy");
	};
	$("select.select2-partial").select2({
		dropdownAutoWidth: !0,
		width: "100%",
		dropdownParent: a,
	});
}
if ($("select.select2-partial-desc")[0]) {
	var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
	$("select.select2-partial-desc")
		.select2({
			dropdownAutoWidth: !0,
			width: "100%",
			dropdownParent: a,
			templateResult: function (opt) {
				var optdesc = $(opt.element).attr("data-desc");
				var $opt = $("<div>" + optdesc + "</div>");
				return $opt;
			},
		})
		.on("select2:open", function (e) {
			var search_placeholder = $(this).attr("search-placeholder");
			$(".select2-search__field").attr("placeholder", search_placeholder);
		});
}

if ($(".textarea-autosize")[0] && autosize($(".textarea-autosize")), $("input-mask")[0] && $(".input-mask").mask(), $("select.select2")[0]) {
  var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
  $("select.select2").select2({
    dropdownAutoWidth: !0,
    minimumResultsForSearch: 0, // atau angka kecil lain
    width: "100%",
    dropdownParent: a
  })
}
if ($(".textarea-autosize")[0] && autosize($(".textarea-autosize")), $("input-mask")[0] && $(".input-mask").mask(), $("select.select2-desc")[0]) {
  var a = $(".select2-parent")[0] ? $(".select2-parent") : $("body");
  $("select.select2-desc").select2({
    minimumResultsForSearch: 0,
    dropdownAutoWidth: !0,
    width: "100%",
    dropdownParent: a,
    templateResult: function (opt) {
      var optdesc = $(opt.element).attr('data-desc');
      var $opt = $(
        '<div>' + optdesc + '</div>'
      );
      return $opt;
    }
  }).on('select2:open', function (e) {
    var search_placeholder = $(this).attr('search-placeholder');
    $('.select2-search__field').attr('placeholder', search_placeholder);
  })
}

function notify(nMessage, nType) {
  $.notify({ message: nMessage }, {
    type: nType,
    z_index: 9999,
    delay: 3500,
    timer: 500,
    placement: {
      from: "top",
      align: "center"
    },
    template: '<div data-notify="container" class="alert alert-dismissible alert-{0} alert--notify" role="alert">' +
      '<span data-notify="message">{2}</span>' +
      '<button type="button" aria-hidden="true" data-notify="dismiss" class="alert--notify__close">Close</button>' +
      '</div>'
  });
};

function readUploadURL(input) {
  $('.upload .upload-preview').html("");

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      if (e.target.result != "") {
        var imageSource = "<img src='" + e.target.result + "'/>";
        $('.upload .upload-preview').html(imageSource);
      };
    };
    reader.readAsDataURL(input.files[0]);
  };
};

function readUploadMultipleURL(input) {
  var key = $(input).attr("data-preview");
  $('.upload .preview-' + key).html("");

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      if (e.target.result != "") {
        var imageSource = "<img src='" + e.target.result + "'/>";
        $('.upload .preview-' + key).html(imageSource);
      };
    };
    reader.readAsDataURL(input.files[0]);
  };
};

function readUploadInlineURL(input) {
  $('.upload-inline .upload-preview').html("");

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      if (e.target.result != "") {
        var imageSource = "<img src='" + e.target.result + "'/>";
        $('.upload-inline .upload-preview').html(imageSource);
      };
    };
    reader.readAsDataURL(input.files[0]);
  };
};

function readUploadInlineDocURL(input) {
  $('.upload-inline .upload-preview').html("No file chosen");

  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileInfo = file.name + "<br />" + formatBytes(file.size);
    $('.upload-inline .upload-preview').html(fileInfo);
  };
};

function readUploadInlineDocURLXs(input) {
  $('.upload-inline-xs .upload-preview').html("No file chosen");

  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileInfo = file.name + "<br />" + formatBytes(file.size);
    $('.upload-inline-xs .upload-preview').html(fileInfo);
  };
};

function readUploadMultipleDocURLXs(input) {
  var key = $(input).attr("data-preview");

  $('.upload-inline-xs .data-preview-' + key).html("No file chosen");

  if (input.files && input.files[0]) {
    var file = input.files[0];
    var fileInfo = file.name + "<br />" + formatBytes(file.size);
    $('.upload-inline-xs .data-preview-' + key).html(fileInfo);
  };
};

// Initialize TinyMce
tinymce.init({
  selector: 'textarea.tinymce-init',
  plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table tc',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl',
  // autosave_ask_before_unload: true,
  autosave_interval: "30s",
  autosave_prefix: "{path}{query}-{id}-",
  autosave_restore_when_empty: false,
  autosave_retention: "2m",
  image_advtab: true,
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css'
  ],
  importcss_append: true,
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: ($(".tinymce-init").attr("data-height") !== undefined) ? parseInt($(".tinymce-init").attr("data-height")) : 600,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: "mceNonEditable",
  toolbar_drawer: 'sliding',
  contextmenu: "link image imagetools table",
});

// Intialize MaskInput
$('.mask-money').mask('#,##0', { reverse: true });
$('.mask-email').mask("A", {
  translation: {
    "A": { pattern: /[\w@\.]/, recursive: true }
  }
});
$('.mask-age').mask('00', { reverse: true });
$('.mask-number').mask("#0", { reverse: true });
$('.mask-slug').mask("A", {
  translation: {
    "A": { pattern: /[\w-\/]/, recursive: true }
  }
});
$('.mask-date').mask("9999-99-99", { placeholder: 'YYYY-MM-DD' });
$('.mask-date-id').mask("99-99-9999", { placeholder: 'DD-MM-YYYY' });

// Initialize Flatpickr
$(".flatpickr-date").flatpickr({
  allowInput: true
});
$(".flatpickr-date-id").flatpickr({
  allowInput: true,
  dateFormat: "d-m-Y",
});
$(".flatpickr-datemax-today").flatpickr({
  allowInput: true,
  dateFormat: "Y-m-d",
  maxDate: "today"
});
$(".flatpickr-datemax-today-id").flatpickr({
  allowInput: true,
  dateFormat: "d-m-Y",
  maxDate: "today"
});
$(".flatpickr-datetime").flatpickr({
  enableTime: true,
  dateFormat: "Y-m-d H:i",
});
$(".flatpickr-datetime-id").flatpickr({
  enableTime: true,
  dateFormat: "d-m-Y H:i",
});
$(".flatpickr-datetime-24").flatpickr({
  enableTime: true,
  dateFormat: "Y-m-d H:i:S",
  time_24hr: true,
});
$(".flatpickr-datetime-24-id").flatpickr({
  enableTime: true,
  dateFormat: "d-m-Y H:i:S",
  time_24hr: true,
});
$(".flatpickr-timeonly").flatpickr({
  enableTime: true,
  noCalendar: true,
  dateFormat: "H:i:S",
  time_24hr: true,
});

// Initialize Select2

// File size format
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';

  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

  const i = Math.floor(Math.log(bytes) / Math.log(k));

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Handle visibility password event
$(".visibility-password").html('<i class="zmdi zmdi-eye"></i>').css("width", "30px");
$(".visibility-password").on("click", function () {
  var obj = $(this);
  var input = obj.attr("data-input");

  if (obj.html() === '<i class="zmdi zmdi-eye"></i>') {
    $(input).prop("type", "text");
    obj.html('<i class="zmdi zmdi-eye-off"></i>');
  } else {
    $(input).prop("type", "password");
    obj.html('<i class="zmdi zmdi-eye"></i>');
  };
});

// Get current data
function getCurrentDate() {
  var d = new Date();

  var month = d.getMonth() + 1;
  var day = d.getDate();

  var output = d.getFullYear() + '-' +
    (month < 10 ? '0' : '') + month + '-' +
    (day < 10 ? '0' : '') + day;

  return output;
};

// Handle input number max-length
$(document).on("keypress", "input[type='number']", function () {
  var maxLength = $(this).attr("maxlength");

  if (typeof maxLength !== "undefined") {
    if ($(this).val().length == maxLength) return false;
  };

  return true;
});

// Initialize responsive tabs
$('.nav-responsive:first').responsiveTabs();
$('.nav-responsive-2').responsiveTabs();
$('.nav-responsive-3:first').responsiveTabs();
$('.nav-responsive-4:first').responsiveTabs();
$('.nav-responsive-5:first').responsiveTabs();
$('.nav-responsive-6:first').responsiveTabs();
$('.nav-responsive-7:first').responsiveTabs();
$('.nav-responsive-8:first').responsiveTabs();
$('.nav-responsive-9:first').responsiveTabs();
$('.nav-responsive-10:first').responsiveTabs();


// Initailize timeline
(function () {
  // Vertical Timeline - by CodyHouse.co
  function VerticalTimeline(element) {
    this.element = element;
    this.blocks = this.element.getElementsByClassName("cd-timeline__block");
    this.images = this.element.getElementsByClassName("cd-timeline__img");
    this.contents = this.element.getElementsByClassName("cd-timeline__content");
    this.offset = 0.8;
    this.hideBlocks();
  };

  VerticalTimeline.prototype.hideBlocks = function () {
    if (!"classList" in document.documentElement) {
      return; // no animation on older browsers
    }
    //hide timeline blocks which are outside the viewport
    var self = this;
    for (var i = 0; i < this.blocks.length; i++) {
      (function (i) {
        if (self.blocks[i].getBoundingClientRect().top > window.innerHeight * self.offset) {
          self.images[i].classList.add("cd-timeline__img--hidden");
          self.contents[i].classList.add("cd-timeline__content--hidden");
        }
      })(i);
    }
  };

  VerticalTimeline.prototype.showBlocks = function () {
    if (! "classList" in document.documentElement) {
      return;
    }
    var self = this;
    for (var i = 0; i < this.blocks.length; i++) {
      (function (i) {
        if (self.contents[i].classList.contains("cd-timeline__content--hidden") && self.blocks[i].getBoundingClientRect().top <= window.innerHeight * self.offset) {
          // add bounce-in animation
          self.images[i].classList.add("cd-timeline__img--bounce-in");
          self.contents[i].classList.add("cd-timeline__content--bounce-in");
          self.images[i].classList.remove("cd-timeline__img--hidden");
          self.contents[i].classList.remove("cd-timeline__content--hidden");
        }
      })(i);
    }
  };

  var verticalTimelines = document.getElementsByClassName("js-cd-timeline"),
    verticalTimelinesArray = [],
    scrolling = false;
  if (verticalTimelines.length > 0) {
    for (var i = 0; i < verticalTimelines.length; i++) {
      (function (i) {
        verticalTimelinesArray.push(new VerticalTimeline(verticalTimelines[i]));
      })(i);
    }

    //show timeline blocks on scrolling
    window.addEventListener("scroll", function (event) {
      if (!scrolling) {
        scrolling = true;
        (!window.requestAnimationFrame) ? setTimeout(checkTimelineScroll, 250) : window.requestAnimationFrame(checkTimelineScroll);
      }
    });
  }

  function checkTimelineScroll() {
    verticalTimelinesArray.forEach(function (timeline) {
      timeline.showBlocks();
    });
    scrolling = false;
  };
})();