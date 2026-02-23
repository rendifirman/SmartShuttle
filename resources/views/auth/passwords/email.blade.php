@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Lupa Password</h2>

        @if(session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required class="w-full border p-2 rounded" value="{{ old('email') }}">
            </div>
            <div class="flex items-center justify-between">
                <a href="{{ route('driver.login') }}" class="text-sm text-gray-600 hover:underline">Kembali ke Login Driver</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Link Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection
