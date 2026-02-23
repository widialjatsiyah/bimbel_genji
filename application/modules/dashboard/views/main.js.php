<script type="text/javascript">
  $(document).ready(function() {

    // Disabled right click on home image
    $(".img-home").each(function() {
      $(this)[0].oncontextmenu = function() {
        return false;
      };
    });

  });
</script>