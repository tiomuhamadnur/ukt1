@extends('layout.userbase')

@section('title-head')
    <title>
        Kinerja | Tambah Data Laporan Kinerja
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pjlp.index') }}">Kinerja</a></li>
            <li class="breadcrumb-item active">Laporan Kinerja</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">
            <form action="{{ route('pjlp-kinerja.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="card m-0">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('pjlp-kinerja.index') }}"
                                class="btn btn-primary col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
                                style="border-radius: 6px">Lihat Daftar
                                Kinerja Saya</a>
                        </div>
                        <h4 class="text-center">Form Input Kinerja</h4>
                        <div class="form-group">
                            <label>Data Lengkap</label>
                            <table>
                                <tr>
                                    <td style="width: 90px">Nama</td>
                                    <td style="width: 15px">:</td>
                                    <td class="font-weight-bolder">{{ $user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>NIP/ID</td>
                                    <td>:</td>
                                    <td>{{ $user->nip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $user->jabatan->name ?? '-' }}</td>
                                </tr>
                                {{-- <tr>
                                    <td>Koordinator</td>
                                    <td>:</td>
                                    <td>{{ $formasi_tim->koordinator->name ?? '#' }}</td>
                                </tr> --}}
                                <tr>
                                    <td>Tim</td>
                                    <td>:</td>
                                    <td>{{ $formasi_tim->tim->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Seksi</td>
                                    <td>:</td>
                                    <td>{{ $formasi_tim->tim->seksi->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Pulau</td>
                                    <td>:</td>
                                    <td>{{ $formasi_tim->pulau->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="required">Nama Kegiatan:</label>
                            <select id="kegiatan_id" name="kegiatan_id" class="form-control" required>
                                <option value="" selected disabled>- pilih kegiatan -</option>
                                @foreach ($kegiatan as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                                <option value="">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group" id="kegiatan_lainnya_container" style="display: none">
                            <label id="label_kegiatan" class="">Kegiatan Lainnya:</label>
                            <input type="text" id="kegiatan_lainnya" class="form-control" name="kegiatan_lainnya"
                                placeholder="input kegiatan lainnya" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="required">Lokasi Kegiatan:</label>
                            <input type="text" class="form-control" name="lokasi" placeholder="input lokasi kegiatan"
                                autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Tanggal:</label>
                            <input type="date" class="form-control" name="tanggal" placeholder="input tanggal kegiatan" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="required">Photo Kegiatan: <span class="text-secondary">(Max: 3 photo)</span></label>
                            <input type="file" class="form-control image-input" name="photo[]" multiple accept="image/*"
                                required>
                            @error('photo')
                                <p class="text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                            <div class="row-group d-flex preview-container my-2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="optional">Catatan</label>
                            <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="btn group-button">
                            <button type="submit" class="btn btn-primary float-right ml-3">Kirim</button>
                            <a href="{{ route('pjlp.index') }}" class="btn btn-dark">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var kategori = document.getElementById('kegiatan_id');
        var inputKegiatanLainnya = document.getElementById('kegiatan_lainnya');
        var containerKegiatanLainnya = document.getElementById('kegiatan_lainnya_container');
        var label_kegiatan = document.getElementById('label_kegiatan');

        kategori.addEventListener('change', function() {
            if (kategori.value === '') {
                kategori.required = false;
                containerKegiatanLainnya.style.display = 'block';
                inputKegiatanLainnya.required = true;
                label_kegiatan.classList.add('required');
            } else {
                containerKegiatanLainnya.style.display = 'none';
                inputKegiatanLainnya.required = false;
                label_kegiatan.classList.remove('required');
            }
        });

        const imageInputs = document.querySelectorAll('.image-input');
        const previewContainer = document.querySelector('.preview-container');

        imageInputs.forEach(input => {
            input.addEventListener('change', function(event) {
                previewContainer.innerHTML = '';

                const files = event.target.files;
                for (const file of files) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const previewImage = document.createElement('img');
                        previewImage.className = 'preview-image';
                        previewImage.src = e.target.result;
                        previewImage.style = 'width: 100px;';
                        previewImage.className = 'img-thumbnail btn-group mt-2 me-2 d-inline-flex';

                        previewContainer.appendChild(previewImage);
                    }

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
