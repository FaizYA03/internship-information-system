@extends('magang.layouts.main')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Manajemen Pembimbing</h1>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Siswa</th>
                    <th class="px-4 py-3">Magang</th>
                    <th class="px-4 py-3">Guru Pembimbing</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($data as $item)
                <tr class="hover:bg-gray-50">
                    
                    <!-- SISWA -->
                    <td class="px-4 py-3 font-medium">
                        {{ $item->siswa->nama ?? '-' }}
                    </td>

                    <!-- MAGANG -->
                    <td class="px-4 py-3">
                        {{ $item->magang->posisi ?? '-' }}
                    </td>

                    <!-- GURU -->
                    <td class="px-4 py-3">
                        {{ $item->guru->nama ?? '-' }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-3">
                        @if($item->status == 'rekomendasi')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Rekomendasi
                            </span>
                        @else
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Disetujui
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-3 text-center space-y-2">

                        @if($item->status == 'rekomendasi')
                        <!-- APPROVE -->
                        <form action="{{ url('/admin/pembimbing/'.$item->id.'/approve') }}" method="POST">
                            @csrf
                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                ✔ Approve
                            </button>
                        </form>
                        @endif

                        <!-- GANTI GURU -->
                        <form action="{{ url('/admin/pembimbing/'.$item->id.'/update') }}" method="POST" class="flex gap-2 justify-center">
                            @csrf
                            <select name="guru_id" class="border rounded px-2 py-1 text-xs">
                                @foreach(App\Models\Guru::where('status','aktif')->get() as $guru)
                                    <option value="{{ $guru->id }}">
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                Ganti
                            </button>
                        </form>

                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection