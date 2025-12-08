@extends('adminlte::page')

@section('title', 'RAB Type 50')

@section('content_header')
    <h1>RAB Type 50</h1>
@stop

@section('content')

<div class="card p-4">

    {{-- ===================== FILTER AREA ===================== --}}
    <form method="GET" action="{{ route('rab.type50') }}" class="row g-3 mb-4">

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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            {{ $selectedLocation->nama ?? '' }}  
            — Type {{ $selectedType->nama ?? '' }}  
            — Unit {{ $selectedUnit->kode_unit ?? '' }}
        </h4>
        <div>
            <button type="button" class="btn btn-success mr-2" id="btnSaveAll">
                <i class="fas fa-save"></i> Simpan Semua
            </button>
            <a href="{{ route('rab.type50.print', ['type_id' => $type_id, 'unit_id' => $unit_id, 'location_id' => $location_id]) }}" 
               class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> Print / Export
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm" id="rabTable">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Uraian Pekerjaan</th>
                    <th class="text-center" style="width: 70px;">Bahan<br>BAKU</th>
                    <th class="text-center" style="width: 70px;">OUT</th>
                    <th class="text-center" style="width: 100px;">Harga Bahan<br>(Rp)</th>
                    <th class="text-center" style="width: 120px;">Total Harga<br>(Rp)</th>
                    <th class="text-center" style="width: 100px;">Upah</th>
                    <th class="text-center" style="width: 120px;">Borongan</th>
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
                        $catTotalUpah = $categoryItems->sum('upah');
                        $catBorongan = $categoryBorongans->get($category->id);
                        $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
                        $catUntungRugi = $catBoronganValue - $catTotalUpah;
                        
                        // Calculate average progress
                        $catProgress = $categoryItems->count() > 0 ? round($categoryItems->avg('progres')) : 0;
                    @endphp

                    {{-- Category Header Row --}}
                    <tr class="bg-light font-weight-bold">
                        <td class="text-center">{{ $category->kode }}</td>
                        <td colspan="9"><strong>{{ $category->nama }}</strong></td>
                    </tr>

                    {{-- Items in Category --}}
                    @foreach($categoryItems as $item)
                        @php $itemCounter++; @endphp
                        <tr data-item-id="{{ $item->id }}" data-category-id="{{ $category->id }}">
                            <td class="text-center">{{ $itemCounter }}</td>
                            <td>{{ $item->uraian }}</td>
                            <td class="text-center">{{ $item->bahan_baku }}</td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm input-out" 
                                       value="{{ $item->bahan_out }}" 
                                       data-item-id="{{ $item->id }}"
                                       data-harga="{{ $item->harga_bahan }}"
                                       min="0" step="1" style="width: 60px;">
                            </td>
                            <td class="text-right">{{ number_format($item->harga_bahan, 0, ',', '.') }}</td>
                            <td class="text-right total-harga" data-item-id="{{ $item->id }}">
                                {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-right">
                                <input type="number" class="form-control form-control-sm input-upah" 
                                       value="{{ $item->upah }}" 
                                       data-item-id="{{ $item->id }}"
                                       min="0" step="1000" style="width: 90px;">
                            </td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm input-progres" 
                                       value="{{ $item->progres }}" 
                                       data-item-id="{{ $item->id }}"
                                       min="0" max="100" step="1" style="width: 60px;">
                            </td>
                        </tr>
                    @endforeach

                    {{-- Category Summary Row --}}
                    <tr class="bg-info text-white category-summary" data-category-id="{{ $category->id }}">
                        <td colspan="5" class="text-right"><strong>Subtotal {{ $category->nama }}:</strong></td>
                        <td class="text-right cat-total-harga" data-category-id="{{ $category->id }}">
                            <strong>{{ number_format($catTotalHarga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-right cat-total-upah" data-category-id="{{ $category->id }}">
                            <strong>{{ number_format($catTotalUpah, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-right">
                            <input type="number" class="form-control form-control-sm input-borongan" 
                                   value="{{ $catBoronganValue }}" 
                                   data-category-id="{{ $category->id }}"
                                   min="0" step="10000" style="width: 100px;">
                        </td>
                        <td class="text-right cat-untung-rugi {{ $catUntungRugi < 0 ? 'text-danger' : '' }}" 
                            data-category-id="{{ $category->id }}">
                            <strong>{{ number_format($catUntungRugi, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center cat-progress" data-category-id="{{ $category->id }}">
                            <strong>{{ $catProgress }}%</strong>
                        </td>
                    </tr>

                @endforeach

                {{-- Grand Total Row --}}
                <tr class="bg-dark text-white font-weight-bold">
                    <td colspan="5" class="text-right"><strong>JUMLAH TOTAL</strong></td>
                    <td class="text-right" id="grandTotalHarga">
                        <strong>{{ number_format($rabItems->sum('total_harga'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right" id="grandTotalUpah">
                        <strong>{{ number_format($rabItems->sum('upah'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right" id="grandTotalBorongan">
                        <strong>{{ number_format($categoryBorongans->sum('borongan'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right {{ ($categoryBorongans->sum('borongan') - $rabItems->sum('upah')) < 0 ? 'text-danger' : '' }}" id="grandTotalUntungRugi">
                        <strong>{{ number_format($categoryBorongans->sum('borongan') - $rabItems->sum('upah'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center" id="grandTotalProgress">
                        <strong>{{ $rabItems->count() > 0 ? round($rabItems->avg('progres')) : 0 }}%</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endif

@stop

@section('css')
<style>
    .table-sm td, .table-sm th {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
    }
    .input-out, .input-upah, .input-progres, .input-borongan {
        text-align: right;
        padding: 2px 5px;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .category-summary {
        font-weight: bold;
    }
    @media print {
        .no-print { display: none !important; }
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    const typeId = '{{ $type_id }}';
    const unitId = '{{ $unit_id }}';
    const locationId = '{{ $location_id }}';

    // Track modified items
    let modifiedItems = {};
    let modifiedBorongans = {};

    // Handle OUT input change
    $('.input-out').on('change', function() {
        const itemId = $(this).data('item-id');
        const harga = parseFloat($(this).data('harga')) || 0;
        const out = parseFloat($(this).val()) || 0;
        const totalHarga = out * harga;

        // Update display
        $(`.total-harga[data-item-id="${itemId}"]`).text(formatNumber(totalHarga));

        // Track modification
        if (!modifiedItems[itemId]) modifiedItems[itemId] = {};
        modifiedItems[itemId].bahan_out = out;

        // Update category total
        updateCategoryTotals($(this).closest('tr').data('category-id'));
    });

    // Handle Upah input change
    $('.input-upah').on('change', function() {
        const itemId = $(this).data('item-id');
        const upah = parseFloat($(this).val()) || 0;

        // Track modification
        if (!modifiedItems[itemId]) modifiedItems[itemId] = {};
        modifiedItems[itemId].upah = upah;

        // Update category total
        updateCategoryTotals($(this).closest('tr').data('category-id'));
    });

    // Handle Progress input change
    $('.input-progres').on('change', function() {
        const itemId = $(this).data('item-id');
        const progres = parseFloat($(this).val()) || 0;

        // Track modification
        if (!modifiedItems[itemId]) modifiedItems[itemId] = {};
        modifiedItems[itemId].progres = progres;

        // Update category progress
        updateCategoryProgress($(this).closest('tr').data('category-id'));
    });

    // Handle Borongan input change
    $('.input-borongan').on('change', function() {
        const categoryId = $(this).data('category-id');
        const borongan = parseFloat($(this).val()) || 0;

        // Track modification
        modifiedBorongans[categoryId] = borongan;

        // Update untung/rugi
        updateCategoryUntungRugi(categoryId);
        updateGrandTotals();
    });

    // Update category totals
    function updateCategoryTotals(categoryId) {
        let totalHarga = 0;
        let totalUpah = 0;

        $(`tr[data-category-id="${categoryId}"]`).not('.category-summary').each(function() {
            const itemId = $(this).data('item-id');
            const harga = parseFloat($(this).find('.input-out').data('harga')) || 0;
            const out = parseFloat($(this).find('.input-out').val()) || 0;
            const upah = parseFloat($(this).find('.input-upah').val()) || 0;

            totalHarga += out * harga;
            totalUpah += upah;
        });

        $(`.cat-total-harga[data-category-id="${categoryId}"]`).html(`<strong>${formatNumber(totalHarga)}</strong>`);
        $(`.cat-total-upah[data-category-id="${categoryId}"]`).html(`<strong>${formatNumber(totalUpah)}</strong>`);

        updateCategoryUntungRugi(categoryId);
        updateGrandTotals();
    }

    // Update category untung/rugi
    function updateCategoryUntungRugi(categoryId) {
        const totalUpah = parseUnformattedNumber($(`.cat-total-upah[data-category-id="${categoryId}"]`).text());
        const borongan = parseFloat($(`.input-borongan[data-category-id="${categoryId}"]`).val()) || 0;
        const untungRugi = borongan - totalUpah;

        const cell = $(`.cat-untung-rugi[data-category-id="${categoryId}"]`);
        cell.html(`<strong>${formatNumber(untungRugi)}</strong>`);
        
        if (untungRugi < 0) {
            cell.addClass('text-danger').css('color', '#dc3545');
        } else {
            cell.removeClass('text-danger').css('color', '');
        }
    }

    // Update category progress
    function updateCategoryProgress(categoryId) {
        let totalProgress = 0;
        let count = 0;

        $(`tr[data-category-id="${categoryId}"]`).not('.category-summary').each(function() {
            totalProgress += parseFloat($(this).find('.input-progres').val()) || 0;
            count++;
        });

        const avgProgress = count > 0 ? Math.round(totalProgress / count) : 0;
        $(`.cat-progress[data-category-id="${categoryId}"]`).html(`<strong>${avgProgress}%</strong>`);
        
        updateGrandTotals();
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

        $('.cat-total-upah').each(function() {
            grandTotalUpah += parseUnformattedNumber($(this).text());
        });

        $('.input-borongan').each(function() {
            grandTotalBorongan += parseFloat($(this).val()) || 0;
        });

        $('.input-progres').each(function() {
            totalProgress += parseFloat($(this).val()) || 0;
            progressCount++;
        });

        const grandUntungRugi = grandTotalBorongan - grandTotalUpah;
        const avgProgress = progressCount > 0 ? Math.round(totalProgress / progressCount) : 0;

        $('#grandTotalHarga').html(`<strong>${formatNumber(grandTotalHarga)}</strong>`);
        $('#grandTotalUpah').html(`<strong>${formatNumber(grandTotalUpah)}</strong>`);
        $('#grandTotalBorongan').html(`<strong>${formatNumber(grandTotalBorongan)}</strong>`);
        
        const untungRugiCell = $('#grandTotalUntungRugi');
        untungRugiCell.html(`<strong>${formatNumber(grandUntungRugi)}</strong>`);
        if (grandUntungRugi < 0) {
            untungRugiCell.addClass('text-danger').css('color', '#dc3545');
        } else {
            untungRugiCell.removeClass('text-danger').css('color', '');
        }

        $('#grandTotalProgress').html(`<strong>${avgProgress}%</strong>`);
    }

    // Save all changes
    $('#btnSaveAll').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Prepare items data
        const items = [];
        for (const itemId in modifiedItems) {
            items.push({
                id: itemId,
                ...modifiedItems[itemId]
            });
        }

        // Also collect all items with current values (not just modified)
        $('tr[data-item-id]').each(function() {
            const itemId = $(this).data('item-id');
            if (!modifiedItems[itemId]) {
                items.push({
                    id: itemId,
                    bahan_out: parseFloat($(this).find('.input-out').val()) || 0,
                    upah: parseFloat($(this).find('.input-upah').val()) || 0,
                    progres: parseFloat($(this).find('.input-progres').val()) || 0
                });
            }
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

        // Save borongans
        const boronganPromises = Object.keys(modifiedBorongans).map(categoryId => {
            return $.ajax({
                url: '{{ route("rab.update-borongan") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: categoryId,
                    type_id: typeId,
                    unit_id: unitId,
                    location_id: locationId,
                    borongan: modifiedBorongans[categoryId]
                }
            });
        });

        // Also save all borongans (not just modified)
        $('.input-borongan').each(function() {
            const categoryId = $(this).data('category-id');
            if (!modifiedBorongans[categoryId]) {
                boronganPromises.push($.ajax({
                    url: '{{ route("rab.update-borongan") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        category_id: categoryId,
                        type_id: typeId,
                        unit_id: unitId,
                        location_id: locationId,
                        borongan: parseFloat($(this).val()) || 0
                    }
                }));
            }
        });

        Promise.all([itemsPromise, ...boronganPromises])
            .then(() => {
                alert('Data berhasil disimpan!');
                modifiedItems = {};
                modifiedBorongans = {};
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan data');
            })
            .finally(() => {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Semua');
            });
    });

    // Helper functions
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(Math.round(num));
    }

    function parseUnformattedNumber(str) {
        return parseFloat(str.replace(/[^\d-]/g, '')) || 0;
    }
});
</script>
@stop
