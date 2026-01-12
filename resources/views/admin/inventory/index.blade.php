@extends('adminlte::page')

@section('title', 'Inventory')

{{-- ====================== CUSTOM CSS ====================== --}}
@section('css')
<style>

    /* Card style */
    .custom-card {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: white;
        padding: 0;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .card-header-custom {
        background: #f3f4f6;
        padding: 12px 18px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
    }

    /* Table style */
    .table-inventory {
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0;
    }

    .table-inventory thead th {
        background-color: #e8f5e9;
        color: #1e293b;
        font-weight: 600;
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #d1d5db;
    }

    .table-inventory tbody td {
        padding: 11px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-inventory tbody tr:nth-child(even) td {
        background: #f9fafb;
    }

    .btn-blue {
        background: #3b82f6 !important;
        color: white !important;
        border-radius: 6px;
        font-weight: 600;
        padding: 7px 16px;
    }

    .btn-green {
        background: #22c55e !important;
        color: white !important;
        border-radius: 6px;
        font-weight: 600;
        padding: 7px 16px;
    }

    .custom-input {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 9px;
    }
    .custom-input:focus {
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
    }

    .notification-bar {
        background: #4ade80;
        padding: 10px;
        color: white;
        font-weight: 600;
        text-align: center;
        border-radius: 6px;
        margin-bottom: 20px;
    }

</style>
@endsection


{{-- ====================== CONTENT HEADER ====================== --}}
@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Inventory</h1>

    <div>
        <a href="{{ route('inventory.print') }}" class="btn btn-info" target="_blank">
            🖨️ Print / Export
        </a>
        <a href="{{ route('inventory.history') }}" class="btn btn-blue">
            📊 Riwayat Transaksi
        </a>

        <button class="btn btn-blue" data-toggle="modal" data-target="#modalTambahBahan">
            + Tambah Bahan Baru
        </button>
    </div>
</div>
@stop


{{-- ====================== CONTENT ====================== --}}
@section('content')

{{-- SUCCESS NOTIFICATION --}}
@if(session('success'))
    <div class="notification-bar">{{ session('success') }}</div>
@endif


{{-- ====================== STOK BARANG ====================== --}}
<div class="custom-card">
    <div class="card-header-custom">Stok Barang Saat Ini</div>

    <div class="p-3">
        <table class="table-inventory">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Stok Awal</th>
                    <th>Stok Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ number_format($item->harga,0,',','.') }}</td>

                    {{-- STOK AWAL --}}
                    <td>{{ $item->stok_awal }}</td>

                    {{-- STOK AKHIR (otomatis dihitung di controller) --}}
                    <td>{{ $item->stok_akhir }}</td>

                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#modalEdit{{ $item->id }}">Edit</button>

                        <form action="{{ route('inventory.item.destroy', $item) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



