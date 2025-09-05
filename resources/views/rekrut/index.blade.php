@extends('layouts.app')

@section('title', 'Dashboard - Sistem Rekrutmen')
@section('page_title', 'Dashboard Rekrutmen')
@section('page_description', 'Kelola semua aktivitas rekrutmen dan pelamar')
@section('breadcrumb', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <x-card title="Total Pelamar" value="{{ $totalPelamar }}" icon="fas fa-users" href="{{ route('admin.dasbord') }}" />
        <x-card title="Total Status Proses" value="{{ $statusProses }}" icon="fas fa-users"
            href="{{ route('admin.dasbord', ['status' => 'proses']) }}" />
        <x-card title="Total Status Interview" value="{{ $statusInterview }}" icon="fas fa-users"
            href="{{ route('admin.dasbord', ['status' => 'interview']) }}" />
        <x-card title="Total Status Training" value="{{ $totalStatusTraining }}" icon="fas fa-users"
            href="{{ route('admin.dasbord', ['status' => 'training']) }}" />
        <x-card title="Total Status Di Tolak" value="{{ $totalStatusTolak }}" icon="fas fa-users"
            href="{{ route('admin.dasbord', ['status' => 'ditolak']) }}" />
    </div>
    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Aksi Cepat</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('rekrutmen.form') }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Pelamar Baru
                        </a>
                        <a href="{{ route('export.excel') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-excel me-2"></i>Ekspor ke Excel
                        </a>


                        <!-- Multi Delete Actions -->
                        <div class="multi-delete-actions d-none">
                            <button class="btn btn-danger" onclick="multiDelete()">
                                <i class="fas fa-trash me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearSelection()">
                                <i class="fas fa-times me-2"></i>Batalkan Pilihan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-datatable data-module="pelamarTable" :pelamars="$pelamars" :posisiList="$posisiList" detailRoute="pelamar.show"
        tableId="pelamarTable" statusFilterId="statusFilter" searchInputId="searchInput" :statusOptions="['proses', 'interview', 'training', 'ditolak']" :showFooter="true"
        :enableSearch="true" :enableFilter="true" />


    <!-- Multi Delete Modal -->
    <div class="modal fade" id="multiDeleteModal" tabindex="-1" aria-labelledby="multiDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="multiDeleteModalLabel">
                        <i class="fas fa-trash me-2"></i>Konfirmasi Hapus Multiple
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <p>Anda akan menghapus <strong><span id="deleteCount">0</span> pelamar</strong> berikut:</p>
                    <div id="deleteList" class="bg-light p-3 rounded max-height-300 overflow-auto">
                        <!-- List akan diisi oleh JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn btn-danger" id="confirmMultiDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Pelamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="editPelamarId" name="pelamar_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editNama" class="form-label">Nama <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editNama" name="nama" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPosisi" class="form-label">
                                        <i class="fas fa-briefcase me-1"></i>Posisi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('posisi') is-invalid @enderror" id="editPosisi"
                                        name="posisi" required>
                                        <option value="">-- Pilih Posisi --</option>
                                        @if (isset($posisis) && $posisis->isNotEmpty())
                                            @foreach ($posisis as $posisiItem)
                                                <option value="{{ $posisiItem->id }}">
                                                    {{ $posisiItem->nama_posisi }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option disabled>Belum ada posisi yang terbuka saat ini</option>
                                        @endif
                                    </select>
                                    @error('posisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Pilih posisi yang Anda lamar.</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editTelepon" class="form-label">Telepon <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="editTelepon" name="telepon" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="proses">Proses</option>
                                <option value="interview">Interview</option>
                                <option value="training">Training</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editCv" class="form-label">CV (PDF)</label>
                                    <input type="file" class="form-control" id="editCv" name="cv"
                                        accept=".pdf">
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah file CV</div>
                                    <div id="currentCv" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editKtp" class="form-label">KTP (PDF/JPG/PNG)</label>
                                    <input type="file" class="form-control" id="editKtp" name="ktp"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah file KTP</div>
                                    <div id="currentKtp" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Status Change Confirmation Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Konfirmasi Perubahan Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="statusMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusBtn">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection
