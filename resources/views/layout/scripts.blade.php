<script>
    $(document).ready(function() {
        $('#deleteModal').on('show.bs.modal', function(e) {
            var url = $(e.relatedTarget).data('url');

            document.getElementById("deleteForm").action = url;
        });
    })
</script>