{{-- ====================== TRANSAKSI BARANG MASUK/KELUAR ====================== --}}
<div class="row">

    {{-- BARANG MASUK --}}
    <div class="col-md-6">
        <div class="custom-card">
            <div class="card-header-custom">Transaksi Barang Masuk</div>

            <div class="p-3">
                <form action="{{ route('inventory.in.store') }}" method="POST">
                    @csrf

                    <label>Pilih Bahan</label>
                    <select name="inventory_item_id" class="form-control custom-input mb-2" required>
                        <option value="">Pilih Bahan</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>

                    <label>Jumlah Masuk</label>
                    <input type="number" name="jumlah_masuk" class="form-control custom-input mb-2" required>

                    <label>Keterangan</label>
                    <input type="text" name="keterangan" class="form-control custom-input mb-3">

                    <button class="btn-green w-100">Tambah Stok Masuk</button>
                </form>
            </div>
        </div>
    </div>



    {{-- BARANG KELUAR --}}
    <div class="col-md-6">
        <div class="custom-card">
            <div class="card-header-custom">Transaksi Barang Keluar</div>

                <div class="p-3">
                <form action="{{ route('inventory.out.store') }}" method="POST">
                    @csrf

                    {{-- PILIH BAHAN --}}
                    <div class="mb-3">
                        <label class="fw-bold d-block">Pilih Bahan</label>
                        <select name="inventory_item_id" class="form-control custom-input">
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JUMLAH KELUAR --}}
                    <div class="mb-3">
                        <label class="fw-bold d-block">Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" class="form-control custom-input" required>
                    </div>

                    {{-- LOKASI --}}
                    <div class="mb-1">
                        <label class="fw-bold d-block">Lokasi Rumah</label>
                        <select name="location_id" class="form-control custom-input">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUTTON LOKASI BARU — HARUS DI LUAR DIV --}}
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm mt-1"
                            data-toggle="modal" data-target="#modalTambahLokasi">
                            + Tambah Lokasi Baru
                        </button>
                    </div>

                    {{-- TYPE --}}
                    <div class="mb-1">
                        <label class="fw-bold d-block">Type Rumah</label>
                        <select name="type_id" class="form-control custom-input">
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUTTON TYPE BARU --}}
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm mt-1"
                            data-toggle="modal" data-target="#modalTambahType">
                            + Tambah Type Baru
                        </button>
                    </div>

                    {{-- UNIT --}}
                    <div class="mb-1">
                        <label class="fw-bold d-block">Unit Rumah</label>
                        <select name="unit_id" class="form-control custom-input">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">
                                    {{ $unit->location->nama }} - Unit {{ $unit->kode_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BUTTON UNIT BARU --}}
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm mt-1"
                            data-toggle="modal" data-target="#modalTambahUnit">
                            + Tambah Unit Baru
                        </button>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-4">
                        <label class="fw-bold d-block">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control custom-input">
                    </div>

                    <button class="btn btn-danger w-100">Keluarkan Barang</button>

                </form>
            </div>
        </div>
    </div>

</div>

{{-- ======================================================================= --}}
{{-- =============================== MODALS ================================ --}}
{{-- ======================================================================= --}}


{{-- Modal Tambah Bahan --}}

<div class="modal fade" id="modalTambahBahan">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('inventory.item.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah Bahan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Bahan</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control">
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stok_awal" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>

{{-- Modal Tambah Lokasi --}}

<div class="modal fade" id="modalTambahLokasi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lokasi Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" id="inputNamaLokasi" class="form-control" required placeholder="Contoh: ILALANG 02">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanLokasi">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Type --}}
<div class="modal fade" id="modalTambahType">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Type Rumah Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Type Rumah</label>
                    <input type="text" id="inputNamaType" class="form-control" required placeholder="Contoh: 45 / 60 / 70">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanType">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Unit --}}
<div class="modal fade" id="modalTambahUnit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Rumah Baru</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Lokasi Rumah</label>
                    <select id="selectLokasiUnit" class="form-control" required>
                        <option value="">-- pilih lokasi --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Type Rumah</label>
                    <select id="selectTypeUnit" class="form-control" required>
                        <option value="">-- pilih type --</option>
                        @foreach($types as $tp)
                            <option value="{{ $tp->id }}">{{ $tp->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Unit</label>
                    <input type="text" id="inputKodeUnit" class="form-control" placeholder="01 / 02 / 03" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanUnit">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Bahan --}}
@foreach($items as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('inventory.item.update', $item->id) }}" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Bahan: {{ $item->nama }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Nama Bahan</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ $item->nama }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control"
                           value="{{ $item->satuan }}">
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control"
                           value="{{ $item->harga }}" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $item->keterangan }}</textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>
@endforeach

@stop

