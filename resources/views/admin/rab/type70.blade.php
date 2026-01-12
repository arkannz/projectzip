@extends('adminlte::page')

@section('title', 'RAB Type 70')

@section('content_header')
    <h1>RAB Type 70</h1>
@stop

@section('content')

<div class="card p-4">

    {{-- ===================== FILTER AREA ===================== --}}
    <form method="GET" action="{{ route('rab.type70') }}" class="row g-3 mb-4">

        <div class="col-md-4">
            <label>Type Rumah</label>
            <input type="text" class="form-control" value="{{ $fixedType->nama }}" disabled style="background-color: #e9ecef; cursor: not-allowed;">
            <input type="hidden" name="type_id" value="{{ $fixedType->id }}">
        </div>

        <div class="col-md-4">
            <label>Lokasi Rumah</label>
            <select name="location_id" class="form-control" id="selectLocationRAB">
                <option value="">Pilih Lokasi</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $location_id == $loc->id ? 'selected' : '' }}>
                        {{ $loc->nama }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#modalTambahLokasi">
                + Tambah Lokasi Baru
            </button>
        </div>

        <div class="col-md-4">
            <label>Unit Rumah</label>
            <select name="unit_id" class="form-control" id="selectUnitRAB">
                <option value="">Pilih Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>
                        {{ $u->location->nama }} - Unit {{ $u->kode_unit }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#modalTambahUnit">
                + Tambah Unit Baru
            </button>
        </div>

        <div class="col-12 d-flex justify-content-center mt-3 mb-2">
            <button class="btn btn-primary px-5 py-2" style="font-weight: 600;">
                Tampilkan
            </button>
        </div>
    
    </form>

</div>


{{-- ===================== TAMPILKAN TABEL HANYA JIKA LOKASI DAN UNIT DIPILIH ===================== --}}
@if(!empty($location_id) && !empty($unit_id))

<div class="card p-4 mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            {{ $selectedLocation->nama ?? '' }}  
            — Type {{ $selectedType->nama ?? '' }}  
            — Unit {{ $selectedUnit->kode_unit ?? '' }}
        </h4>
        <div>
            <button type="button" class="btn btn-warning mr-2" id="btnRefreshPrices" title="Refresh harga dari inventory">
                <i class="fas fa-sync"></i> Refresh Harga
            </button>
            <button type="button" class="btn btn-danger mr-2" id="btnRegenerate" title="Hapus dan generate ulang RAB">
                <i class="fas fa-redo"></i> Regenerate
            </button>
            <button type="button" class="btn btn-success mr-2" id="btnSaveAll">
                <i class="fas fa-save"></i> Simpan Semua
            </button>
            <a href="{{ route('rab.type70.print', ['type_id' => $type_id, 'unit_id' => $unit_id, 'location_id' => $location_id]) }}" 
               class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> Print / Export
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="rabTable">
            <thead class="thead-light-gray">
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Uraian Pekerjaan</th>
                    <th class="text-center" style="width: 70px;">Bahan<br>BAKU</th>
                    <th class="text-center" style="width: 70px;">OUT</th>
                    <th class="text-center" style="width: 100px;">Harga Bahan<br>(Rp)</th>
                    <th class="text-center" style="width: 120px;">Total Harga<br>(Rp)</th>
                    <th class="text-center" style="width: 110px;">Upah</th>
                    <th class="text-center" style="width: 110px;">Borongan</th>
                    <th class="text-center" style="width: 120px;">Untung/Rugi</th>
                    <th class="text-center" style="width: 80px;">Progress</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $currentCategoryId = null;
                    $categoryCounter = 0;
                    $itemCounter = 0;
                    $categoryTotals = [];
                @endphp

                @foreach($categories as $category)
                    @php
                        $categoryItems = $rabItems->where('rab_category_id', $category->id);
                        if ($categoryItems->isEmpty()) continue;
                        
                        $categoryCounter++;
                        $itemCounter = 0;
                        
                        // Calculate category totals
                        $catTotalHarga = $categoryItems->sum('total_harga');
                        $catBorongan = $categoryBorongans->get($category->id);
                        $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
                        $catUpahValue = $catBorongan ? $catBorongan->upah : 0;
                        $catProgressValue = $catBorongan ? $catBorongan->progress : 0;
                        $catUntungRugi = $catBoronganValue - $catUpahValue;
                    @endphp

                    {{-- Category Header Row --}}
                    <tr class="category-header font-weight-bold">
                        <td class="text-center">{{ $category->kode }}</td>
                        <td colspan="9"><strong>{{ $category->nama }}</strong></td>
                    </tr>

                    {{-- Items in Category --}}
                    @foreach($categoryItems as $item)
                        @php $itemCounter++; @endphp
                        <tr class="item-row" data-item-id="{{ $item->id }}" data-category-id="{{ $category->id }}">
                            <td class="text-center">{{ $itemCounter }}</td>
                            <td>{{ $item->uraian }}</td>
                            <td class="text-center">{{ number_format($item->bahan_baku, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm input-out" 
                                       value="{{ (int)$item->bahan_out }}" 
                                       data-item-id="{{ $item->id }}"
                                       data-harga="{{ $item->harga_bahan }}"
                                       min="0" step="1" style="width: 60px;">
                            </td>
                            <td class="text-right">Rp {{ number_format($item->harga_bahan, 0, ',', '.') }}</td>
                            <td class="text-right total-harga" data-item-id="{{ $item->id }}">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        </tr>
                    @endforeach

                    {{-- Category Summary Row with Inputs --}}
                    <tr class="category-summary-row category-summary" data-category-id="{{ $category->id }}">
                        <td colspan="5" class="text-center"><strong>Subtotal {{ $category->nama }}:</strong></td>
                        <td class="text-right cat-total-harga" data-category-id="{{ $category->id }}">
                            <strong>Rp {{ number_format($catTotalHarga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm input-upah-cat" 
                                   value="{{ $catUpahValue }}" 
                                   data-category-id="{{ $category->id }}"
                                   min="0" step="10000" style="width: 100px;">
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm input-borongan" 
                                   value="{{ $catBoronganValue }}" 
                                   data-category-id="{{ $category->id }}"
                                   min="0" step="10000" style="width: 100px;">
                        </td>
                        <td class="text-center cat-untung-rugi {{ $catUntungRugi < 0 ? 'text-danger' : '' }}" 
                            data-category-id="{{ $category->id }}">
                            <strong>Rp {{ number_format($catUntungRugi, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm" style="width: 85px; display: inline-flex;">
                                <input type="number" class="form-control form-control-sm input-progress-cat" 
                                       value="{{ $catProgressValue }}" 
                                       data-category-id="{{ $category->id }}"
                                       min="0" max="100" step="1">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="padding: 2px 5px;">%</span>
                                </div>
                            </div>
                        </td>
                    </tr>

                @endforeach

                {{-- Grand Total Row --}}
                <tr class="grand-total-row font-weight-bold">
                    <td colspan="5" class="text-center"><strong>JUMLAH TOTAL</strong></td>
                    <td class="text-right" id="grandTotalHarga">
                        <strong>Rp {{ number_format($rabItems->sum('total_harga'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right" id="grandTotalUpah">
                        <strong>Rp {{ number_format($categoryBorongans->sum('upah'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right" id="grandTotalBorongan">
                        <strong>Rp {{ number_format($categoryBorongans->sum('borongan'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right {{ ($categoryBorongans->sum('borongan') - $categoryBorongans->sum('upah')) < 0 ? 'text-danger' : '' }}" id="grandTotalUntungRugi">
                        <strong>Rp {{ number_format($categoryBorongans->sum('borongan') - $categoryBorongans->sum('upah'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center" id="grandTotalProgress">
                        <strong>{{ $rabItems->count() > 0 ? round($rabItems->avg('progres')) : 0 }}%</strong>
                    </td>
                </tr>

                {{-- Total Harga Bahan + Total HK Row --}}
                @php
                    $totalHargaBahan = $rabItems->sum('total_harga');
                    $totalHK = $categoryBorongans->sum('upah');
                    $grandTotalBahanHK = $totalHargaBahan + $totalHK;
                @endphp
                <tr class="total-bahan-hk-row font-weight-bold">
                    <td colspan="5" class="text-center"><strong>( TOTAL HARGA BAHAN + TOTAL HK )</strong></td>
                    <td colspan="5" class="text-center" id="grandTotalBahanHK">
                        <strong>Rp {{ number_format($grandTotalBahanHK, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endif

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
                    <input type="text" class="form-control" value="{{ $fixedType->nama }}" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                    <input type="hidden" id="selectTypeUnit" value="{{ $fixedType->id }}">
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

@stop

@section('css')
<style>
    .table-sm td, .table-sm th {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
    }
    .input-out, .input-upah-cat, .input-progress-cat, .input-borongan {
        text-align: right;
        padding: 2px 5px;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .category-summary {
        font-weight: bold;
    }
    
    /* Header tabel - Abu-abu Muda dengan tulisan Hitam */
    .thead-light-gray {
        background-color: #d5d8dc !important;
        color: #000 !important;
    }
    .thead-light-gray th {
        background-color: #d5d8dc !important;
        color: #000 !important;
        border-color: #b3b6b7 !important;
        font-weight: bold;
    }
    
    /* Category Header - Biru Terang dengan tulisan Hitam */
    .category-header {
        background-color: #3498db !important;
        color: #000 !important;
    }
    .category-header td {
        background-color: #3498db !important;
        color: #000 !important;
    }
    .category-header strong {
        color: #000 !important;
    }
    
    /* Item Rows */
    .item-row {
        background-color: #fff !important;
    }
    .item-row td {
        background-color: #fff !important;
    }
    
    /* Subtotal Row - Biru Muda */
    .category-summary-row {
        background-color: #aed6f1 !important;
        color: #000 !important;
    }
    .category-summary-row td {
        background-color: #aed6f1 !important;
        color: #000 !important;
    }
    
    /* Grand Total - Hijau Pastel Soft */
    .grand-total-row {
        background-color: #a5d6a7 !important;
        color: #000 !important;
    }
    .grand-total-row td {
        background-color: #a5d6a7 !important;
        color: #000 !important;
    }
    
    /* Total Bahan + HK - Orange Pastel Soft */
    .total-bahan-hk-row {
        background-color: #ffccbc !important;
        color: #000 !important;
    }
    .total-bahan-hk-row td {
        background-color: #ffccbc !important;
        color: #000 !important;
    }
    
    @media print {
        .no-print { display: none !important; }
    }
</style>
@stop

@section('js')
<script>
// Pastikan jQuery ter-load sebelum menjalankan script
(function() {
    // Fungsi untuk menjalankan script setelah jQuery ter-load
    function initScript() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded! Retrying...');
            setTimeout(initScript, 100);
            return;
        }
        
        console.log('jQuery loaded, version:', jQuery.fn.jquery);
        
        // Jalankan script utama
        jQuery(document).ready(function($) {
    const typeId = '{{ $type_id }}';
    const unitId = '{{ $unit_id }}';
    const locationId = '{{ $location_id }}';

    // Track modified items and categories
    let modifiedItems = {};
    let modifiedCategories = {};

    // Handle OUT input change (per item)
    $('.input-out').on('change', function() {
        const itemId = $(this).data('item-id');
        const harga = parseFloat($(this).data('harga')) || 0;
        const out = parseFloat($(this).val()) || 0;
        const totalHarga = out * harga;

        // Update display
        $(`.total-harga[data-item-id="${itemId}"]`).text(formatRupiah(totalHarga));

        // Track modification
        if (!modifiedItems[itemId]) modifiedItems[itemId] = {};
        modifiedItems[itemId].bahan_out = out;

        // Update category total
        updateCategoryTotals($(this).closest('tr').data('category-id'));
    });

    // Handle Upah input change (per category)
    $('.input-upah-cat').on('change', function() {
        const categoryId = $(this).data('category-id');
        const upah = parseFloat($(this).val()) || 0;

        // Track modification
        if (!modifiedCategories[categoryId]) modifiedCategories[categoryId] = {};
        modifiedCategories[categoryId].upah = upah;

        // Update displays
        $(`.cat-total-upah[data-category-id="${categoryId}"]`).html(`<strong>${formatNumber(upah)}</strong>`);
        updateCategoryUntungRugi(categoryId);
        updateGrandTotals();
    });

    // Handle Borongan input change (per category)
    $('.input-borongan').on('change', function() {
        const categoryId = $(this).data('category-id');
        const borongan = parseFloat($(this).val()) || 0;

        // Track modification
        if (!modifiedCategories[categoryId]) modifiedCategories[categoryId] = {};
        modifiedCategories[categoryId].borongan = borongan;

        // Update displays
        $(`.cat-total-borongan[data-category-id="${categoryId}"]`).html(`<strong>${formatNumber(borongan)}</strong>`);
        updateCategoryUntungRugi(categoryId);
        updateGrandTotals();
    });

    // Handle Progress input change (per category)
    $('.input-progress-cat').on('change', function() {
        const categoryId = $(this).data('category-id');
        const progress = parseFloat($(this).val()) || 0;

        // Track modification
        if (!modifiedCategories[categoryId]) modifiedCategories[categoryId] = {};
        modifiedCategories[categoryId].progress = progress;

        // Update displays
        $(`.cat-total-progress[data-category-id="${categoryId}"]`).html(`<strong>${progress}%</strong>`);
        updateGrandTotals();
    });

    // Update category totals (Total Harga only from items)
    function updateCategoryTotals(categoryId) {
        let totalHarga = 0;

        $(`.item-row[data-category-id="${categoryId}"]`).each(function() {
            const harga = parseFloat($(this).find('.input-out').data('harga')) || 0;
            const out = parseFloat($(this).find('.input-out').val()) || 0;
            totalHarga += out * harga;
        });

        $(`.cat-total-harga[data-category-id="${categoryId}"]`).html(`<strong>${formatRupiah(totalHarga)}</strong>`);
        updateGrandTotals();
    }

    // Update category untung/rugi
    function updateCategoryUntungRugi(categoryId) {
        const upah = parseFloat($(`.input-upah-cat[data-category-id="${categoryId}"]`).val()) || 0;
        const borongan = parseFloat($(`.input-borongan[data-category-id="${categoryId}"]`).val()) || 0;
        const untungRugi = borongan - upah;

        // Update subtotal row
        const cell = $(`.cat-untung-rugi[data-category-id="${categoryId}"]`);
        cell.html(`<strong>${formatRupiah(untungRugi)}</strong>`);
        if (untungRugi < 0) {
            cell.addClass('text-danger').css('color', '#dc3545');
        } else {
            cell.removeClass('text-danger').css('color', '');
        }
    }

    // Update grand totals
    function updateGrandTotals() {
        let grandTotalHarga = 0;
        let grandTotalUpah = 0;
        let grandTotalBorongan = 0;
        let totalProgress = 0;
        let progressCount = 0;

        $('.cat-total-harga').each(function() {
            grandTotalHarga += parseUnformattedNumber($(this).text());
        });

        $('.input-upah-cat').each(function() {
            grandTotalUpah += parseFloat($(this).val()) || 0;
        });

        $('.input-borongan').each(function() {
            grandTotalBorongan += parseFloat($(this).val()) || 0;
        });

        $('.input-progress-cat').each(function() {
            totalProgress += parseFloat($(this).val()) || 0;
            progressCount++;
        });

        const grandUntungRugi = grandTotalBorongan - grandTotalUpah;
        const avgProgress = progressCount > 0 ? Math.round(totalProgress / progressCount) : 0;

        $('#grandTotalHarga').html(`<strong>${formatRupiah(grandTotalHarga)}</strong>`);
        $('#grandTotalUpah').html(`<strong>${formatRupiah(grandTotalUpah)}</strong>`);
        $('#grandTotalBorongan').html(`<strong>${formatRupiah(grandTotalBorongan)}</strong>`);
        
        const untungRugiCell = $('#grandTotalUntungRugi');
        untungRugiCell.html(`<strong>${formatRupiah(grandUntungRugi)}</strong>`);
        if (grandUntungRugi < 0) {
            untungRugiCell.addClass('text-danger').css('color', '#dc3545');
        } else {
            untungRugiCell.removeClass('text-danger').css('color', '');
        }

        $('#grandTotalProgress').html(`<strong>${avgProgress}%</strong>`);
        
        // Update Total Harga Bahan + Total HK
        const grandTotalBahanHK = grandTotalHarga + grandTotalUpah;
        $('#grandTotalBahanHK').html(`<strong>${formatRupiah(grandTotalBahanHK)}</strong>`);
    }

    // Save all changes
    $('#btnSaveAll').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Prepare items data (only OUT values now)
        const items = [];
        $('.item-row').each(function() {
            const itemId = $(this).data('item-id');
            items.push({
                id: itemId,
                bahan_out: parseFloat($(this).find('.input-out').val()) || 0
            });
        });

        // Save items
        const itemsPromise = items.length > 0 ? 
            $.ajax({
                url: '{{ route("rab.batch-update") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: items
                }
            }) : Promise.resolve();

        // Save category data (upah, borongan, progress)
        const categoryPromises = [];
        $('.category-summary').each(function() {
            const categoryId = $(this).data('category-id');
            const upah = parseFloat($(this).find('.input-upah-cat').val()) || 0;
            const borongan = parseFloat($(this).find('.input-borongan').val()) || 0;
            const progress = parseFloat($(this).find('.input-progress-cat').val()) || 0;

            categoryPromises.push($.ajax({
                url: '{{ route("rab.update-borongan") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId,
                    type_id: typeId,
                    unit_id: unitId,
                    location_id: locationId,
                    upah: upah,
                    borongan: borongan,
                    progress: progress
                }
            }));
        });

        Promise.all([itemsPromise, ...categoryPromises])
            .then(() => {
                alert('Data berhasil disimpan!');
                modifiedItems = {};
                modifiedCategories = {};
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan data');
            })
            .finally(() => {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Semua');
            });
    });

    // Refresh Prices from Inventory
    $('#btnRefreshPrices').on('click', function() {
        if (!confirm('Refresh harga bahan dari inventory? Data yang sudah diinput (OUT, Upah, Progress) tidak akan berubah.')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');

        $.ajax({
            url: '{{ route("rab.refresh-prices") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type_id: typeId,
                unit_id: unitId,
                location_id: locationId
            }
        })
        .then(response => {
            alert(response.message);
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat refresh harga');
        })
        .finally(() => {
            btn.prop('disabled', false).html('<i class="fas fa-sync"></i> Refresh Harga');
        });
    });

    // Regenerate RAB
    $('#btnRegenerate').on('click', function() {
        if (!confirm('PERINGATAN: Ini akan MENGHAPUS semua data RAB yang sudah ada dan membuat ulang dari template.\n\nSemua data OUT, Upah, Progress akan HILANG!\n\nLanjutkan?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Regenerating...');

        $.ajax({
            url: '{{ route("rab.regenerate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type_id: typeId,
                unit_id: unitId,
                location_id: locationId
            }
        })
        .then(response => {
            alert(response.message);
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat regenerate RAB');
        })
        .finally(() => {
            btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Regenerate');
        });
    });

            // Helper functions
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(Math.round(num));
            }
            
            function formatRupiah(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
            }

            function parseUnformattedNumber(str) {
                return parseFloat(str.replace(/[^\d-]/g, '')) || 0;
            }

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
                    data: { 
                        _token: '{{ csrf_token() }}',
                        nama: namaLokasi 
                    },
                    success: function(response) {
                        $('#selectLocationRAB').append(
                            '<option value="' + response.id + '">' + response.nama + '</option>'
                        );
                        $('#selectLokasiUnit').append(
                            '<option value="' + response.id + '">' + response.nama + '</option>'
                        );
                        $('#selectLocationRAB').val(response.id);
                        $('#inputNamaLokasi').val('');
                        $('#modalTambahLokasi').modal('hide');
                        alert('Lokasi "' + response.nama + '" berhasil ditambahkan!');
                    },
                    error: function(xhr) {
                        alert('Gagal menambahkan lokasi. Silakan coba lagi.');
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
                if (kodeUnit === '') {
                    alert('Kode unit tidak boleh kosong!');
                    return;
                }

                $.ajax({
                    url: '{{ route("inventory.addUnit") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        location_id: locationId,
                        type_id: typeId,
                        kode_unit: kodeUnit
                    },
                    success: function(response) {
                        location.reload();
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

            $('#modalTambahUnit').on('hidden.bs.modal', function() {
                $('#selectLokasiUnit').val('');
                $('#inputKodeUnit').val('');
            });
        });
    }
    
    // Coba jalankan langsung, jika jQuery belum ter-load akan retry
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScript);
    } else {
        initScript();
    }
})();
</script>
@stop