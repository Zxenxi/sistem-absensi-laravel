<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PiketSchedule;
use App\Models\User; // Untuk data guru
use Carbon\Carbon;

class PiketController extends Controller
{
    /**
     * Tampilkan daftar jadwal piket.
     * Jika request Ajax, kembalikan data JSON.
     */
    public function index(Request $request)
    {
        $schedules = PiketSchedule::with('guru')->orderBy('schedule_date', 'asc')->get();
        if ($request->ajax()) {
            return response()->json(['schedules' => $schedules]);
        }
        return view('piket.index');
    }
    

    /**
     * Simpan jadwal piket baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id'       => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'start_time'    => 'nullable',
            'end_time'      => 'nullable',
        ]);

        $schedule = PiketSchedule::create($request->all());
        return response()->json([
            'message' => 'Jadwal piket berhasil ditambahkan.',
            'schedule' => $schedule
        ]);
    }

    /**
     * Ambil data jadwal piket untuk keperluan edit.
     */
    public function show(PiketSchedule $piket)
    {
        return response()->json(['schedule' => $piket]);
    }

    /**
     * Perbarui jadwal piket.
     */
    public function update(Request $request, PiketSchedule $piket)
    {
        $request->validate([
            'guru_id'       => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'start_time'    => 'nullable',
            'end_time'      => 'nullable',
        ]);

        $piket->update($request->all());
        return response()->json([
            'message' => 'Jadwal piket berhasil diperbarui.',
            'schedule' => $piket
        ]);
    }

    /**
     * Hapus jadwal piket.
     */
    public function destroy(PiketSchedule $piket)
    {
        $piket->delete();
        return response()->json(['message' => 'Jadwal piket berhasil dihapus.']);
    }
}