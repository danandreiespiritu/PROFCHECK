@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-semibold mb-4">Confirm Password</h1>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Confirm</button>
        </form>
    </div>
</div>
@endsection
