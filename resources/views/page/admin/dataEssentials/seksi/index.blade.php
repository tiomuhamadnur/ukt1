@extends('layout.base')

@section('title-head')
    <title>Masterdata | Daftar Seksi</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar Seksi</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-6">
                            <form class="form-inline mb-2">
                                <input class="form-control mr-2" type="search" placeholder="Cari sesuatu..."
                                    id="search-bar">
                                <button class="btn btn-dark" type="submit">Pencarian</button>
                            </form>
                        </div>
                        <div class="col-12 mb-3 text-left">
                            <a href="{{ route('dataEssentials.index') }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"></i>Kembali</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahData">Tambah
                                Data</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Nama Seksi</th>
                                    <th class="text-center">Kode Seksi</th>
                                    <th class="text-center">Unit Kerja</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($seksi as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->code }}</td>
                                        <td class="text-center">{{ $item->unit_kerja->name ?? '-' }}</td>
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

                                    {{-- Modal Edit Seksi --}}
                                    <div class="modal fade" id="editData{{ $item->uuid }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Data Seksi</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>

                                                <form action="{{ route('seksi.update', $item->uuid) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Seksi:</label>
                                                            <input type="text" class="form-control" name="name"
                                                                value="{{ $item->name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kode Seksi:</label>
                                                            <input type="text" class="form-control" name="code"
                                                                value="{{ $item->code }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Unit Kerja:</label>
                                                            <select name="unit_kerja_id" class="form-control" required>
                                                                <option value="" disabled>-- Pilih Unit Kerja --
                                                                </option>
                                                                @foreach ($unit_kerja as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $item->unit_kerja_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
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
                                        <td colspan="5" class="text-center text-muted">Belum ada data Seksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Seksi -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Seksi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="{{ route('seksi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Seksi:</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label>Kode Seksi:</label>
                            <input type="text" class="form-control" name="code">
                        </div>
                        <div class="form-group">
                            <label>Unit Kerja:</label>
                            <select name="unit_kerja_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Unit Kerja --</option>
                                @foreach ($unit_kerja as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Hapus Seksi -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Seksi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data Seksi ini?</p>
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
            let url = "{{ route('seksi.destroy', ':uuid') }}";
            url = url.replace(':uuid', uuid);
            document.getElementById('deleteForm').action = url;
            $('#deleteModal').modal('show');
        }
    </script>
@endsection
