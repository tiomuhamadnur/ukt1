@extends('layout.userbase')

@section('title-head')
    <title>
        Cuti | Permohonan Cuti / Izin
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pjlp.index') }}">Cuti PJLP</a></li>
            <li class="breadcrumb-item active">Permohonan Cuti / Izin</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12 col-12">
            <div class="card m-0">
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('pjlp-cuti.index') }}"
                            class="btn btn-primary col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
                            style="border-radius: 6px">Lihat Pengajuan
                            Cuti Saya</a>
                    </div>
                    <h4 class="text-center mb-3"><u>Form Pengajuan Cuti</u></h4>
                    <form action="{{ route('pjlp-cuti.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="jenis_pengajuan" class="required">Jenis Pengajuan</label>
                                    <select class="form-control" id="jenis_cuti_id" name="jenis_cuti_id" required>
                                        <option value="" selected disabled>- pilih jenis cuti -</option>
                                        @foreach ($jenis_cuti as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="alert" class="text-danger" style="display: none">*Izin Sakit wajib
                                        menyertakan Surat Keterangan Dokter</p>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <label for="jenis_pengajuan" class="required">Tanggal Mulai:</label>
                                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                                placeholder="Tanggal Awal" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <label for="jenis_pengajuan" class="required">Tanggal Akhir:</label>
                                            <input type="date" class="form-control" id="tanggal_akhir"
                                                name="tanggal_akhir" placeholder="Tanggal Akhir" required>
                                        </div>
                                        @error('tanggal_akhir')
                                            <div class="container">
                                                <p class="text-danger">
                                                    {{ $message }}
                                                </p>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                            <label for="jenis_pengajuan">Total Permohonan (Hari):</label>
                                            <input type="text" class="form-control" id="total_hari"
                                                placeholder="total hari cuti" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12" id="total_cuti_tahunan">
                                <div class="form-group">
                                    <label for="nama">Cuti Tahunan Tersedia</label>
                                    <input type="text" class="form-control" value="{{ $sisa_cuti ?? '0' }} hari"
                                        disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label for="catatan" class="required">Alasan:</label>
                                <textarea id="catatan" class="form-control" name="catatan" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="row" id="lampiran_wrapper" style="display: none">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                                <label for="catatan" id="label_lampiran" class="">Lampiran Surat Keterangan
                                    Dokter:</label>
                                <div class="">
                                    <input type="file" id="lampiran" class="form-control" name="lampiran"
                                        accept="image/*">
                                </div>
                                @error('lampiran')
                                    <div class="container">
                                        <p class="text-danger">
                                            {{ $message }}
                                        </p>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2 justify-content-center d-flex">
                                <div class="row">
                                    <a href="{{ route('pjlp.index') }}" class="btn btn-dark mx-2">Batal</a>
                                    <button type="submit" class="btn btn-primary mx-2">Ajukan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        // ================= HITUNG JUMLAH HARI CUTI =================
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        tanggalAkhir.addEventListener('change', hitungJumlahHariCuti);

        function hitungJumlahHariCuti() {
            var tanggalMulai = document.getElementById('tanggal_awal').value;
            var tanggalSelesai = document.getElementById('tanggal_akhir').value;

            if (!tanggalMulai || !tanggalSelesai) return;

            var jumlahHariCuti = hitungJumlahHari(tanggalMulai, tanggalSelesai);
            document.getElementById('total_hari').value = jumlahHariCuti + ' hari';
        }

        function hitungJumlahHari(tanggalMulai, tanggalSelesai) {
            var satuHari = 24 * 60 * 60 * 1000;
            var tanggalMulaiObj = new Date(tanggalMulai);
            var tanggalSelesaiObj = new Date(tanggalSelesai);

            var selisihHari = Math.abs((tanggalSelesaiObj - tanggalMulaiObj) / satuHari) + 1;
            return selisihHari;
        }

        // ================= TAMBAH HARI (UNTUK CUTI MELAHIRKAN) =================
        function tambahHari(tanggal, jumlahHari) {
            var date = new Date(tanggal);
            date.setDate(date.getDate() + jumlahHari);

            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var dd = String(date.getDate()).padStart(2, '0');

            return yyyy + '-' + mm + '-' + dd;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const jenisCuti = document.getElementById('jenis_cuti_id');
            const totalCutiTahunan = document.getElementById('total_cuti_tahunan');
            const alert = document.getElementById('alert');

            const lampiranWrapper = document.getElementById('lampiran_wrapper');
            const lampiran = document.getElementById('lampiran');
            const labelLampiran = document.getElementById('label_lampiran');

            const tanggalAwal = document.getElementById('tanggal_awal');
            const tanggalAkhir = document.getElementById('tanggal_akhir');
            const totalHari = document.getElementById('total_hari');

            // ================= JENIS CUTI CHANGE =================
            jenisCuti.addEventListener('change', function() {
                const jenis = this.value;

                // RESET DEFAULT
                alert.style.display = 'none';
                totalCutiTahunan.style.display = 'block';
                lampiranWrapper.style.display = 'none';
                lampiran.required = false;
                lampiran.value = '';
                labelLampiran.classList.remove('required');

                tanggalAkhir.disabled = false;
                tanggalAkhir.value = '';
                totalHari.value = '';

                if (jenis === '2') {
                    // ============ IZIN SAKIT ============
                    alert.style.display = 'block';
                    totalCutiTahunan.style.display = 'none';

                    lampiranWrapper.style.display = 'block';
                    lampiran.required = true;
                    labelLampiran.classList.add('required');

                } else if (jenis === '3') {
                    // ============ CUTI MELAHIRKAN ============
                    totalCutiTahunan.style.display = 'none';

                    if (tanggalAwal.value) {
                        tanggalAkhir.value = tambahHari(tanggalAwal.value, 59);
                        totalHari.value = '60 hari';
                    }

                    tanggalAkhir.disabled = true;
                }
            });

            // ================= TANGGAL MULAI CHANGE (KHUSUS MELAHIRKAN) =================
            tanggalAwal.addEventListener('change', function() {
                if (jenisCuti.value === '3') {
                    tanggalAkhir.value = tambahHari(this.value, 59);
                    totalHari.value = '60 hari';
                }
            });
        });
    </script>
@endsection
