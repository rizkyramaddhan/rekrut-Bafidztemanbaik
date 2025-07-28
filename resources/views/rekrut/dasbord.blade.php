@extends('layouts.app')

@section('title', 'Dashboard - Sistem Rekrutmen')

@section('page_title', 'Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="row">
        <x-card title="Total Pelamar" value="{{ $totalPelamar }}" icon="fas fa-users" href="{{ route('admin.dasbord') }}"/>
        <x-card title="Posisi Terbuka" value="{{ $totalPosisi }}" icon="fas fa-briefcase" href="{{ route('posisi') }}"/>
        <x-card title="Total Status Proses" value="{{ $statusProses }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'proses']) }}" />
        <x-card title="Total Status Interview" value="{{ $statusInterview }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'interview']) }}" />
        <x-card title="Total Status Training" value="{{ $totalStatusTraining }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'training']) }}" />
        <x-card title="Total Status Di Tolak" value="{{ $totalStatusTolak }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'ditolak']) }}" />
    </div>
@endsection
