@extends('adminlte::page')

@section('title', 'Riwayat Transaksi')

@section('css')
<style>
    /* Header tabel */
    .custom-header th {
        background-color: #d8f3dc !important; /* hijau lembut */
        color: #1b4332 !important;           /* hijau gelap */
        font-weight: 600;
        padding: 12px;
    }

    /* Semua baris data tetap putih */
    table.table tbody tr {
        background-color: #ffffff !important;
    }

    /* Hilangkan efek hover abu jika ada */
    table.table tbody tr:hover {
        background-color: #f1fdf4 !important; /* opsional efek hover hijau muda */
    }
</style>
@endsection

@section('content_header')
    <h1>Riwayat Transaksi Inventory</h1>
@stop

@section('content')

{{-- ====================== FILTER ====================== --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h3 class="card-title">Filter Riwayat</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('inventory.history') }}" class="row g-3">

            {{-- DARI TANGGAL --}}
            <div class="col-md-4">
                <label class="fw-bold">Dari Tanggal</label>
                <input type="date" class="form-control border rounded" name="start_date" value="{{ request('start_date') }}">
            </div>

            {{-- SAMPAI TANGGAL --}}
            <div class="col-md-4">
                <label class="fw-bold">Sampai Tanggal</label>
                <input type="date" class="form-control border rounded" name="end_date" value="{{ request('end_date') }}">
            </div>

            {{-- LOKASI --}}
            <div class="col-md-3">
                <label class="fw-bold">Lokasi Rumah</label>
                <select name="location_id" class="form-control border rounded">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BUTTON FILTER --}}
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100 rounded">Filter</button>
            </div>

        </form>
    </div>
</div>

{{-- ====================== TAB ====================== --}}
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom">
        <ul class="nav nav-pills">

            <li class="nav-item">
                <a class="nav-link active fw-bold" data-toggle="tab" href="#tab-masuk">
                    Barang Masuk
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link fw-bold" data-toggle="tab" href="#tab-keluar">
                    Barang Keluar
                </a>
            </li>

        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            {{-- ====================== BARANG MASUK ====================== --}}
            <div id="tab-masuk" class="tab-pane fade show active">

                <table class="table table-bordered table-striped">
                    <thead class="custom-header">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Bahan</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($masuk->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Tidak ada transaksi masuk
                                </td>
                            </tr>
                        @else
                            @foreach($masuk as $m)
                            <tr>
                                <td>{{ $m->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $m->item->nama }}</td>
                                <td>{{ $m->jumlah_masuk }}</td>
                                <td>{{ $m->keterangan }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>

            {{-- ====================== BARANG KELUAR ====================== --}}
            <div id="tab-keluar" class="tab-pane fade">

                <table class="table table-bordered table-striped">
                    <thead class="custom-header">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Bahan</th>
                            <th>Jumlah</th>
                            <th>Lokasi - Type - Unit</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($keluar->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Tidak ada transaksi keluar
                                </td>
                            </tr>
                        @else
                            @foreach($keluar as $k)
                            <tr>
                                <td>{{ $k->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $k->item->nama }}</td>
                                <td>{{ $k->jumlah_keluar }}</td>
                                <td>
                                    {{ $k->location->nama }} —
                                    Type {{ $k->type->nama }} —
                                    Unit {{ $k->unit->kode_unit }}
                                </td>
                                <td>{{ $k->keterangan }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</div>

@stop
