@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Available Schedules</h1>

    <table class="table">
        <thead><tr><th>ID</th><th>Rute</th><th>Tanggal</th><th>Jam</th><th>Driver</th></tr></thead>
        <tbody>
        @foreach($jadwals as $j)
            <tr>
                <td>{{ $j->id }}</td>
                <td>{{ $j->id_rute }}</td>
                <td>{{ $j->tanggal->format('Y-m-d') }}</td>
                <td>{{ $j->jam_berangkat }}</td>
                <td>{{ $j->id_driver ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $jadwals->links() }}
</div>
@endsection
