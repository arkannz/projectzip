@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
<style>
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 25px;
        transition: transform 0.2s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .card-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .card-icon.green { background: linear-gradient(135deg, #22c55e, #15803d); }
    .card-icon.orange { background: linear-gradient(135deg, #f97316, #ea580c); }
    .card-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }

    .card-title {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 5px;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
    }

    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
    }

    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .welcome-subtitle {
        font-size: 16px;
        opacity: 0.9;
    }

    .quick-link {
        display: block;
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-decoration: none;
        color: #1e293b;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.2s;
        margin-bottom: 15px;
    }

    .quick-link:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        text-decoration: none;
        color: #1e293b;
    }

    .quick-link i {
        font-size: 24px;
        margin-right: 15px;
    }

    .quick-link .link-title {
        font-weight: 600;
        font-size: 16px;
    }

    .quick-link .link-desc {
        font-size: 13px;
        color: #64748b;
    }
</style>
@endsection

@section('content_header')
@stop

@section('content')

{{-- WELCOME SECTION --}}
<div class="welcome-section">
    <div class="welcome-title">
        👋 Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!
    </div>
    <div class="welcome-subtitle">
        Kelola proyek properti Anda dengan mudah melalui dashboard ini.
    </div>
</div>

{{-- STATISTICS CARDS --}}
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="d-flex align-items-center">
                <div class="card-icon blue">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="ml-3">
                    <div class="card-title">Total Bahan</div>
                    <div class="card-value">{{ $totalItems ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="d-flex align-items-center">
                <div class="card-icon green">
                    <i class="fas fa-home"></i>
                </div>
                <div class="ml-3">
                    <div class="card-title">Total Unit</div>
                    <div class="card-value">{{ $totalUnits ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="d-flex align-items-center">
                <div class="card-icon orange">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="ml-3">
                    <div class="card-title">Total Type</div>
                    <div class="card-value">{{ $totalTypes ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card">
            <div class="d-flex align-items-center">
                <div class="card-icon purple">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="ml-3">
                    <div class="card-title">Total Lokasi</div>
                    <div class="card-value">{{ $totalLocations ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- QUICK LINKS --}}
<div class="row mt-4">
    <div class="col-md-6">
        <h5 class="mb-3 font-weight-bold">🚀 Akses Cepat</h5>

        <a href="{{ route('inventory.index') }}" class="quick-link">
            <div class="d-flex align-items-center">
                <i class="fas fa-boxes text-primary"></i>
                <div>
                    <div class="link-title">Inventory</div>
                    <div class="link-desc">Kelola stok barang masuk & keluar</div>
                </div>
            </div>
        </a>

        <a href="{{ route('rab.index') }}" class="quick-link">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar text-success"></i>
                <div>
                    <div class="link-title">R A B</div>
                    <div class="link-desc">Rencana Anggaran Biaya</div>
                </div>
            </div>
        </a>

        <a href="{{ route('inventory.history') }}" class="quick-link">
            <div class="d-flex align-items-center">
                <i class="fas fa-history text-warning"></i>
                <div>
                    <div class="link-title">Riwayat Transaksi</div>
                    <div class="link-desc">Lihat riwayat barang masuk & keluar</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <h5 class="mb-3 font-weight-bold">📊 Informasi</h5>

        <div class="dashboard-card">
            <h6 class="font-weight-bold mb-3">
                <i class="fas fa-clock text-primary mr-2"></i>
                Aktivitas Terakhir
            </h6>
            <ul class="list-unstyled mb-0">
                @if(isset($recentActivities) && count($recentActivities) > 0)
                    @foreach($recentActivities as $activity)
                        <li class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            <div>{{ $activity->description }}</div>
                        </li>
                    @endforeach
                @else
                    <li class="text-muted">Belum ada aktivitas terbaru</li>
                @endif
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    console.log('Dashboard loaded successfully!');
</script>
@endsection