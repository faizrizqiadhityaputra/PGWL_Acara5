@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 2.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15);
            position: relative;
            overflow: hidden;
        }
        .dashboard-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            background: white;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.2rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1);
        }
        .bg-light-primary { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .bg-light-success { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
        .bg-light-warning { background-color: rgba(255, 193, 7, 0.12); color: #e0a800; }
        .bg-light-info { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }

        .action-card {
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 1.5rem;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.2s ease;
            background: white;
        }
        .action-card:hover {
            background: #f8fbff;
            border-color: #0d6efd;
            color: #0d6efd;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.08);
        }
        .action-icon {
            font-size: 2.2rem;
        }
        .about-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
    </style>
@endsection

@section('content')
<div class="container py-4">

    <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3 mt-2">
        <div class="position-relative" style="z-index: 1;">
            <h2 class="fw-bold mb-2">
                @auth
                    Selamat Datang, {{ auth()->user()->name }}!
                @else
                    Selamat Datang di WebGIS!
                @endauth
            </h2>
            <p class="mb-0 text-white-50 fs-6">Panel Manajemen Sistem Informasi Geografis (CRUD Geospasial)</p>
        </div>
        <div class="text-end position-relative" style="z-index: 1;">
            <span class="badge bg-white text-primary px-4 py-2 rounded-pill shadow-sm fs-6 fw-medium">
                <i class="fa-solid fa-calendar-day me-2"></i> {{ now()->format('d F Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card h-100 p-4">
                <div class="icon-wrapper bg-light-primary">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-2">Total Poin</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $points_count }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card h-100 p-4">
                <div class="icon-wrapper bg-light-success">
                    <i class="fa-solid fa-route"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-2">Total Garis (Polyline)</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $polylines_count }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card h-100 p-4">
                <div class="icon-wrapper bg-light-warning">
                    <i class="fa-solid fa-draw-polygon"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-2">Total Area (Polygon)</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $polygons_count }}</h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card h-100 p-4">
                <div class="icon-wrapper bg-light-info">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-2">Pengguna Terdaftar</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $user_count }}</h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <i class="fa-solid fa-bolt text-warning me-2"></i> Akses Cepat
            </h5>
            <div class="d-flex flex-column gap-3">
                <a href="{{ route('peta') }}" class="action-card">
                    <div class="action-icon text-primary">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Peta Interaktif WebGIS</h6>
                        <span class="text-muted small">Visualisasikan, tambah, edit, atau hapus data spasial langsung di atas kanvas peta.</span>
                    </div>
                    <i class="fa-solid fa-chevron-right ms-auto text-muted"></i>
                </a>

                <a href="{{ route('tabel') }}" class="action-card">
                    <div class="action-icon text-success">
                        <i class="fa-solid fa-table-list"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Tabel Atribut Data</h6>
                        <span class="text-muted small">Kelola informasi tabular dari entitas geospasial yang telah didigitasi.</span>
                    </div>
                    <i class="fa-solid fa-chevron-right ms-auto text-muted"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <i class="fa-solid fa-circle-info text-info me-2"></i> Informasi Sistem
            </h5>
            <div class="about-section h-100 d-flex flex-column justify-content-center">
                <h6 class="fw-bold text-dark mb-3">Aplikasi Geospatial CRUD</h6>
                <p class="text-muted mb-4 lh-lg text-justify">
                    Aplikasi ini dikembangkan untuk memenuhi tugas kuliah praktikum <b>Pemrograman Geospasial Web Lanjut</b>. Sistem ini menampilkan peta interaktif yang memfasilitasi manajemen objek geometri (Titik, Garis, dan Area/Poligon) secara <i>real-time</i>.
                </p>
                <div class="d-flex flex-wrap gap-2 mt-auto">
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-brands fa-laravel text-danger me-1"></i> Laravel</span>
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-database text-primary me-1"></i> PostgreSQL + PostGIS</span>
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-leaf text-success me-1"></i> Leaflet JS</span>
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-brands fa-bootstrap text-purple me-1"></i> Bootstrap 5</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
