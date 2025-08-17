@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Forgot Password</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required class="w-full border rounded px-2 py-1" />
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Send Reset Link</button>
        </div>
    </form>
</div>
@endsection
