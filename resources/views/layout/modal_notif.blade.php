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
                    <div class="d-inline-flex align-items-center justify-content-center
                                rounded-circle border border-danger mb-3"
                        style="width: 120px; height: 120px;">
                        <i class="fa fa-trash text-danger" style="font-size: 80px;"></i>
                    </div>
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

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-body text-center">
                    <div class="d-inline-flex align-items-center justify-content-center
                                rounded-circle border border-danger mb-3"
                        style="width: 120px; height: 120px;">
                        <i class="fa fa-sign-out-alt text-danger" style="font-size: 80px;"></i>
                    </div>
                    <p>Apakah Anda yakin ingin <strong>Keluar</strong> dari sistem ini?</p>
                </div>

                <div class="modal-footer custom">
                    <div class="left-side">
                        <button type="button" class="btn btn-link danger" data-dismiss="modal">Tidak</button>
                    </div>

                    <div class="divider"></div>

                    <div class="right-side">
                        <button type="submit" class="btn btn-link success">Ya, Keluar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End Modal Logout -->
