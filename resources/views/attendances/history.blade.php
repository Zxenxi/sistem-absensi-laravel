@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Riwayat Presensi Saya</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 border-b">No</th>
                        <th class="py-3 px-4 border-b">Nama</th>
                        <th class="py-3 px-4 border-b">Waktu</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b">Lokasi</th>
                        <th class="py-3 px-4 border-b">Foto Wajah</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($attendances as $attendance)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-4 border-b">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4 border-b">
                                @if ($attendance->siswa)
                                    {{ $attendance->siswa->name }}
                                @elseif($attendance->guru)
                                    {{ $attendance->guru->name }}
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ \Carbon\Carbon::parse($attendance->waktu)->format('d M Y H:i') }}
                            </td>
                            <td class="py-3 px-4 border-b">{{ $attendance->status }}</td>
                            <td class="py-3 px-4 border-b">{{ $attendance->lokasi }}</td>
                            <td class="py-3 px-4 border-b">
                                @if ($attendance->foto_wajah)
                                    <img src="{{ asset($attendance->foto_wajah) }}" alt="Foto Wajah"
                                        class="w-16 h-16 rounded-full object-cover">
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center">Belum ada riwayat presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
