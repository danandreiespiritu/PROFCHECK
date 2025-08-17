@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Reset Password</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mt-2">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mt-2">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Reset Password</button>
        </div>
    </form>
</div>
@endsection
