@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-semibold mb-4">Verify Your Email Address</h1>
        <p class="mb-4">Before proceeding, please check your email for a verification link.</p>
        @if (session('status') == 'verification-link-sent')
            <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded mb-4">A new verification link has been sent to your email address.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Resend Verification Email</button>
        </form>
    </div>
</div>
@endsection
