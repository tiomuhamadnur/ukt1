@extends('layout.base')

@section('title-head')
    <title>Masterdata | Daftar Formasi Tim</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item">Tim</li>
            <li class="breadcrumb-item active">Formasi Tim</li>
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
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Nama Formasi</th>
                                    {{-- <th class="text-center">Kode</th> --}}
                                    <th class="text-center">Tim</th>
                                    <th class="text-center">Pulau</th>
                                    <th class="text-center">Koordinator</th>
                                    <th class="text-center">Personel</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($formasi_tim as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->periode ?? '-' }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        {{-- <td class="text-center">{{ $item->code }}</td> --}}
                                        <td class="text-center">{{ $item->tim->name ?? '-' }}</td>
                                        <td class="text-center">{{ $item->pulau->name ?? '-' }}</td>
                                        <td class="text-center">{{ $item->koordinator->name ?? '-' }}</td>
                                        <td class="text-center">{{ $item->user->name ?? '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary" data-toggle="modal"
                                                data-target="#editData{{ $item->uuid }}"><i
                                                    class="fa fa-edit"></i></button>
                                            <a href="javascript:void(0);" class="btn btn-outline-danger"
                                                onclick="toggleModal('{{ $item->uuid }}')"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit --}}
                                    <div class="modal fade" id="editData{{ $item->uuid }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Formasi Tim</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <form action="{{ route('formasi-tim.update', $item->uuid) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Formasi:</label>
                                                            <input type="text" class="form-control" name="name"
                                                                value="{{ $item->name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kode:</label>
                                                            <input type="text" class="form-control" name="code"
                                                                value="{{ $item->code }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tim:</label>
                                                            <select name="tim_id" class="form-control">
                                                                <option value="" disabled>-- Pilih Tim --</option>
                                                                @foreach ($tim as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $item->tim_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Pulau:</label>
                                                            <select name="pulau_id" class="form-control">
                                                                <option value="" disabled>-- Pilih Pulau --</option>
                                                                @foreach ($pulau as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $item->pulau_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Personel:</label>
                                                            <select name="user_id" class="form-control">
                                                                <option value="" disabled>-- Pilih Personel --
                                                                </option>
                                                                @foreach ($user as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $item->user_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Koordinator:</label>
                                                            <select name="koordinator_id" class="form-control">
                                                                <option value="" disabled>-- Pilih Koordinator --
                                                                </option>
                                                                @foreach ($user as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $item->koordinator_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Periode:</label>
                                                            <select name="periode" class="form-control" required>
                                                                @foreach ($tahun as $pilih_tahun)
                                                                    <option value="{{ $pilih_tahun }}"
                                                                        {{ isset($formasiTimItem) && $formasiTimItem->periode == $pilih_tahun ? 'selected' : '' }}>
                                                                        {{ $pilih_tahun }}
                                                                    </option>
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
                                        <td colspan="8" class="text-center text-muted">Belum ada data formasi tim.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Formasi Tim</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('formasi-tim.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Formasi:</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label>Kode:</label>
                            <input type="text" class="form-control" name="code">
                        </div>
                        <div class="form-group">
                            <label>Tim:</label>
                            <select name="tim_id" class="form-control">
                                <option value="" disabled selected>-- Pilih Tim --</option>
                                @foreach ($tim as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pulau:</label>
                            <select name="pulau_id" class="form-control">
                                <option value="" disabled selected>-- Pilih Pulau --</option>
                                @foreach ($pulau as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Koordinator:</label>
                            <select name="koordinator_id" class="form-control">
                                <option value="" disabled selected>-- Pilih Koordinator --</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Personel:</label>
                            <select name="user_id" class="form-control">
                                <option value="" disabled selected>-- Pilih Personel --</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Periode:</label>
                            <select name="periode" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $pilih_tahun)
                                    <option value="{{ $pilih_tahun }}">{{ $pilih_tahun }}</option>
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

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Formasi Tim</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data formasi tim ini?</p>
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
            let url = "{{ route('formasi-tim.destroy', ':uuid') }}";
            url = url.replace(':uuid', uuid);
            document.getElementById('deleteForm').action = url;
            $('#deleteModal').modal('show');
        }
    </script>
@endsection
