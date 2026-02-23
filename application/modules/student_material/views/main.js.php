<script>
    $(document).ready(function() {
        // Inisialisasi tab Bootstrap 5
        var triggerTabList = [].slice.call(document.querySelectorAll('#subjectTab button'));
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
    });
</script>
