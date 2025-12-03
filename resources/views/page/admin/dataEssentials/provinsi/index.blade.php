@extends('layout.base')

@section('title-head')
    <title>
        Masterdata | Daftar Provinsi
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item">Provinsi</li>
            <li class="breadcrumb-item active">Daftar Provinsi</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <br>
                            <form class="form-inline mb-2">
                                <input class="form-control mr-sm-2" type="search" placeholder="Cari sesuatu di sini..."
                                    aria-label="Search" id="search-bar">
                                <button class="btn btn-dark my-2 my-sm-0" type="submit">Pencarian</button>
                            </form>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <a href="{{ route('dataEssentials.index') }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"></i>Kembali</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahData">Tambah
                                Data</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Nama Provinsi</th>
                                        <th class="text-center">Kode Provinsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($provinsi as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">{{ $item->code }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#editData{{ $item->uuid }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="javascript:void(0);" class="btn btn-outline-danger"
                                                    onclick="toggleModal('{{ $item->uuid }}')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit --}}
                                        <div class="modal fade" id="editData{{ $item->uuid }}" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Provinsi</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('provinsi.update', $item->uuid) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nama Provinsi:</label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ $item->name }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Kode Provinsi:</label>
                                                                <input type="text" class="form-control" name="code"
                                                                    value="{{ $item->code }}">
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer custom">
                                                            <div class="left-side">
                                                                <button type="button" class="btn btn-link danger"
                                                                    data-dismiss="modal">Batal</button>
                                                            </div>

                                                            <div class="divider"></div>

                                                            <div class="right-side">
                                                                <button type="submit" class="btn btn-link success">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada data provinsi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tamvbah Data Provinsi-->
    <div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="customModalTwoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalTwoLabel">Form Tambah Data Provinsi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('provinsi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Nama Provinsi:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Kode Provinsi:</label>
                            <input type="text" class="form-control" id="code" name="code">
                        </div>
                    </div>

                    <div class="modal-footer custom">
                        <div class="left-side">
                            <button type="button" class="btn btn-link danger" data-dismiss="modal">Batal</button>
                        </div>

                        <div class="divider"></div>

                        <div class="right-side">
                            <button type="submit" class="btn btn-link success">Buat Data</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- Modal Hapus Data Provinsi -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data provinsi ini?</p>
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
@endsection

@section('javascript')
    <script>
        function toggleModal(uuid) {
            let url = "{{ route('provinsi.destroy', ':uuid') }}";
            url = url.replace(':uuid', uuid);

            document.getElementById('deleteForm').action = url;

            $('#deleteModal').modal('show');
        }
    </script>
@endsection
