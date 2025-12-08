<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB Type {{ $selectedType->nama ?? '50' }} - {{ $selectedLocation->nama ?? '' }} - Unit {{ $selectedUnit->kode_unit ?? '' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            background: #fff;
            color: #000;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm 15mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            margin-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .header-left {
            width: 50%;
        }

        .header-right {
            width: 50%;
            text-align: right;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: middle;
        }

        .main-table thead th {
            background-color: #e3f2fd;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }

        .main-table .category-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .main-table .category-row td {
            padding: 3px 5px;
        }

        .main-table .summary-row {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        .main-table .grand-total-row {
            background-color: #bbdefb;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .text-danger { color: #dc3545; }
        
        .col-no { width: 25px; }
        .col-uraian { width: 150px; }
        .col-baku { width: 45px; }
        .col-out { width: 40px; }
        .col-harga { width: 70px; }
        .col-total { width: 80px; }
        .col-upah { width: 70px; }
        .col-borongan { width: 80px; }
        .col-untung { width: 80px; }
        .col-progress { width: 45px; }

        .page-number {
            position: absolute;
            top: 10mm;
            right: 15mm;
            font-size: 10px;
        }

        @media print {
            body {
                background: white;
            }
            
            .page {
                width: 100%;
                padding: 5mm 10mm;
                margin: 0;
                page-break-after: auto;
            }

            .no-print {
                display: none !important;
            }

            .main-table th,
            .main-table td {
                font-size: 8px;
                padding: 1px 3px;
            }

            @page {
                size: A4;
                margin: 5mm;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #0056b3;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            right: 120px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            z-index: 1000;
        }

        .back-btn:hover {
            background: #5a6268;
            color: white;
        }
    </style>
</head>
<body>
    <a href="{{ route('rab.type50', ['type_id' => $selectedType->id ?? '', 'unit_id' => $selectedUnit->id ?? '', 'location_id' => $selectedLocation->id ?? '']) }}" class="back-btn no-print">← Kembali</a>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

    <div class="page">
        {{-- Header --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <strong>LOKASI:</strong> {{ $selectedLocation->nama ?? '-' }}
                    </td>
                    <td class="header-right">
                        <strong>TYPE:</strong> {{ $selectedType->nama ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Main Table --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th class="col-no" rowspan="2">No</th>
                    <th class="col-uraian" rowspan="2">URAIAN PEKERJAAN</th>
                    <th colspan="2">BAHAN</th>
                    <th class="col-harga" rowspan="2">HARGA BAHAN<br>(Rp)</th>
                    <th class="col-total" rowspan="2">TOTAL HARGA<br>(Rp)</th>
                    <th class="col-upah" rowspan="2">UPAH</th>
                    <th class="col-borongan" rowspan="2">BORONGAN</th>
                    <th class="col-untung" rowspan="2">UNTUNG/RUGI</th>
                    <th class="col-progress" rowspan="2">PROGRES</th>
                </tr>
                <tr>
                    <th class="col-baku">BAKU</th>
                    <th class="col-out">OUT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalHarga = 0;
                    $grandTotalUpah = 0;
                    $grandTotalBorongan = 0;
                    $grandUntungRugi = 0;
                    $allProgress = [];
                @endphp

                @foreach($categories as $category)
                    @php
                        $categoryItems = $rabItems->where('rab_category_id', $category->id);
                        if ($categoryItems->isEmpty()) continue;
                        
                        // Calculate category totals
                        $catTotalHarga = $categoryItems->sum('total_harga');
                        $catTotalUpah = $categoryItems->sum('upah');
                        $catBorongan = $categoryBorongans->get($category->id);
                        $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
                        $catUntungRugi = $catBoronganValue - $catTotalUpah;
                        $catProgress = $categoryItems->count() > 0 ? round($categoryItems->avg('progres')) : 0;
                        
                        // Add to grand totals
                        $grandTotalHarga += $catTotalHarga;
                        $grandTotalUpah += $catTotalUpah;
                        $grandTotalBorongan += $catBoronganValue;
                        
                        foreach($categoryItems as $item) {
                            $allProgress[] = $item->progres;
                        }
                        
                        $itemCounter = 0;
                    @endphp

                    {{-- Category Header --}}
                    <tr class="category-row">
                        <td class="text-center">{{ $category->kode }}</td>
                        <td colspan="9"><strong>{{ strtoupper($category->nama) }}</strong></td>
                    </tr>

                    {{-- Category Items --}}
                    @foreach($categoryItems as $item)
                        @php $itemCounter++; @endphp
                        <tr>
                            <td class="text-center">{{ $itemCounter }}</td>
                            <td>{{ $item->uraian }}</td>
                            <td class="text-center">{{ $item->bahan_baku }}</td>
                            <td class="text-center">{{ $item->bahan_out }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_bahan, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $item->upah > 0 ? 'Rp ' . number_format($item->upah, 0, ',', '.') : '-' }}</td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-center">{{ $item->progres > 0 ? $item->progres . '%' : '-' }}</td>
                        </tr>
                    @endforeach

                    {{-- Category Summary --}}
                    <tr class="summary-row">
                        <td colspan="5" class="text-right">Subtotal:</td>
                        <td class="text-right">Rp {{ number_format($catTotalHarga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catTotalUpah, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catBoronganValue, 0, ',', '.') }}</td>
                        <td class="text-right {{ $catUntungRugi < 0 ? 'text-danger' : '' }}">
                            Rp {{ number_format($catUntungRugi, 0, ',', '.') }}
                        </td>
                        <td class="text-center">{{ $catProgress }}%</td>
                    </tr>
                @endforeach

                {{-- Grand Total --}}
                @php
                    $grandUntungRugi = $grandTotalBorongan - $grandTotalUpah;
                    $avgProgress = count($allProgress) > 0 ? round(array_sum($allProgress) / count($allProgress)) : 0;
                @endphp
                <tr class="grand-total-row">
                    <td colspan="5" class="text-right"><strong>JUMLAH TOTAL</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalHarga, 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalUpah, 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalBorongan, 0, ',', '.') }}</strong></td>
                    <td class="text-right {{ $grandUntungRugi < 0 ? 'text-danger' : '' }}">
                        <strong>Rp {{ number_format($grandUntungRugi, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center"><strong>{{ $avgProgress }}%</strong></td>
                </tr>

                {{-- Breakdown Row --}}
                <tr class="grand-total-row">
                    <td colspan="5" class="text-right"><strong>(TOTAL HARGA BAHAN + TOTAL HK)</strong></td>
                    <td class="text-right" colspan="2">
                        <strong>Rp {{ number_format($grandTotalHarga + $grandTotalUpah, 0, ',', '.') }}</strong>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>

        {{-- Footer Info --}}
        <div style="margin-top: 20px; font-size: 9px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Unit:</strong> {{ $selectedUnit->kode_unit ?? '-' }}</p>
                        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <p><strong>Keterangan:</strong></p>
                        <p style="color: #dc3545;">* Warna merah = Rugi</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
