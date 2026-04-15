@extends('magang.layouts.main')

@section('content')
<div class="p-6">

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-xl mx-auto">

    <h2 class="text-xl font-semibold mb-4">
        Edit Pembimbing
    </h2>

    {{-- INFO SISWA --}}
    <div class="mb-4">
        <label class="text-sm text-gray-500">Nama Siswa</label>
        <div class="font-medium text-gray-800">
            {{ $magang->nama }}
        </div>
    </div>

    <div class="mb-4">
        <label class="text-sm text-gray-500">Magang</label>
        <div class="font-medium text-gray-800">
            {{ optional($magang->opening)->posisi ?? '-' }}
        </div>
    </div>

    {{-- FORM --}}
    <form method="POST"
        action="{{ $magang->pembimbing 
            ? url('/admin/pembimbing/'.$magang->pembimbing->id.'/update')
            : url('/admin/pembimbing/store') }}">
        
        @csrf

        <input type="hidden" name="magang_id" value="{{ $magang->id }}">

        <div class="mb-4">
            <label class="text-sm text-gray-600">Pilih Guru</label>

            <select name="guru_id"
                class="w-full border px-3 py-2 rounded mt-1 focus:ring-2 focus:ring-blue-400"
                required>

                <option value="">-- Pilih Guru --</option>

                @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}"
                        {{ optional($magang->pembimbing)->guru_id == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-between">

            <a href="{{ url('/admin/pembimbing') }}"
               class="btn bg-gray-300 text-xs">
                ← Kembali
            </a>

            <button class="btn btn-primary text-xs">
                💾 Simpan
            </button>

        </div>

    </form>

</div>

</div>
@endsection