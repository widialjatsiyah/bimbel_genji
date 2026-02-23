<script type="text/javascript">
    $(document).ready(function() {

        $('#btn-save-setting').click(function() {
            var formData = new FormData($('#form-setting')[0]);
            $.ajax({
                url: '<?= base_url('settings_home/save') ?>',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
					var response = JSON.parse(response);
					// console.log(response);
                    if (response.status) {
                        notify(response.data, 'success');
                    } else {
                        notify(response.data, 'danger');
                    }

					setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        });

    });
</script>
