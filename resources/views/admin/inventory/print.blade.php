<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stock Barang - {{ now()->format('d/m/Y') }}</title>
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

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .col-no { width: 5%; }
        .col-nama { width: 35%; }
        .col-satuan { width: 12%; }
        .col-harga { width: 15%; }
        .col-stok-awal { width: 16.5%; }
        .col-stok-akhir { width: 16.5%; }

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
    <a href="{{ route('inventory.index') }}" class="back-btn no-print">← Kembali</a>
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print</button>

    <div class="page">
        {{-- Header --}}
        <div class="header page-header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <strong>LAPORAN STOCK BARANG</strong>
                    </td>
                    <td class="header-right">
                        <strong>Tanggal:</strong> {{ now()->format('d/m/Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Main Table --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-nama">NAMA BARANG</th>
                    <th class="col-satuan">SATUAN</th>
                    <th class="col-harga">HARGA (Rp)</th>
                    <th class="col-stok-awal">STOCK AWAL</th>
                    <th class="col-stok-akhir">STOCK AKHIR</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item['nama'] }}</td>
                    <td class="text-center">{{ $item['satuan'] ?? '-' }}</td>
                    <td class="text-center">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['stok_awal'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($item['stok_akhir'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data stock barang</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer Info --}}
        <div class="page-footer" style="margin-top: 10px; font-size: 8px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p style="margin: 2px 0;"><strong>Total Item:</strong> {{ count($items) }}</p>
                        <p style="margin: 2px 0;"><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <p style="margin: 2px 0;"><strong>Keterangan:</strong></p>
                        <p style="margin: 2px 0;">Stock Awal = Stock awal barang</p>
                        <p style="margin: 2px 0;">Stock Akhir = Stock saat ini</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>