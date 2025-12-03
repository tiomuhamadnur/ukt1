@extends('layout.userbase')

@section('title-head')
    <title>
        Dashboard Sigma | PJLP
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard Sigma PJLP</li>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('pjlp-absensi.create') }}">
                                <div class="launch-box h-180">
                                    <h3>Absensi Saya</h3>
                                    <i class="fa fa-id-card"></i>
                                    <p>#</p>
                                    <h5 class="jam"></h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('pjlp-kinerja.create') }}">
                                <div class="launch-box h-180">
                                    <h3>Kinerja</h3>
                                    <i class="fa fa-suitcase"></i>
                                    <p>#</p>
                                    <h5>Lihat Data Kinerja</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                            <a href="{{route('pjlp-cuti.index')}}">
                                <div class="launch-box h-180">
                                    <h3>Cuti Saya</h3>
                                    <i class="fa fa-calendar-times"></i>
                                    <p>#</p>
                                    {{-- @if ($sisa_cuti > 0)
                                    @else
                                    <h5>Kuota Cuti Habis</h5>
                                    @endif --}}
                                    <h5>Sisa Cuti: x</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 576px) {
            h3 {
                font-size: 15px;
            }
        }
    </style>

    <div class="modal fade" id="spModal" tabindex="-1" role="dialog" aria-labelledby="spModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-danger shadow-lg">
                <div class="modal-header bg-danger text-white d-flex justify-content-center">
                    <h5 class="modal-title">⚠️ Peringatan SP</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    Halo <strong>{{ Auth::user()->name }}</strong>, berdasarkan catatan sistem <br>Anda memiliki
                    <b>Surat Peringatan (SP)</b>.
                    <br><br>
                    Mohon untuk lebih <span class="text-danger font-weight-bold">meningkatkan kedisiplinan</span>
                    dan <span class="text-danger font-weight-bold">ketertiban dalam absensi</span>.
                    <br><br>
                    Silakan klik tombol di bawah untuk melihat detail Surat Peringatan Anda.
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <a href="#" class="btn btn-primary">
                        📄 Lihat Detail SP
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script>
        function toggleModal(id) {
            $('#id').val(id);
        }

        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            document.querySelectorAll('.jam').forEach(function(element) {
                element.innerHTML = h + ":" + m + ":" + s + ' WIB';
            });

            setTimeout(startTime, 1000);
        }


        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }

        $(document).ready(function() {
            startTime();
        });
    </script>
@endsection

{{-- // $(function() {
//     startTime();
//     @if ($surat_peringatan > 0)
//         $('#spModal').modal('show');
//     @endif
// }); --}}
