@extends('adminlte::page')

@section('title', 'Aktivitas Terakhir')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Aktivitas Terakhir</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        ← Kembali ke Dashboard
    </a>
</div>
@stop

@section('content')

{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">{{ number_format($stats['total']) }}</h5>
                        <small class="text-muted">Total Aktivitas</small>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-success card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">{{ number_format($stats['today']) }}</h5>
                        <small class="text-muted">Hari Ini</small>
                    </div>
                    <div class="text-success" style="font-size: 2rem;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">{{ number_format($stats['this_week']) }}</h5>
                        <small class="text-muted">Minggu Ini</small>
                    </div>
                    <div class="text-info" style="font-size: 2rem;">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-0">{{ number_format($stats['this_month']) }}</h5>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                    <div class="text-warning" style="font-size: 2rem;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Form --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter Aktivitas</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activities') }}" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="">Semua Type</option>
                        <option value="inventory" {{ request('type') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="rab" {{ request('type') == 'rab' ? 'selected' : '' }}>RAB</option>
                        <option value="angkutan" {{ request('type') == 'angkutan' ? 'selected' : '' }}>Angkutan</option>
                        <option value="master" {{ request('type') == 'master' ? 'selected' : '' }}>Master Data</option>
                        <option value="print" {{ request('type') == 'print' ? 'selected' : '' }}>Print/Export</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Action</label>
                    <select name="action" class="form-control">
                        <option value="">Semua Action</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="print" {{ request('action') == 'print' ? 'selected' : '' }}>Print</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Activities List --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Aktivitas</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Waktu</th>
                        <th>Type</th>
                        <th>Action</th>
                        <th>Deskripsi</th>
                        <th>User</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activities->firstItem() + $loop->index }}</td>
                            <td>
                                <div>{{ $activity->created_at->format('d/m/Y H:i:s') }}</div>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @php
                                    $badgeColors = [
                                        'inventory' => 'primary',
                                        'rab' => 'success',
                                        'angkutan' => 'info',
                                        'master' => 'warning',
                                        'print' => 'secondary',
                                    ];
                                    $color = $badgeColors[$activity->type] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $color }}">{{ ucfirst($activity->type) }}</span>
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'create' => 'success',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        'print' => 'info',
                                    ];
                                    $actionColor = $actionColors[$activity->action] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $actionColor }}">{{ ucfirst($activity->action) }}</span>
                            </td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->user ? $activity->user->name : 'System' }}</td>
                            <td><small class="text-muted">{{ $activity->ip_address }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada aktivitas ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $activities->links() }}
    </div>
</div>

@stop






   





