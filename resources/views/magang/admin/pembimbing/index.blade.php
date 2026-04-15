@extends('magang.layouts.main')

@section('content')
<div class="p-6">

<div class="bg-white border rounded-xl shadow-sm">

    {{-- HEADER --}}
    <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">
            Data Pembimbing
        </h2>
    </div>

    {{-- FILTER --}}
    <div class="flex justify-between items-center p-4">
        <div class="text-sm text-gray-600">
            Tampilkan
            <select id="limitSelect" class="border rounded px-2 py-1 mx-1">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            data
        </div>

        <div class="text-sm text-gray-600">
            Cari:
            <input type="text" id="searchInput"
                class="border rounded px-3 py-1 ml-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tableData">

            <thead class="bg-blue-50 text-gray-700">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Siswa</th>
                    <th class="px-4 py-2">Magang</th>
                    <th class="px-4 py-2">Pembimbing</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($magang as $i => $item)
                <tr class="hover:bg-gray-50 relative">

                    <td class="px-4 py-2">{{ $i+1 }}</td>

                    <td class="px-4 py-2 nama font-medium">
                        {{ $item->nama }}
                    </td>

                    <td class="px-4 py-2">
                        {{ optional($item->opening)->posisi ?? '-' }}
                    </td>

                    <td class="px-4 py-2">
                        @if($item->pembimbing)
                            {{ $item->pembimbing->guru->nama }}
                        @else
                            <span class="text-red-500">Belum ada</span>
                        @endif
                    </td>

                    <td class="px-4 py-2 text-center relative">
                        <div class="flex justify-center gap-2">

                            {{-- EDIT --}}
                            <a href="{{ url('/admin/pembimbing/'.$item->id.'/edit') }}"
                            class="btn btn-warning text-xs">
                                ✏️
                            </a>
                            {{-- DELETE --}}
                            <form id="deleteForm{{ $item->id }}" 
                                  action="/admin/pembimbing/{{ $item->id }}/delete" 
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                

                            <button type="button"
                                onclick="confirmDelete('{{ $item->id }}')"
                                class="btn btn-danger text-xs">
                                🗑️
                            </button>
                            </form>

                        </div>

                        {{-- 🔥 DROPDOWN --}}
                       

                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- FOOTER --}}
    <div class="flex justify-between items-center p-4 text-sm text-gray-600 border-t">
        <span>Menampilkan {{ count($magang) }} data</span>
    </div>

</div>

</div>
@endsection


@push('script')
<script>

// 🔥 DROPDOWN CONTROL
function toggleDropdown(id, el) {
    closeDropdown();

    const dropdown = document.getElementById('dropdown' + id);
    dropdown.classList.toggle('hidden');

    // 🔥 auto posisi (kanan / kiri)
    const rect = dropdown.getBoundingClientRect();

    if (rect.right > window.innerWidth) {
        dropdown.classList.remove('left-full', 'ml-2');
        dropdown.classList.add('right-full', 'mr-2');
    } else {
        dropdown.classList.remove('right-full', 'mr-2');
        dropdown.classList.add('left-full', 'ml-2');
    }
}

function closeDropdown() {
    document.querySelectorAll('[id^="dropdown"]').forEach(el => {
        el.classList.add('hidden');
    });
}

// klik luar = close
document.addEventListener('click', function(e) {
    if (!e.target.closest('button') && !e.target.closest('[id^="dropdown"]')) {
        closeDropdown();
    }
});


// 🔥 DELETE CONFIRM
function confirmDelete(id) {
    Swal.fire({
        title: "Yakin hapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}


// 🔥 SEARCH
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#tableData tbody tr').forEach(row => {
        let nama = row.querySelector('.nama').innerText.toLowerCase();
        row.style.display = nama.includes(value) ? '' : 'none';
    });
});


// 🔥 LIMIT
document.getElementById('limitSelect').addEventListener('change', function() {
    let limit = parseInt(this.value);
    let rows = document.querySelectorAll('#tableData tbody tr');

    rows.forEach((row, index) => {
        row.style.display = index < limit ? '' : 'none';
    });
});

</script>
@endpush