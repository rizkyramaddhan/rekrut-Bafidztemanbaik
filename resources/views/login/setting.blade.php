@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
    <h2>Account Settings</h2>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form untuk mengedit pengaturan akun -->
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Input password -->
        <div class="form-group">
            <label for="password">New Password (Leave empty if not changing)</label>
            <input type="password" id="password" name="password" class="form-control">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>


        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
@endsection
