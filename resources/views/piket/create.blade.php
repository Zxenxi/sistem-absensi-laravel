@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Tambah Jadwal Petugas Piket</h1>
        <form action="{{ route('piket.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Guru</label>
                <select name="guru_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Pilih Guru</option>
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Piket</label>
                <input type="date" name="schedule_date" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jam Mulai</label>
                <input type="time" name="start_time" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jam Selesai</label>
                <input type="time" name="end_time" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('piket.index') }}" class="ml-4 text-gray-700 hover:underline">Batal</a>
            </div>
        </form>
    </div>
@endsection
