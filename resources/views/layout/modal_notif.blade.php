@if (session('notify'))
    <script>
        Swal.fire({
            icon: "success",
            title: "Sukses!",
            html: @json(session('notify')) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@elseif (session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops!",
            html: @json(session('error')) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@elseif ($errors->any())
    <script>
        @php
            $errorsList = $errors->all();
            $messageError = collect($errorsList)
                ->map(function ($msg, $index) use ($errorsList) {
                    return count($errorsList) > 1
                        ? ($index + 1) . '. ' . e($msg)
                        : e($msg);
                })
                ->implode('<br>');
        @endphp
        Swal.fire({
            icon: "error",
            title: "Ooopss!",
            html: @json($messageError) // pakai html, bukan text
        }).then(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        });
    </script>
@endif

<!-- Modal Hapus Data -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="deleteForm" action="#" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer custom">
                    <div class="left-side">
                        <button type="button" class="btn btn-link danger" data-dismiss="modal">Tidak</button>
                    </div>

                    <div class="divider"></div>

                    <div class="right-side">
                        <button type="submit" class="btn btn-link success">Ya, Hapus</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End Modal Hapus Data -->
