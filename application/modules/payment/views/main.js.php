
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $client_key ?>"></script>
<script type="text/javascript">
    $('#pay-button').click(function(event) {
        event.preventDefault();
        snap.pay('<?= $snap_token ?>', {
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
</script>
