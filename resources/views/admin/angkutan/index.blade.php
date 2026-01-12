@extends('adminlte::page')

@section('title', 'Data Angkutan')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>REKAPAN MINGGUAN PASIR-BATU</h1>
    <div>
        <a href="{{ route('inventory.angkutan.print') }}" class="btn btn-info" target="_blank">
            🖨️ Print / Export
        </a>
    </div>
</div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Data Angkutan</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('inventory.angkutan.store') }}" id="formAngkutan">
            @csrf
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Hari/Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control" placeholder="PB/JK" maxlength="10" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Nama lokasi" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Angkutan</label>
                        <select name="angkutan" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="Pasir">Pasir</option>
                            <option value="Batu">Batu</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Pangkalan</label>
                        <input type="text" name="pangkalan" class="form-control" placeholder="Nama pangkalan" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Tambah</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Data Angkutan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="tableAngkutan">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th class="text-center" style="width: 120px;">HARI/TGL</th>
                        <th class="text-center" style="width: 80px;">KODE</th>
                        <th>LOKASI</th>
                        <th class="text-center" style="width: 100px;">ANGKUTAN</th>
                        <th class="text-center" style="width: 80px;">JUMLAH</th>
                        <th>PANGKALAN</th>
                        <th class="text-center" style="width: 100px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $totalPasir = 0;
                        $totalBatu = 0;
                        $groupedByDate = $angkatans->groupBy(function($item) {
                            return $item->tanggal->format('Y-m-d');
                        });
                    @endphp
                    @forelse($groupedByDate as $tanggal => $items)
                        @php
                            $hari = \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
                            $tanggalFormatted = \Carbon\Carbon::parse($tanggal)->format('d-m-Y');
                            $totalPerHari = 0;
                            $firstItem = true;
                        @endphp
                        @foreach($items as $angkutan)
                            <tr data-id="{{ $angkutan->id }}" class="row-angkutan">
                                <td class="text-center">
                                    @if($firstItem)
                                        {{ $no++ }}
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                                <td>
                                    @if($firstItem)
                                        <strong>{{ ucfirst($hari) }}<br>{{ $tanggalFormatted }}</strong>
                                        @php $firstItem = false; @endphp
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                                <td class="text-center">{{ $angkutan->kode }}</td>
                                <td>{{ $angkutan->lokasi }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $angkutan->angkutan === 'Batu' ? 'badge-danger' : 'badge-primary' }}">
                                        {{ $angkutan->angkutan }}
                                    </span>
                                </td>
                                <td class="text-center">{{ number_format($angkutan->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $angkutan->pangkalan }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $angkutan->id }}" 
                                        data-tanggal="{{ $angkutan->tanggal->format('Y-m-d') }}"
                                        data-kode="{{ $angkutan->kode }}"
                                        data-lokasi="{{ $angkutan->lokasi }}"
                                        data-angkutan="{{ $angkutan->angkutan }}"
                                        data-jumlah="{{ $angkutan->jumlah }}"
                                        data-pangkalan="{{ $angkutan->pangkalan }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('inventory.angkutan.destroy', $angkutan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @php
                                $totalPerHari += $angkutan->jumlah;
                                if($angkutan->angkutan === 'Pasir') {
                                    $totalPasir += $angkutan->jumlah;
                                } else {
                                    $totalBatu += $angkutan->jumlah;
                                }
                            @endphp
                        @endforeach
                        {{-- Total per hari --}}
                        <tr class="table-info font-weight-bold">
                            <td colspan="5" class="text-right">TOTAL</td>
                            <td class="text-center">{{ number_format($totalPerHari, 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data</td>
                        </tr>
                    @endforelse
                    
                    @if($angkatans->count() > 0)
                        {{-- Total Keseluruhan --}}
                        <tr class="table-success font-weight-bold">
                            <td colspan="4" class="text-right">TOTAL KESELURUHAN</td>
                            <td class="text-center">Pasir</td>
                            <td class="text-center">{{ number_format($totalPasir, 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="table-success font-weight-bold">
                            <td colspan="4" class="text-right"></td>
                            <td class="text-center">Batu</td>
                            <td class="text-center">{{ number_format($totalBatu, 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditAngkutan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Angkutan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="formEditAngkutan">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hari/Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" name="kode" id="edit_kode" class="form-control" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Angkutan</label>
                        <select name="angkutan" id="edit_angkutan" class="form-control" required>
                            <option value="Pasir">Pasir</option>
                            <option value="Batu">Batu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" id="edit_jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Pangkalan</label>
                        <input type="text" name="pangkalan" id="edit_pangkalan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
$(document).ready(function() {
    // Edit button click
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        $('#formEditAngkutan').attr('action', '{{ url("inventory/angkutan") }}/' + id);
        $('#edit_tanggal').val($(this).data('tanggal'));
        $('#edit_kode').val($(this).data('kode'));
        $('#edit_lokasi').val($(this).data('lokasi'));
        $('#edit_angkutan').val($(this).data('angkutan'));
        $('#edit_jumlah').val($(this).data('jumlah'));
        $('#edit_pangkalan').val($(this).data('pangkalan'));
        $('#modalEditAngkutan').modal('show');
    });
});
</script>
@stop

