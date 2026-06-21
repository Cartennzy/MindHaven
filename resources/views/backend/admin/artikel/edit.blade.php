@extends('backend.layouts.app')

@section('title', 'Edit Artikel')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="admin-title">Edit Artikel</h1>
        <p class="admin-subtitle">Perbarui informasi artikel kesehatan mental.</p>
    </div>

    <form action="{{ route('admin.artikel.update', $artikel->id_artikel) }}" method="POST" enctype="multipart/form-data" class="admin-card p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="admin-label">Judul Artikel</label>
            <input type="text" name="judul" class="admin-input" value="{{ old('judul', $artikel->judul) }}">
            @error('judul') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="admin-label">Kategori</label>
                <select name="kategori" class="admin-input">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}" {{ old('kategori', $artikel->kategori) == $kategori ? 'selected' : '' }}>
                            {{ $kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="admin-label">Status</label>
                <select name="status" class="admin-input">
                    <option value="0" {{ old('status', $artikel->status ? '1' : '0') == '0' ? 'selected' : '' }}>Draft</option>
                    <option value="1" {{ old('status', $artikel->status ? '1' : '0') == '1' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="admin-label">Penulis</label>
                <input type="text" name="penulis" class="admin-input" value="{{ old('penulis', $artikel->penulis) }}">
                @error('penulis') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="admin-label">Sumber Artikel</label>
                <input type="text" name="sumber_artikel" class="admin-input" value="{{ old('sumber_artikel', $artikel->sumber_artikel) }}" placeholder="Contoh: WHO, Kemenkes RI, APA">
                @error('sumber_artikel') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="admin-label">Tanggal Publish</label>
            <input type="date" name="tanggal_publish" class="admin-input" value="{{ old('tanggal_publish', optional($artikel->tanggal_publish)->format('Y-m-d')) }}">
            @error('tanggal_publish') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-label">Thumbnail Baru</label>
            <input type="file" name="gambar" class="admin-input" accept="image/png,image/jpeg,image/jpg">

            @if($artikel->gambar)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-32 w-48 rounded-2xl object-cover">
                </div>
            @endif

            @error('gambar') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="admin-label">Isi Artikel</label>

            <div class="mb-3 flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                <button type="button" onclick="formatText('bold')" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Bold</button>
                <button type="button" onclick="formatText('underline')" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Underline</button>
                <button type="button" onclick="formatBlock('H1')" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Heading 1</button>
                <button type="button" onclick="formatBlock('H2')" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Heading 2</button>
                <button type="button" onclick="formatBlock('H3')" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">Heading 3</button>

                <select onchange="changeFont(this.value)" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                    <option value="">Jenis Font</option>
                    <option value="Arial">Arial</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Tahoma">Tahoma</option>
                </select>
            </div>

            <div id="editorArtikel"
                 contenteditable="true"
                 class="min-h-[300px] w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm leading-7 text-slate-800 outline-none transition focus:border-[#01588E] focus:ring-4 focus:ring-[#01588E]/10">{!! old('konten', $artikel->konten) !!}</div>

            <textarea id="kontenArtikel" name="konten" class="hidden">{{ old('konten', $artikel->konten) }}</textarea>

            @error('konten') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="admin-btn-primary">Update Artikel</button>
            <a href="{{ route('admin.artikel.index') }}" class="admin-btn-light">Batal</a>
        </div>

    </form>

</div>

<script>
    const editorArtikel = document.getElementById('editorArtikel');
    const kontenArtikel = document.getElementById('kontenArtikel');

    function formatText(command) {
        document.execCommand(command, false, null);
        editorArtikel.focus();
        syncEditor();
    }

    function formatBlock(tag) {
        document.execCommand('formatBlock', false, tag);
        editorArtikel.focus();
        syncEditor();
    }

    function changeFont(fontName) {
        if (fontName !== '') {
            document.execCommand('fontName', false, fontName);
            editorArtikel.focus();
            syncEditor();
        }
    }

    function syncEditor() {
        kontenArtikel.value = editorArtikel.innerHTML;
    }

    editorArtikel.addEventListener('input', syncEditor);

    document.querySelector('form').addEventListener('submit', function () {
        syncEditor();
    });
</script>

@endsection