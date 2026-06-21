@extends('backend.layouts.app')

@section('title', 'Data Artikel')

@section('content')

@php
    $dataArtikel = isset($artikels) ? $artikels : collect();

    $totalArtikel = $dataArtikel->count();
    $totalPublished = $dataArtikel->where('status', true)->count();
    $totalDraft = $dataArtikel->where('status', false)->count();
@endphp

<div class="admin-content">

    <section class="hero-card">
        <div class="hero-badge">
            <span></span>
            Data Artikel MindHaven
        </div>

        <div class="hero-main">
            <div>
                <h1>
                    Kelola Data <span>Artikel</span>
                </h1>

                <p>
                    Pantau dan kelola seluruh artikel edukasi kesehatan mental dengan tampilan modern dan rapi.
                </p>
            </div>

            <a href="{{ route('admin.artikel.create') }}" class="btn-add">
                Tambah Artikel
            </a>
        </div>

        <div class="hero-stats">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 4H17C18.1046 4 19 4.89543 19 6V20L12 17L5 20V6C5 4.89543 5.89543 4 7 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9 12H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <p>Total Artikel</p>
                    <h3>{{ $totalArtikel }} Artikel</h3>
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
                    <h3>{{ $totalPublished }} Artikel</h3>
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
                    <h3>{{ $totalDraft }} Artikel</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="card-table">
        <div class="table-header">
            <div>
                <h2>Daftar Artikel</h2>
                <p>Semua artikel edukasi yang tersedia pada sistem MindHaven.</p>
            </div>
        </div>

        @if ($artikels->count())
            <div class="article-list">
                @foreach ($artikels as $artikel)
                    <article class="article-card">
                        <div class="article-media">
                            @if ($artikel->gambar)
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}">
                            @else
                                <div class="empty-image">No Image</div>
                            @endif
                        </div>

                        <div class="article-content">
                            <div class="article-labels">
                                <span class="pill category">
                                    {{ $artikel->kategori ?? 'Kesehatan Mental' }}
                                </span>

                                @if ($artikel->status)
                                    <span class="pill published">Published</span>
                                @else
                                    <span class="pill draft">Draft</span>
                                @endif
                            </div>

                            <h3>{{ $artikel->judul }}</h3>

                            <p class="article-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($artikel->konten ?? '-'), 260) }}
                            </p>

                            <div class="meta-grid">
                                <div class="meta-item">
                                    <span>Penulis</span>
                                    <strong>{{ $artikel->penulis ?? 'Tim MindHaven' }}</strong>
                                </div>

                                <div class="meta-item">
                                    <span>Sumber</span>
                                    <strong>{{ \Illuminate\Support\Str::limit($artikel->sumber_artikel ?? '-', 55) }}</strong>
                                </div>

                                <div class="meta-item">
                                    <span>Tanggal</span>
                                    <strong>
                                        @if ($artikel->tanggal_publish)
                                            {{ \Carbon\Carbon::parse($artikel->tanggal_publish)->translatedFormat('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="article-actions">
                            <a href="{{ route('admin.artikel.edit', $artikel->id_artikel) }}" class="btn-edit">
                                Edit Artikel
                            </a>

                            <form action="{{ route('admin.artikel.destroy', $artikel->id_artikel) }}" method="POST" class="delete-article-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">
                                    Hapus Artikel
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-data">
                Belum ada data artikel.
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
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 180px;
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

    .card-table {
        background: #ffffff;
        padding: 28px;
        border-radius: 30px;
        box-shadow: 0 20px 55px rgba(15, 23, 42, 0.07);
        border: 1px solid #e5e7eb;
    }

    .table-header {
        margin-bottom: 24px;
    }

    .table-header h2 {
        margin: 0;
        color: #0F172A;
        font-size: 26px;
        font-weight: 500;
        letter-spacing: -0.02em;
    }

    .table-header p {
        margin: 7px 0 0;
        color: #64748B;
        font-size: 15px;
        font-weight: 400;
    }

    .article-list {
        display: flex;
        flex-direction: column;
        gap: 26px;
        width: 100%;
    }

    .article-card {
        width: 100%;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e4eaf2;
        border-radius: 30px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
        transition: 0.25s ease;
    }

    .article-card:hover {
        transform: translateY(-3px);
        border-color: #cfe0ef;
        box-shadow: 0 22px 50px rgba(15, 23, 42, 0.09);
    }

    .article-media {
        width: 100%;
        height: 360px;
        overflow: hidden;
        background: #f8fafc;
        border-bottom: 1px solid #e4eaf2;
    }

    .article-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .empty-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 15px;
        font-weight: 600;
    }

    .article-content {
        padding: 30px 32px 26px;
    }

    .article-labels {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1;
    }

    .pill.category {
        background: #eef7ff;
        color: #01588E;
    }

    .pill.published {
        background: #dcfce7;
        color: #16a34a;
    }

    .pill.draft {
        background: #fef3c7;
        color: #d97706;
    }

    .article-content h3 {
        margin: 0;
        max-width: 980px;
        color: #0f172a;
        font-size: 34px;
        line-height: 1.32;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .article-excerpt {
        max-width: 980px;
        margin: 16px 0 0;
        color: #64748b;
        font-size: 17px;
        line-height: 1.85;
        font-weight: 400;
    }

    .meta-grid {
        margin-top: 26px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .meta-item {
        min-width: 0;
        padding: 17px 18px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
    }

    .meta-item span {
        display: block;
        margin-bottom: 7px;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .meta-item strong {
        display: block;
        color: #334155;
        font-size: 15px;
        line-height: 1.45;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .article-actions {
        display: flex;
        gap: 14px;
        padding: 0 32px 32px;
    }

    .article-actions form {
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
        background: #F8FAFC;
        border-radius: 24px;
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

        .card-table {
            padding: 26px;
        }

        .article-media {
            height: 260px;
        }

        .article-content h3 {
            font-size: 27px;
        }

        .article-excerpt {
            font-size: 15px;
        }

        .meta-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .admin-content {
            padding: 18px 14px;
        }

        .hero-card,
        .card-table {
            padding: 22px;
            border-radius: 24px;
        }

        .hero-main h1 {
            font-size: 30px;
        }

        .table-header h2 {
            font-size: 24px;
        }

        .article-card {
            border-radius: 24px;
        }

        .article-media {
            height: 210px;
        }

        .article-content {
            padding: 22px;
        }

        .article-content h3 {
            font-size: 22px;
        }

        .article-excerpt {
            line-height: 1.75;
        }

        .article-actions {
            flex-direction: column;
            padding: 0 22px 22px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-article-form');

        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus Artikel?',
                    text: 'Data artikel yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#64748B',
                    reverseButtons: true,
                    background: '#FFFFFF',
                    color: '#0F172A',
                    customClass: {
                        popup: 'rounded-swal-popup',
                        confirmButton: 'rounded-swal-button',
                        cancelButton: 'rounded-swal-button'
                    }
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