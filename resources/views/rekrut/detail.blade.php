@extends('layouts.app')

@section('title', 'Detail Pelamar - Sistem Rekrutmen')
@section('page_title', 'Detail Pelamar')
@section('page_description', 'Informasi lengkap tentang pelamar')
@section('breadcrumb', 'Detail Pelamar')

@php
        // Fungsi untuk format nomor WhatsApp
        function formatWhatsAppNumber($phone) {
            if (!$phone) return null;
            
            // Hapus semua karakter non-digit
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            // Konversi format Indonesia
            if (substr($cleanPhone, 0, 2) === '08') {
                $cleanPhone = '628' . substr($cleanPhone, 2);
            } elseif (substr($cleanPhone, 0, 1) === '8') {
                $cleanPhone = '62' . $cleanPhone;
            } elseif (substr($cleanPhone, 0, 2) !== '62') {
                $cleanPhone = '62' . $cleanPhone;
            }
            
            return $cleanPhone;
        }
        
        $whatsappNumber = formatWhatsAppNumber($pelamar->telepon);
        $defaultMessage = "Halo " . $pelamar->nama . ", terima kasih telah melamar di perusahaan kami. Kami ingin berdiskusi lebih lanjut mengenai lamaran Anda.";
        $whatsappUrl = $whatsappNumber ? 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($defaultMessage) : '#';
    @endphp

@section('content')
    <!-- Main Content -->
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Pelamar</h2>
            <a href="{{ route('admin.dasbord') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card shadow-sm rounded">
            <div class="card-header bg-primary text-white">
                Informasi Pelamar
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nama</dt>
                    <dd class="col-sm-9">{{ $pelamar->nama }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $pelamar->email }}</dd>

                    <dt class="col-sm-3">Telepon</dt>
                    <dd class="col-sm-9">{{ $pelamar->telepon }}</dd>

                    <dt class="col-sm-3">Posisi Dilamar</dt>
                    <dd class="col-sm-9"><!-- WhatsApp Link -->
            <a href="{{ $whatsappUrl }}" 
               target="_blank" 
               class="whatsapp-link"
               title="Hubungi via WhatsApp">
                <i class="fab fa-whatsapp me-2"></i>
                WhatsApp
                <i class="fas fa-external-link-alt ms-2"></i>
            </a>
            
            <a href="tel:{{ $pelamar->telepon }}" class="phone-link me-3 " title="Telepon langsung">
                <i class="fas fa-phone text-primary me-2"></i>
                {{ $pelamar->telepon }}
            </a>
        
        </dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $pelamar->status == 'proses' ? 'warning' : ($pelamar->status == 'interview' ? 'primary' : ($pelamar->status == 'training' ? 'success' : ($pelamar->status == 'ditolak' ? 'danger' : 'secondary'))) }}">
                            {{ ucfirst($pelamar->status) }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">Tanggal Apply</dt>
                    <dd class="col-sm-9">{{ $pelamar->created_at->format('d M Y') }}</dd>

                    @if($pelamar->catatan)
                        <dt class="col-sm-3">Catatan</dt>
                        <dd class="col-sm-9">{{ $pelamar->catatan }}</dd>
                    @endif

                    <dt class="col-sm-3">CV</dt>
                    <dd class="col-sm-9">
                        <a href="{{ asset('storage/' . $pelamar->cv) }}" target="_blank">Lihat CV</a>
                    </dd>

                    <dt class="col-sm-3">KTP</dt>
                    <dd class="col-sm-9">
                        <a href="{{ asset('storage/' . $pelamar->ktp) }}" target="_blank">Lihat KTP</a>
                    </dd>


                </dl>
            </div>
        </div>
    </div>
@endsection

