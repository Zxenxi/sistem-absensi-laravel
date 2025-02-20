<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Siswa</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Absensi Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Tahun Ajaran</th>
                <th>Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $index => $attendance)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->siswa->nisn ?? '' }}</td>
                    <td>{{ $attendance->siswa->name ?? '' }}</td>
                    <td>{{ $attendance->siswa->kelas->kelas ?? '' }}</td>
                    <td>{{ $attendance->siswa->kelas->jurusan ?? '' }}</td>
                    <td>{{ $attendance->siswa->kelas->tahun_ajaran ?? '' }}</td>
                    <td>{{ $attendance->waktu }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
