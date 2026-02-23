<script type="text/javascript">
  $(document).ready(function() {

    var _form = "form-permintaan";

    // Handle ajax start
    $(document).ajaxStart(function() {
      $(".spinner-action-button").attr("disabled", true);
    });

    // Handle ajax stop
    $(document).ajaxStop(function() {
      $(".spinner-action-button").removeAttr("disabled");
    });

    // Handle unit change
    $(`#${_form} .permintaan-unit`).on("change", function() {
      var unit = $(this).val();

      $.ajax({
        type: "POST",
        url: "<?= base_url('form/ajax_get_sub_unit/') ?>",
        data: {
          "<?= $this->security->get_csrf_token_name(); ?>": "<?= $this->security->get_csrf_hash(); ?>",
          unit: unit
        },
        dataType: "json",
        success: function(response) {
          var subUnit = $(`#${_form} .permintaan-sub_unit`);
          var options = "";

          if (response.length > 0) {
            $.each(response, function(index, item) {
              options += `<option value="${item.nama_sub_unit}">${item.nama_sub_unit}</option>`;
            });
          };

          subUnit.html(options);
        }
      });
    });

    // Handle data submit
    $("#" + _form + " .page-action-permintaan").on("click", function(e) {
      e.preventDefault();

      var form = $("#" + _form)[0];
      var data = new FormData(form);

      $.ajax({
        type: "post",
        url: "<?php echo base_url('form/ajax_submit/') ?>",
        data: data,
        dataType: "json",
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
          if (response.status === true) {
            $("#" + _form).trigger("reset");
            $("#" + _form + " .permintaan-form-wrapper").hide();
            $("#" + _form + " .permintaan-message").show();
            $("#" + _form + " .permintaan-message-email").html(response.email);
          } else {
            notify(response.data, "danger");
          };
        }
      });
      return false;
    });

  });
</script>