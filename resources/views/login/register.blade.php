@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h2>Register</h2>

    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama Pengguna -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        @error('password_confirmation')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

        <!-- Role (Default = user) -->
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Status (Default = aktif) -->
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>


        <button type="submit" class="btn btn-primary">Register</button>
    </form>
@endsection