{{-- ====================== JAVASCRIPT AJAX ====================== --}}
@section('js')
<script>
$(document).ready(function() {
    // CSRF Token untuk AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // ===== TAMBAH LOKASI BARU (AJAX) =====
    $('#btnSimpanLokasi').click(function() {
        var namaLokasi = $('#inputNamaLokasi').val().trim();

        if (namaLokasi === '') {
            alert('Nama lokasi tidak boleh kosong!');
            return;
        }

        $.ajax({
            url: '{{ route("inventory.add.location") }}',
            type: 'POST',
            data: { nama: namaLokasi },
            success: function(response) {
                // Tambahkan option baru ke dropdown lokasi di form barang keluar
                $('select[name="location_id"]').append(
                    '<option value="' + response.id + '">' + response.nama + '</option>'
                );

                // Tambahkan juga ke dropdown lokasi di modal tambah unit
                $('#selectLokasiUnit').append(
                    '<option value="' + response.id + '">' + response.nama + '</option>'
                );

                // Pilih lokasi yang baru ditambahkan
                $('select[name="location_id"]').val(response.id);

                // Reset input dan tutup modal
                $('#inputNamaLokasi').val('');
                $('#modalTambahLokasi').modal('hide');

                // Tampilkan notifikasi sukses
                alert('Lokasi "' + response.nama + '" berhasil ditambahkan!');
            },
            error: function(xhr) {
                alert('Gagal menambahkan lokasi. Silakan coba lagi.');
                console.log(xhr.responseText);
            }
        });
    });

    // ===== TAMBAH TYPE BARU (AJAX) =====
    $('#btnSimpanType').click(function() {
        var namaType = $('#inputNamaType').val().trim();

        if (namaType === '') {
            alert('Nama type tidak boleh kosong!');
            return;
        }

        $.ajax({
            url: '{{ route("inventory.add.type") }}',
            type: 'POST',
            data: { nama: namaType },
            success: function(response) {
                // Tambahkan option baru ke dropdown type di form barang keluar
                $('select[name="type_id"]').append(
                    '<option value="' + response.id + '">' + response.nama + '</option>'
                );

                // Tambahkan juga ke dropdown type di modal tambah unit
                $('#selectTypeUnit').append(
                    '<option value="' + response.id + '">' + response.nama + '</option>'
                );

                // Pilih type yang baru ditambahkan
                $('select[name="type_id"]').val(response.id);

                // Reset input dan tutup modal
                $('#inputNamaType').val('');
                $('#modalTambahType').modal('hide');

                // Tampilkan notifikasi sukses
                alert('Type "' + response.nama + '" berhasil ditambahkan!');
            },
            error: function(xhr) {
                alert('Gagal menambahkan type. Silakan coba lagi.');
                console.log(xhr.responseText);
            }
        });
    });

    // ===== TAMBAH UNIT BARU (AJAX) =====
    $('#btnSimpanUnit').click(function() {
        var locationId = $('#selectLokasiUnit').val();
        var typeId = $('#selectTypeUnit').val();
        var kodeUnit = $('#inputKodeUnit').val().trim();

        if (locationId === '') {
            alert('Pilih lokasi terlebih dahulu!');
            return;
        }
        if (typeId === '') {
            alert('Pilih type terlebih dahulu!');
            return;
        }
        if (kodeUnit === '') {
            alert('Kode unit tidak boleh kosong!');
            return;
        }

        $.ajax({
            url: '{{ route("inventory.addUnit") }}',
            type: 'POST',
            data: {
                location_id: locationId,
                type_id: typeId,
                kode_unit: kodeUnit
            },
            success: function(response) {
                // Tambahkan option baru ke dropdown unit di form barang keluar
                $('select[name="unit_id"]').append(
                    '<option value="' + response.id + '">' + response.location_nama + ' - Unit ' + response.kode_unit + '</option>'
                );

                // Pilih unit yang baru ditambahkan
                $('select[name="unit_id"]').val(response.id);

                // Reset form dan tutup modal
                $('#selectLokasiUnit').val('');
                $('#selectTypeUnit').val('');
                $('#inputKodeUnit').val('');
                $('#modalTambahUnit').modal('hide');

                // Tampilkan notifikasi sukses
                alert('Unit "' + response.location_nama + ' - Unit ' + response.kode_unit + '" berhasil ditambahkan!');
            },
            error: function(xhr) {
                alert('Gagal menambahkan unit. Silakan coba lagi.');
                console.log(xhr.responseText);
            }
        });
    });

    // Reset form modal saat modal ditutup
    $('#modalTambahLokasi').on('hidden.bs.modal', function() {
        $('#inputNamaLokasi').val('');
    });

    $('#modalTambahType').on('hidden.bs.modal', function() {
        $('#inputNamaType').val('');
    });

    $('#modalTambahUnit').on('hidden.bs.modal', function() {
        $('#selectLokasiUnit').val('');
        $('#selectTypeUnit').val('');
        $('#inputKodeUnit').val('');
    });
});
</script>
@endsection