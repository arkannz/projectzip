@extends('adminlte::page')

@section('title', 'RAB')

@section('content_header')
    <h1>RAB</h1>
@stop

@section('content')

<div class="card p-4">

    {{-- ===================== FILTER AREA ===================== --}}
    <form method="GET" action="{{ route('rab.index') }}" class="row g-3 mb-4">

        <div class="col-md-4">
            <label>Type Rumah</label>
            <select name="type_id" class="form-control">
                <option value="">Pilih Type</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}" {{ $type_id == $t->id ? 'selected' : '' }}>
                        {{ $t->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Lokasi Rumah</label>
            <select name="location_id" class="form-control">
                <option value="">Pilih Lokasi</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $location_id == $loc->id ? 'selected' : '' }}>
                        {{ $loc->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Unit Rumah</label>
            <select name="unit_id" class="form-control">
                <option value="">Pilih Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>
                        {{ $u->location->nama }} - Unit {{ $u->kode_unit }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 d-flex justify-content-center mt-3 mb-2">
            <button class="btn btn-primary px-5 py-2" style="font-weight: 600;">
                Tampilkan
            </button>
        </div>
    
    </form>

</div>


{{-- ===================== TAMPILKAN TABEL HANYA JIKA LENGKAP ===================== --}}
@if(!empty($type_id) && !empty($location_id) && !empty($unit_id))

<div class="card p-4 mt-3">

    <h4 class="mb-3">
        {{ $locations->find($location_id)->nama }}  
        — Type {{ $types->find($type_id)->nama }}  
        — Unit {{ $units->find($unit_id)->kode_unit }}
    </h4>

    <table class="table table-bordered table-striped">
        <thead class="bg-light">
            <tr>
                <th>No</th>
                <th>Uraian</th>
                <th>Bahan Baku</th>
                <th>Bahan Out</th>
                <th>Harga (Rp)</th>
                <th>Total Harga (Rp)</th>
                <th>Upah</th>
                <th>Borongan</th>
                <th>Untung/Rugi</th>
                <th>Progres</th>
            </tr>
        </thead>

        <tbody>
            @foreach($rabs as $i => $r)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r->uraian }}</td>
                <td>{{ $r->bahan_baku }}</td>
                <td>{{ $r->bahan_out }}</td>
                <td>{{ number_format($r->harga_bahan) }}</td>
                <td>{{ number_format($r->total_harga) }}</td>
                <td>{{ number_format($r->upah) }}</td>
                <td>{{ number_format($r->borongan) }}</td>
                <td>{{ number_format($r->untung_rugi) }}</td>
                <td>{{ $r->progres }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a class="btn btn-success mt-3">+ Input Data</a>

</div>

@endif

@stop
