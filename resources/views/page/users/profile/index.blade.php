@extends('layout.userbase')

@section('title-head')
    <title>
        User Profil
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Profil</li>
            <li class="breadcrumb-item active">User Profil</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="user-details h-320">
                <a href="javascript:;" data-toggle="modal" data-target="#formPhotoProfilModal"
                    data-photo="{{ $user->photo != null ? asset('storage/' . $user->photo) : asset('assets/img/avadefault.jpg') }}">
                    <div class="user-thumb">
                        <img src="{{ $user->photo != null ? asset('storage/' . $user->photo) : asset('assets/img/avadefault.jpg') }}"
                            alt="Photo">
                    </div>
                </a>
                <h4>{{ $user->name }}</h4>
                <h5>{{ $user->nip ?? '-' }}</h5>
                <br>
                <h6>{{ $user->formasi_tim?->tim?->seksi?->unit_kerja?->name ?? ($user->unit_kerja?->name ?? 'Unit Kerja N/A') }}
                </h6>
                <h6>Seksi {{ $user->formasi_tim?->tim?->seksi?->name ?? ($user->seksi?->name ?? 'N/A') }}</h6>
                <p>Pulau {{ $user->formasi_tim->pulau->name ?? 'N/A' }}</p>
                @role('pjlp')
                    <h6>Sisa Cuti Anda Tahun {{ $tahun }}: {{ $user->konfigurasi_cuti->jumlah_akhir ?? 'N/A' }} hari</h6>
                @endrole
            </div>
        </div>
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Saya</h5>
                </div>
                <div class="card-body">
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" placeholder="Nama" value="{{ $user->name }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email"
                                    value={{ $user->email }} disabled>
                            </div>
                            {{-- <div class="form-group">
                                <label for="no_hp" class="optional">Nomor HP/WA</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="input nomor hp" value="{{ $user->no_hp }}">
                            </div> --}}
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" value="{{ $user->jabatan->name ?? 'N/A' }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $user->formasi_tim?->tim?->seksi?->unit_kerja?->name ?? ($user->unit_kerja->name ?? 'N/A') }}">
                            </div>
                            <div class="form-group">
                                <label>Seksi</label>
                                <input type="text" class="form-control"
                                    value="{{ $user->formasi_tim?->tim?->seksi?->name ?? ($user->seksi->name ?? 'N/A') }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label for="pulau">Tempat Bertugas</label>
                                <input type="text" class="form-control" id="pulau" placeholder="Pulau"
                                    value="{{ $user->formasi_tim?->pulau?->name ? 'Pulau ' . $user->formasi_tim->pulau->name : 'N/A' }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-right">
                                {{-- <button type="button" id="submit" name="submit" class="btn btn-dark">Simpan
                                    Perubahan</button> --}}
                                <a href="{{ route('password.index') }}" class="btn btn-warning">Ubah
                                    Password</a>
                                <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row gutters">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">📄 Daftar Surat Peringatan (SP) - Tahun #</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {{ $dataTable->table([
                            'class' => 'table table-bordered table-striped',
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    {{-- BEGIN: Update Photo Profil --}}
    <div id="formPhotoProfilModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Photo Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- PREVIEW -->
                    <div class="d-flex justify-content-center mb-3">
                        <img class="img-thumbnail" id="previewImage" src="#" alt="Preview"
                            style="max-width: 250px; max-height: 250px;">
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('user.photo.update', $user->uuid) }}" id="formPhotoProfil" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="d-flex justify-content-center">
                            <div class="form-group w-75 text-center">
                                <label class="required">Photo Profil</label>
                                <input type="file" id="imageInput" name="photo" class="form-control" accept="image/*"
                                    required>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submut" form="formPhotoProfil" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Update Photo Profil --}}

    {{-- BEGIN: Update Photo TTD --}}
    <div id="formPhotoTTDModal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Photo TTD</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img class="img-thumbnail" id="previewImageTTD" src="#" alt="Preview"
                            style="max-width: 250px; max-height: 250px; display: none;">
                    </div>
                    <div class="form-row gutters mt-3">
                        {{-- <form action="{{ route('user.update_ttd') }}" id="formPhotoTTD" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="">Photo TTD</label>
                                <input type="file" id="imageInputTTD" name="photo" class="form-control"
                                    accept="image/*" required>
                            </div>
                        </form> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submut" form="formPhotoTTD" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Update Photo TTD --}}
@endsection

{{-- @push('scripts')
    {{ $dataTable->scripts() }}
@endpush --}}

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#formPhotoProfilModal').on('show.bs.modal', function(e) {
                var photo = $(e.relatedTarget).data('photo');

                document.getElementById("previewImage").src = photo;
            });

            const imageInput = document.getElementById('imageInput');
            const previewImage = document.getElementById('previewImage');

            imageInput.addEventListener('change', function(event) {
                const selectedFile = event.target.files[0];

                if (selectedFile) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                    }

                    reader.readAsDataURL(selectedFile);
                }
            });
        });


        // const imageInputTTD = document.getElementById('imageInputTTD');
        // const previewImageTTD = document.getElementById('previewImageTTD');

        // imageInputTTD.addEventListener('change', function(event) {
        //     const selectedFile = event.target.files[0];

        //     if (selectedFile) {
        //         const reader = new FileReader();

        //         reader.onload = function(e) {
        //             previewImageTTD.src = e.target.result;
        //             previewImageTTD.style.display = 'block';
        //         }

        //         reader.readAsDataURL(selectedFile);
        //     }
        // });
    </script>
@endsection
