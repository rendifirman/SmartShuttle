@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Driver Jadwal</h1>

    @if($mode === 'driver_confirmation')
        <h3>Open Schedules (Takeable)</h3>
        <table class="table">
            <thead><tr><th>ID</th><th>Rute</th><th>Tanggal</th><th>Jam</th><th></th></tr></thead>
            <tbody>
            @foreach($open as $o)
                <tr>
                    <td>{{ $o->id }}</td>
                    <td>{{ $o->id_rute }}</td>
                    <td>{{ $o->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $o->jam_berangkat }}</td>
                    <td>
                        <form method="POST" action="{{ route('driver.rute_jadwal.take', $o->id) }}">
                            @csrf
                            <button class="btn btn-primary">Take</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h3>Your Assigned Schedules</h3>
        <table class="table">
            <thead><tr><th>ID</th><th>Rute</th><th>Tanggal</th><th>Jam</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($assigned as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->id_rute }}</td>
                    <td>{{ $a->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $a->jam_berangkat }}</td>
                    <td>{{ $a->status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
