<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAB Type {{ $selectedType->nama ?? '55' }} - {{ $selectedLocation->nama ?? '' }} - Unit {{ $selectedUnit->kode_unit ?? '' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: Rockwell, sans-serif;
            font-size: 10px;
            font-weight: semi bold;
            line-height: 1.5;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .page {
            width: 8.5in;
            min-height: 13in;
            padding: 2mm 1mm;
            margin: 0;
            background: white;
        }

        .header {
            margin-bottom: 5px;
            padding: 2mm 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            font-weight: bold;
        }

        .header-table td {
            padding: 2px 3px;
            vertical-align: middle;
        }

        .header-label {
            font-weight: bold;
            display: inline-block;
            min-width: 50px;
        }

        .header-left {
            width: 50%;
            text-align: left;
        }

        .header-right {
            width: 50%;
            text-align: right;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            table-layout: fixed;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow: hidden;
        }

        .main-table thead th {
            background-color: #d5d8dc;
            color: #000;
            font-weight: bold;
            text-align: center;
            font-size: 7px;
            padding: 3px 2px;
        }

        .main-table .category-row {
            background-color: #3498db;
            color: #000;
            font-weight: bold;
            font-size: 9px;
        }

        .main-table .category-row td {
            padding: 3px 5px;
            background-color: #3498db;
            color: #000;
            font-size: 9px;
        }

        .main-table .summary-row {
            background-color: #aed6f1;
            font-weight: bold;
            font-size: 8.5px;
        }
        
        .main-table .summary-row td {
            background-color: #aed6f1;
            font-size: 8.5px;
        }

        .main-table .grand-total-row {
            background-color: #a5d6a7;
            font-weight: bold;
        }
        
        .main-table .grand-total-row td {
            background-color: #a5d6a7;
        }
        
        .main-table .total-bahan-hk-row {
            background-color: #ffccbc;
            font-weight: bold;
        }
        
        .main-table .total-bahan-hk-row td {
            background-color: #ffccbc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .text-danger { color: #dc3545; }
        
        .col-no { width: 4%; }
        .col-uraian { width: 28%; }
        .col-baku { width: 6%; }
        .col-out { width: 5%; }
        .col-harga { width: 10%; }
        .col-total { width: 11%; }
        .col-upah { width: 10%; }
        .col-borongan { width: 11%; }
        .col-untung { width: 10%; }
        .col-progress { width: 5%; }

        .page-number {
            position: absolute;
            top: 10mm;
            right: 15mm;
            font-size: 10px;
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .page {
                width: 8.5in;
                min-height: 13in;
                padding: 2mm 1mm;
                margin: 0;
                page-break-after: always;
                page-break-inside: avoid;
            }

            /* Halaman pertama: tambahkan sedikit space di bawah */
            .page:first-of-type,
            .page.page-odd:first-of-type {
                padding-bottom: 3mm;
            }

            /* Sembunyikan header di halaman genap (belakang) */
            .page.page-even .page-header {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            /* Alternatif: juga sembunyikan dengan nth-of-type */
            .page:nth-of-type(even) .page-header {
                display: none !important;
            }

            /* Sembunyikan thead (header kolom) di halaman kedua tapi tetap pertahankan lebar kolom */
            .page.page-even .main-table thead,
            .page:nth-of-type(even) .main-table thead {
                display: table-header-group !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            /* Sembunyikan konten header tapi pertahankan struktur untuk lebar kolom */
            .page.page-even .main-table thead th,
            .page:nth-of-type(even) .main-table thead th {
                height: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
                overflow: hidden !important;
                font-size: 0 !important;
                line-height: 0 !important;
                visibility: hidden !important;
            }

            .no-print {
                display: none !important;
            }

            .main-table {
                font-size: 6.5px;
                width: 100%;
            }

            .main-table th,
            .main-table td {
                font-size: 6.5px;
                padding: 1px 1px;
                line-height: 1.1;
            }

            .main-table thead th {
                font-size: 6.5px;
                padding: 1px 2px;
            }

            .main-table .category-row {
                page-break-inside: avoid;
                font-size: 7.5px !important;
            }

            .main-table .category-row td {
                font-size: 7.5px !important;
            }

            .main-table .summary-row {
                page-break-inside: avoid;
                font-size: 7px !important;
            }

            .main-table .summary-row td {
                font-size: 7px !important;
            }

            .main-table .grand-total-row {
                page-break-inside: avoid;
            }

            .main-table .total-bahan-hk-row {
                page-break-inside: avoid;
            }

            .header {
                margin-bottom: 2px;
                padding: 1mm 0;
            }

            .header-table {
                font-size: 9px;
            }

            .header-table td {
                padding: 1px 2px;
            }

            @page {
                size: 8.5in 13in;
                margin: 0;
            }

            @page :first {
                margin: 0;
            }

            @page :left {
                margin: 0;
            }

            @page :right {
                margin: 0;
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
    <a href="{{ route('rab.type107', ['type_id' => $selectedType->id ?? '', 'unit_id' => $selectedUnit->id ?? '', 'location_id' => $selectedLocation->id ?? '']) }}" class="back-btn no-print">← Kembali</a>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

    @php
        // Pisahkan kategori menjadi 2 grup: A-K dan L-selesai
        $categoriesFirstPage = $categories->filter(function($cat) {
            $kode = strtoupper($cat->kode);
            return $kode >= 'A' && $kode <= 'K';
        });
        
        $categoriesSecondPage = $categories->filter(function($cat) {
            $kode = strtoupper($cat->kode);
            return $kode >= 'L';
        });
        
        // Hitung grand total untuk semua kategori
        $grandTotalHarga = 0;
        $grandTotalUpah = 0;
        $grandTotalBorongan = 0;
        $allProgress = [];
        $categoryCount = 0;
        
        foreach($categories as $category) {
            $categoryItems = $rabItems->where('rab_category_id', $category->id);
            if ($categoryItems->isEmpty()) continue;
            
            $catBorongan = $categoryBorongans->get($category->id);
            $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
            $catUpahValue = $catBorongan ? $catBorongan->upah : 0;
            $catProgressValue = $catBorongan ? $catBorongan->progress : 0;
            
            $grandTotalHarga += $categoryItems->sum('total_harga');
            $grandTotalUpah += $catUpahValue;
            $grandTotalBorongan += $catBoronganValue;
            $allProgress[] = $catProgressValue;
            $categoryCount++;
        }
        
        $grandUntungRugi = $grandTotalBorongan - $grandTotalUpah;
        $avgProgress = $categoryCount > 0 ? round(array_sum($allProgress) / $categoryCount) : 0;
    @endphp

    {{-- HALAMAN PERTAMA: Kategori A-K --}}
    <div class="page page-odd">
        {{-- Header LOKASI dan TYPE --}}
        <div class="header page-header">
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
                @foreach($categoriesFirstPage as $category)
                    @php
                        $categoryItems = $rabItems->where('rab_category_id', $category->id);
                        if ($categoryItems->isEmpty()) continue;
                        
                        // Get category data from borongans
                        $catBorongan = $categoryBorongans->get($category->id);
                        $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
                        $catUpahValue = $catBorongan ? $catBorongan->upah : 0;
                        $catProgressValue = $catBorongan ? $catBorongan->progress : 0;
                        $catUntungRugi = $catBoronganValue - $catUpahValue;
                        
                        // Calculate total harga from items
                        $catTotalHarga = $categoryItems->sum('total_harga');
                        
                        // Grand total sudah dihitung di awal, tidak perlu dihitung lagi di sini
                        
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
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        </tr>
                    @endforeach

                    {{-- Category Summary with Upah, Borongan, Untung/Rugi, Progress --}}
                    <tr class="summary-row">
                        <td colspan="5" class="text-center"><strong>Subtotal {{ $category->nama }}:</strong></td>
                        <td class="text-right">Rp {{ number_format($catTotalHarga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catUpahValue, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catBoronganValue, 0, ',', '.') }}</td>
                        <td class="text-right {{ $catUntungRugi < 0 ? 'text-danger' : '' }}">
                            Rp {{ number_format($catUntungRugi, 0, ',', '.') }}
                        </td>
                        <td class="text-center">{{ $catProgressValue }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- HALAMAN KEDUA: Kategori L-selesai --}}
    <div class="page page-even">
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
                @foreach($categoriesSecondPage as $category)
                    @php
                        $categoryItems = $rabItems->where('rab_category_id', $category->id);
                        if ($categoryItems->isEmpty()) continue;
                        
                        // Get category data from borongans
                        $catBorongan = $categoryBorongans->get($category->id);
                        $catBoronganValue = $catBorongan ? $catBorongan->borongan : 0;
                        $catUpahValue = $catBorongan ? $catBorongan->upah : 0;
                        $catProgressValue = $catBorongan ? $catBorongan->progress : 0;
                        $catUntungRugi = $catBoronganValue - $catUpahValue;
                        
                        // Calculate total harga from items
                        $catTotalHarga = $categoryItems->sum('total_harga');
                        
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
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        </tr>
                    @endforeach

                    {{-- Category Summary with Upah, Borongan, Untung/Rugi, Progress --}}
                    <tr class="summary-row">
                        <td colspan="5" class="text-center"><strong>Subtotal {{ $category->nama }}:</strong></td>
                        <td class="text-right">Rp {{ number_format($catTotalHarga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catUpahValue, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($catBoronganValue, 0, ',', '.') }}</td>
                        <td class="text-right {{ $catUntungRugi < 0 ? 'text-danger' : '' }}">
                            Rp {{ number_format($catUntungRugi, 0, ',', '.') }}
                        </td>
                        <td class="text-center">{{ $catProgressValue }}%</td>
                    </tr>
                @endforeach

                {{-- Grand Total (hanya di halaman terakhir) --}}
                <tr class="grand-total-row">
                    <td colspan="5" class="text-center"><strong>JUMLAH TOTAL</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalHarga, 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalUpah, 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($grandTotalBorongan, 0, ',', '.') }}</strong></td>
                    <td class="text-right {{ $grandUntungRugi < 0 ? 'text-danger' : '' }}">
                        <strong>Rp {{ number_format($grandUntungRugi, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center"><strong>{{ $avgProgress }}%</strong></td>
                </tr>

                {{-- Total Harga Bahan + Total HK Row --}}
                <tr class="total-bahan-hk-row">
                    <td colspan="5" class="text-center"><strong>( TOTAL HARGA BAHAN + TOTAL HK )</strong></td>
                    <td class="text-center" colspan="5">
                        <strong>Rp {{ number_format($grandTotalHarga + $grandTotalUpah, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer Info Halaman 2 --}}
        <div class="page-footer" style="margin-top: 10px; font-size: 8px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p style="margin: 2px 0;"><strong>Unit:</strong> {{ $selectedUnit->kode_unit ?? '-' }}</p>
                        <p style="margin: 2px 0;"><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                        <p style="margin: 2px 0;"><strong>Halaman 2/2</strong></p>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <p style="margin: 2px 0;"><strong>Keterangan:</strong></p>
                        <p style="margin: 2px 0; color: #dc3545;">* Warna merah = Rugi</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        // Tambahkan class khusus untuk halaman genap (belakang)
        document.addEventListener('DOMContentLoaded', function() {
            const pages = document.querySelectorAll('.page');
            pages.forEach((page, index) => {
                // Index dimulai dari 0, jadi halaman 2, 4, 6, dst adalah genap
                if ((index + 1) % 2 === 0) {
                    page.classList.add('page-even');
                } else {
                    page.classList.add('page-odd');
                }
            });
        });

        // Saat print, pastikan class sudah ditambahkan
        window.addEventListener('beforeprint', function() {
            const pages = document.querySelectorAll('.page');
            pages.forEach((page, index) => {
                if ((index + 1) % 2 === 0) {
                    page.classList.add('page-even');
                } else {
                    page.classList.add('page-odd');
                }
            });
        });
    </script>
</body>
</html>
