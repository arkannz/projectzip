<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REKAPAN MINGGUAN PASIR-BATU - {{ now()->format('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        html, body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            background: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 8.5in;
            min-height: 11in;
            padding: 10mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header .date {
            font-size: 11px;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            z-index: 1000;
        }

        .back-btn:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table td {
            text-align: left;
        }

        table td.text-center {
            text-align: center;
        }

        table td.text-right {
            text-align: right;
        }

        .total-row {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .grand-total-row {
            background-color: #d0d0d0;
            font-weight: bold;
        }

        .badge-pasir {
            color: #000;
        }

        .badge-batu {
            color: #d32f2f;
            font-weight: bold;
        }

        @media print {
            .back-btn {
                display: none;
            }
            
            .page {
                padding: 5mm;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('inventory.angkutan') }}" class="back-btn no-print">← Kembali</a>

    <div class="page">
        <div class="header">
            <h1>REKAPAN MINGGUAN PASIR-BATU</h1>
            <div class="date">Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">NO</th>
                    <th style="width: 100px;">HARI/TGL</th>
                    <th style="width: 60px;">KODE</th>
                    <th>LOKASI</th>
                    <th style="width: 80px;">ANGKUTAN</th>
                    <th style="width: 70px;">JUMLAH</th>
                    <th>PANGKALAN</th>
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
                        <tr>
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
                            <td class="text-center {{ $angkutan->angkutan === 'Batu' ? 'badge-batu' : 'badge-pasir' }}">
                                {{ $angkutan->angkutan }}
                            </td>
                            <td class="text-center">{{ number_format($angkutan->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $angkutan->pangkalan }}</td>
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
                    <tr class="total-row">
                        <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalPerHari, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                
                @if($angkatans->count() > 0)
                    {{-- Total Keseluruhan --}}
                    <tr class="grand-total-row">
                        <td colspan="4" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                        <td class="text-center"><strong>Pasir</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalPasir, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                    <tr class="grand-total-row">
                        <td colspan="4" class="text-right"></td>
                        <td class="text-center"><strong>Batu</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalBatu, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>













