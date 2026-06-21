@extends('backend.layouts.app')

@section('title', 'Data Meditasi')

@section('content')

@php
    $dataMeditasi = isset($meditasis) ? $meditasis : collect();

    $totalMeditasi = $dataMeditasi->count();
    $totalPublished = $dataMeditasi->where('status', 'published')->count();
    $totalDraft = $dataMeditasi->where('status', 'draft')->count();
@endphp

<div class="admin-content">

    <section class="hero-card">
        <div class="hero-badge">
            <span></span>
            Data Meditasi MindHaven
        </div>

        <div class="hero-main">
            <div>
                <h1>
                    Kelola Data <span>Meditasi</span>
                </h1>

                <p>
                    Pantau dan kelola konten relaksasi serta meditasi MindHaven dengan tampilan modern dan rapi.
                </p>
            </div>

            <a href="{{ route('admin.meditasi.create') }}" class="btn-add">
                Tambah Meditasi
            </a>
        </div>

        <div class="hero-stats">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 18V5L21 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="2"/>
                        <circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div>
                    <p>Total Meditasi</p>
                    <h3>{{ $totalMeditasi }} Meditasi</h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M20 7L10 17L5 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 5.5C4 4.67157 4.67157 4 5.5 4H18.5C19.3284 4 20 4.67157 20 5.5V18.5C20 19.3284 19.3284 20 18.5 20H5.5C4.67157 20 4 19.3284 4 18.5V5.5Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div>
                    <p>Published</p>
                    <h3>{{ $totalPublished }} Meditasi</h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 4H14L19 9V20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M14 4V9H19" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 14H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <p>Draft</p>
                    <h3>{{ $totalDraft }} Meditasi</h3>
                </div>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <section class="content-card">
        <div class="section-header">
            <div>
                <h2>Daftar Meditasi</h2>
                <p>Semua konten meditasi yang tersedia pada sistem MindHaven.</p>
            </div>
        </div>

        @if($meditasis->count())

            <div class="meditasi-list">

                @foreach ($meditasis as $meditasi)

                    <div class="meditasi-card">

                        <div class="meditasi-banner">
                            <div class="banner-content">
                                <div class="meditasi-icon">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M9 18V5L21 3V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>

                                <div>
                                    <span class="banner-small">Konten Meditasi</span>
                                    <h3>{{ $meditasi->judul }}</h3>
                                </div>
                            </div>

                            @if($meditasi->status == 'published')
                                <span class="badge-published">
                                    Published
                                </span>
                            @else
                                <span class="badge-draft">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <div class="meditasi-body">
                            <div class="meditasi-labels">
                                <span class="category-pill">
                                    {{ $meditasi->kategori ?? '-' }}
                                </span>
                            </div>

                            <h3>
                                {{ $meditasi->judul }}
                            </h3>

                            <p class="description">
                                {{ $meditasi->deskripsi }}
                            </p>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span>Durasi</span>
                                    <strong>{{ $meditasi->durasi }} Menit</strong>
                                </div>

                                <div class="info-item">
                                    <span>Admin</span>
                                    <strong>{{ $meditasi->admin->nama_lengkap ?? $meditasi->admin->user->name ?? '-' }}</strong>
                                </div>

                                <div class="info-item">
                                    <span>File Audio</span>
                                    <strong>{{ $meditasi->audio ? 'Tersedia' : 'Belum ada' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="action-wrapper">
                            <a href="{{ route('admin.meditasi.edit', $meditasi->id_meditasi) }}" class="btn-edit">
                                Edit Meditasi
                            </a>

                            <form action="{{ route('admin.meditasi.destroy', $meditasi->id_meditasi) }}" method="POST" class="delete-meditasi-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">
                                    Hapus Meditasi
                                </button>
                            </form>
                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-data">
                Belum ada data meditasi.
            </div>

        @endif
    </section>

</div>

<style>
    .admin-content {
        padding: 34px 46px;
    }

    .hero-card {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 32px 40px;
        margin-bottom: 30px;
        background:
            radial-gradient(circle at 0% 0%, rgba(45, 212, 191, 0.13), transparent 28%),
            radial-gradient(circle at 100% 0%, rgba(147, 197, 253, 0.16), transparent 30%),
            linear-gradient(135deg, #F8FDFF 0%, #FFFFFF 48%, #F1F7FF 100%);
        box-shadow: 0 20px 55px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(226, 232, 240, 0.9);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 10px 18px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #dbeafe;
        color: #01588E;
        font-size: 15px;
        font-weight: 500;
        box-shadow: 0 8px 18px rgba(1, 88, 142, 0.08);
    }

    .hero-badge span {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        background: #12B76A;
    }

    .hero-main {
        margin-top: 34px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 26px;
    }

    .hero-main h1 {
        margin: 0;
        font-size: 46px;
        line-height: 1.1;
        letter-spacing: -0.04em;
        font-weight: 600;
        color: #0F172A;
    }

    .hero-main h1 span {
        color: #0284C7;
        font-weight: 600;
    }

    .hero-main p {
        margin: 20px 0 0;
        color: #334155;
        font-size: 18px;
        line-height: 1.65;
        font-weight: 400;
        max-width: 780px;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 190px;
        padding: 16px 24px;
        border-radius: 20px;
        background: #01588E;
        color: #ffffff;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 16px 34px rgba(1, 88, 142, 0.22);
        transition: 0.25s ease;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: #014a78;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .hero-stats {
        margin-top: 30px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        max-width: 900px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 90px;
        padding: 16px 20px;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.07);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg {
        width: 27px;
        height: 27px;
    }

    .stat-icon.blue {
        background: #DBEAFE;
        color: #2563EB;
    }

    .stat-icon.green {
        background: #DCFCE7;
        color: #16A34A;
    }

    .stat-icon.yellow {
        background: #FEF3C7;
        color: #D97706;
    }

    .stat-card p {
        margin: 0;
        color: #94A3B8;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.16em;
    }

    .stat-card h3 {
        margin: 8px 0 0;
        color: #0F172A;
        font-size: 20px;
        font-weight: 500;
    }

    .alert-success,
    .alert-error {
        margin-bottom: 22px;
        padding: 16px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .alert-success {
        background: #ECFDF3;
        color: #027A48;
    }

    .alert-error {
        background: #FFF1F2;
        color: #E11D48;
    }

    .content-card {
        background: #ffffff;
        padding: 28px;
        border-radius: 30px;
        box-shadow: 0 20px 55px rgba(15, 23, 42, 0.07);
        border: 1px solid #e5e7eb;
    }

    .section-header {
        margin-bottom: 24px;
    }

    .section-header h2 {
        margin: 0;
        color: #0F172A;
        font-size: 26px;
        font-weight: 500;
        letter-spacing: -0.02em;
    }

    .section-header p {
        margin: 7px 0 0;
        color: #64748B;
        font-size: 15px;
        font-weight: 400;
    }

    .meditasi-list {
        display: flex;
        flex-direction: column;
        gap: 26px;
        width: 100%;
    }

    .meditasi-card {
        width: 100%;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4eaf2;
        border-radius: 30px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
        transition: 0.25s ease;
    }

    .meditasi-card:hover {
        transform: translateY(-3px);
        border-color: #cfe0ef;
        box-shadow: 0 22px 50px rgba(15, 23, 42, 0.09);
    }

    .meditasi-banner {
        min-height: 230px;
        padding: 34px 36px;
        background:
            radial-gradient(circle at 0% 0%, rgba(1, 88, 142, 0.16), transparent 34%),
            radial-gradient(circle at 100% 0%, rgba(65, 173, 1, 0.13), transparent 32%),
            linear-gradient(135deg, #F8FDFF 0%, #EEF7FF 100%);
        border-bottom: 1px solid #e4eaf2;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
    }

    .banner-content {
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .meditasi-icon {
        width: 82px;
        height: 82px;
        border-radius: 26px;
        background: #DBEAFE;
        color: #01588E;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 16px 30px rgba(1, 88, 142, 0.12);
    }

    .meditasi-icon svg {
        width: 38px;
        height: 38px;
    }

    .banner-small {
        display: inline-flex;
        margin-bottom: 10px;
        color: #64748B;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .meditasi-banner h3 {
        margin: 0;
        color: #0F172A;
        font-size: 38px;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.035em;
    }

    .badge-published,
    .badge-draft {
        display: inline-flex;
        align-items: center;
        padding: 10px 18px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-published {
        background: #DCFCE7;
        color: #16A34A;
    }

    .badge-draft {
        background: #FEF3C7;
        color: #D97706;
    }

    .meditasi-body {
        padding: 30px 32px 26px;
    }

    .meditasi-labels {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
    }

    .category-pill {
        display: inline-flex;
        padding: 9px 15px;
        border-radius: 999px;
        background: #EAF8FF;
        color: #01588E;
        font-size: 13px;
        font-weight: 700;
    }

    .meditasi-body h3 {
        margin: 0;
        max-width: 980px;
        color: #0F172A;
        font-size: 34px;
        line-height: 1.32;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .description {
        max-width: 980px;
        margin: 16px 0 0;
        color: #64748B;
        font-size: 17px;
        line-height: 1.85;
        font-weight: 400;
    }

    .info-grid {
        margin-top: 26px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .info-item {
        min-width: 0;
        padding: 17px 18px;
        border-radius: 18px;
        background: #F8FAFC;
        border: 1px solid #edf2f7;
    }

    .info-item span {
        display: block;
        margin-bottom: 7px;
        color: #94A3B8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .info-item strong {
        display: block;
        color: #334155;
        font-size: 15px;
        line-height: 1.45;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-wrapper {
        display: flex;
        gap: 14px;
        padding: 0 32px 32px;
    }

    .action-wrapper form {
        flex: 1;
        margin: 0;
    }

    .btn-edit,
    .btn-delete {
        width: 100%;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 15px;
        font-weight: 700;
        transition: 0.22s ease;
    }

    .btn-edit {
        flex: 1;
        background: #01588E;
        color: #ffffff;
        text-decoration: none;
        border: 1px solid #01588E;
        box-shadow: 0 12px 24px rgba(1, 88, 142, 0.18);
    }

    .btn-edit:hover {
        background: #014a78;
        color: #ffffff;
        border-color: #014a78;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: #ef4444;
        color: #ffffff;
        border: 1px solid #ef4444;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(239, 68, 68, 0.16);
    }

    .btn-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        transform: translateY(-1px);
    }

    .empty-data {
        text-align: center;
        color: #94a3b8;
        padding: 42px;
        font-weight: 400;
        border-radius: 24px;
        background: #F8FAFC;
        border: 1px dashed #CBD5E1;
    }

    @media (max-width: 992px) {
        .admin-content {
            padding: 26px 20px;
        }

        .hero-card {
            padding: 28px;
        }

        .hero-main {
            flex-direction: column;
        }

        .hero-main h1 {
            font-size: 38px;
        }

        .hero-main p {
            font-size: 16px;
        }

        .hero-stats {
            grid-template-columns: 1fr;
        }

        .btn-add {
            width: 100%;
        }

        .content-card {
            padding: 26px;
        }

        .meditasi-banner {
            min-height: auto;
            flex-direction: column;
            padding: 28px;
        }

        .banner-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .meditasi-banner h3 {
            font-size: 30px;
        }

        .meditasi-body h3 {
            font-size: 27px;
        }

        .description {
            font-size: 15px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .admin-content {
            padding: 18px 14px;
        }

        .hero-card,
        .content-card {
            padding: 22px;
            border-radius: 24px;
        }

        .hero-main h1 {
            font-size: 30px;
        }

        .section-header h2 {
            font-size: 24px;
        }

        .meditasi-card {
            border-radius: 24px;
        }

        .meditasi-banner {
            padding: 24px 22px;
        }

        .meditasi-icon {
            width: 68px;
            height: 68px;
            border-radius: 22px;
        }

        .meditasi-icon svg {
            width: 32px;
            height: 32px;
        }

        .meditasi-banner h3 {
            font-size: 26px;
        }

        .meditasi-body {
            padding: 22px;
        }

        .meditasi-body h3 {
            font-size: 22px;
        }

        .description {
            line-height: 1.75;
        }

        .action-wrapper {
            flex-direction: column;
            padding: 0 22px 22px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-meditasi-form');

        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus Meditasi?',
                    text: 'Data meditasi yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#64748B',
                    reverseButtons: true,
                    background: '#FFFFFF',
                    color: '#0F172A'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#0F172A'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal',
                text: @json(session('error')),
                icon: 'error',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#01588E',
                background: '#FFFFFF',
                color: '#0F172A'
            });
        @endif
    });
</script>

@endsection