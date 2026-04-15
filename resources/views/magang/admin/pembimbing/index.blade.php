@extends('magang.layouts.main')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Penentuan Guru Pembimbing</h1>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Siswa</th>
                    <th class="px-4 py-3">Magang</th>
                    <th class="px-4 py-3">Pilih Guru</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($magang as $item)
                <tr class="hover:bg-gray-50">

                    <!-- SISWA -->
                    <td class="px-4 py-3 font-medium">
                        {{ $item->nama }}
                        <div class="text-xs text-gray-500">
                            {{ $item->email ?? '-' }}
                        </div>
                    </td>

                    <!-- MAGANG -->
                    <td class="px-4 py-3">
                        <div class="font-medium">
                            {{ optional($item->opening)->posisi ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}
                        </div>
                    </td>

                    <!-- PILIH GURU -->
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.pembimbing.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="magang_id" value="{{ $item->id }}">

                            <select name="guru_id" class="border rounded px-2 py-1 text-sm w-full" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}">
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-3 text-center">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                ✔ Tetapkan
                            </button>
                        </form>
                    </td>

                </tr>

                @empty
                {{-- EMPTY --}}
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        Belum ada siswa yang diterima mitra 😴 <br>
                        <span class="text-xs">
                            Data akan muncul setelah mitra approve siswa
                        </span>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection