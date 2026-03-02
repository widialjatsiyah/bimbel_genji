<script type="text/javascript">
  $(document).ready(function() {

    var _form = "form-register";

    // Handle ajax start
    $(document).ajaxStart(function() {
      $(".spinner").css("display", "flex");
    });

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(".spinner").hide();
    });

    // Handle data submit
    $("#" + _form + " .page-action-register").on("click", function(e) {
      e.preventDefault();

      var form = $("#" + _form)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('register/ajax_submit/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            notify(response.data, "success");
            window.location.href = "<?php echo base_url('select_package') ?>";
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

  });
</script>
