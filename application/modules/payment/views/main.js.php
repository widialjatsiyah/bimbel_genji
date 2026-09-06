
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $client_key ?>"></script>
<script type="text/javascript">
    $('#pay-button').click(function(event) {
        event.preventDefault();
        snap.pay('<?= $snap_token ?>', {
            enabledPayments: ['bank_transfer'],
            onSuccess: function(result) {
                window.location.href = '<?= base_url("payment/finish?order_id=" . $order_id) ?>';
            },
            onPending: function(result) {
                window.location.href = '<?= base_url("payment/finish?order_id=" . $order_id) ?>';
            },
            onError: function(result) {
                alert("Pembayaran gagal! Silakan coba lagi.");
            }
        });
    });

    $('#manual-pay-button').click(function(event) {
        event.preventDefault();
        $('#checkout-payment-proof').val('');
        $('#checkout-payment-note').val('');
        $('#modal-checkout-manual-payment').modal('show');
    });

    $('#checkout-submit-manual').click(function(event) {
        event.preventDefault();
        var fileInput = $('#checkout-payment-proof')[0];
        if (!fileInput.files.length) {
            alert('Pilih bukti pembayaran terlebih dahulu.');
            return;
        }

        var formData = new FormData();
        formData.append('payment_proof', fileInput.files[0]);
        formData.append('manual_note', $('#checkout-payment-note').val());
        formData.append('<?= $this->security->get_csrf_token_name() ?>', '<?= $this->security->get_csrf_hash() ?>');
        var button = $(this);
        button.prop('disabled', true).text('Mengirim...');

        $.ajax({
            url: '<?= base_url('payment/upload_manual_proof/') ?><?= $order_id ?>',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                alert(response.data);
                if (response.status) {
                    $('#modal-checkout-manual-payment').modal('hide');
                    window.location.href = '<?= base_url('my_payment') ?>';
                }
            },
            error: function() {
                alert('Gagal mengirim bukti pembayaran.');
            },
            complete: function() {
                button.prop('disabled', false).text('Kirim Bukti');
            }
        });
    });
</script>
