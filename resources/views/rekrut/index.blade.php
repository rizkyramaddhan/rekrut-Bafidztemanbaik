@extends('layouts.app')

@section('title', 'Dashboard - Sistem Rekrutmen')
@section('page_title', 'Dashboard Rekrutmen')
@section('page_description', 'Kelola semua aktivitas rekrutmen dan pelamar')
@section('breadcrumb', 'Dashboard')

@section('content')

<style>
.max-height-300 {
    max-height: 300px;
}

.avatar-sm {
    width: 35px;
    height: 35px;
}

.bg-primary-custom {
    background-color: #0d6efd;
}

.multi-delete-actions.show {
    display: block !important;
}
</style>
<!-- Statistics Cards -->
<div class="row">
        <x-card title="Total Pelamar" value="{{ $totalPelamar }}" icon="fas fa-users" href="{{ route('admin.dasbord') }}" />
        <x-card title="Total Status Proses" value="{{ $statusProses }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'proses']) }}" />
<x-card title="Total Status Interview" value="{{ $statusInterview }}" icon="fas fa-users"  href="{{ route('admin.dasbord', ['status' => 'interview']) }}"/>
<x-card title="Total Status Training" value="{{ $totalStatusTraining }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'training']) }}" />
<x-card title="Total Status Di Tolak" value="{{ $totalStatusTolak }}" icon="fas fa-users" href="{{ route('admin.dasbord', ['status' => 'ditolak']) }}" />
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

<!-- Main Content Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Pelamar Terbaru
        </h5>
        <div class="d-flex gap-2">
            <!-- Filter Status -->
            <select class="form-select form-select-sm" id="statusFilter" onchange="filterByStatus()">
                <option value="">Semua Status</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                <option value="training" {{ request('status') == 'training' ? 'selected' : '' }}>Training</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            
            <!-- Search -->
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Cari nama pelamar..." id="searchInput" onkeyup="searchPelamar()">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="pelamarTable">
                <thead class="table-light">
                    <tr>
                        <th class="border-0"></th>
                        <th class="border-0">No</th>
                        <th class="border-0">
                            <i class="fas fa-user me-1"></i>Nama Pelamar
                        </th>
                        <th class="border-0">
                            <i class="fas fa-briefcase me-1"></i>Posisi
                        </th>
                        <th class="border-0">
                            <i class="fas fa-info-circle me-1"></i>Status
                        </th>
                        <th class="border-0">
                            <i class="fas fa-calendar me-1"></i>Tanggal Apply
                        </th>
                        <th class="border-0 text-center">
                            <i class="fas fa-cog me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelamars as $index => $pelamar)
                        <tr data-status="{{ $pelamar->status }}" data-name="{{ strtolower($pelamar->nama) }}">
                            <td class="align-middle">
    <input type="checkbox" class="pelamarCheckbox" value="{{ $pelamar->id }}" onclick="updateSelectedCount()">
</td>

                            <td class="align-middle">
                                <span class="badge bg-light text-dark">{{ $pelamars->firstItem() + $index }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary-custom rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $pelamar->nama }}</h6>
                                        <small class="text-muted">{{ $pelamar->email ?? 'Email tidak tersedia' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
    <span class="badge bg-primary text-white">
    {{ $posisiList[$pelamar->posisi] ?? 'Posisi Tidak Diketahui' }}
</span>
</td>


                            <td class="align-middle">
                                @php
                                    $statusColors = [
                                        'proses' => 'warning',
                                        'interview' => 'info',
                                        'training' => 'success',
                                        'ditolak' => 'danger',
                                        'diterima' => 'success'
                                    ];
                                    $statusIcons = [
                                        'proses' => 'clock',
                                        'interview' => 'calendar-check',
                                        'training' => 'graduation-cap',
                                        'ditolak' => 'times-circle',
                                        'diterima' => 'check-circle'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$pelamar->status] ?? 'secondary' }}">
                                <i class="fas fa-{{ $statusIcons[$pelamar->status] ?? 'question' }} me-1"></i>
                                {{ strtoupper($pelamar->status) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $pelamar->created_at->format('d M Y') }}</span>
                                <small class="d-block text-muted">{{ $pelamar->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="align-middle text-center">
    <div class="btn-group" role="group">
        <!-- Detail Button -->
        <a href="{{ route('pelamar.show', $pelamar->id) }}" 
           class="btn btn-sm btn-outline-primary" 
           title="Lihat Detail"
           data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>

        <!-- Status Action Buttons -->
        @if ($pelamar->status == 'proses')
    <button class="btn btn-sm btn-success" 
            onclick="ubahStatus({{ $pelamar->id }}, 'interview')"
            title="Proses ke Interview">
        <i class="fas fa-arrow-right"></i>
    </button>
    <button class="btn btn-sm btn-danger" 
            onclick="ubahStatus({{ $pelamar->id }}, 'ditolak')"
            title="Tolak Pelamar">
        <i class="fas fa-times"></i>
    </button>
@elseif ($pelamar->status == 'interview')
    <button class="btn btn-sm btn-primary" 
            onclick="ubahStatus({{ $pelamar->id }}, 'training')"
            title="Proses ke Training">
        <i class="fas fa-arrow-right"></i>
    </button>
    <button class="btn btn-sm btn-danger" 
            onclick="ubahStatus({{ $pelamar->id }}, 'ditolak')"
            title="Tolak Pelamar">
        <i class="fas fa-times"></i>
    </button>
@elseif ($pelamar->status == 'training')
    <button class="btn btn-sm btn-success" 
            onclick="ubahStatus({{ $pelamar->id }}, 'diterima')"
            title="Terima Pelamar">
        <i class="fas fa-check"></i>
    </button>
    <button class="btn btn-sm btn-danger" 
            onclick="ubahStatus({{ $pelamar->id }}, 'ditolak')"
            title="Tolak Pelamar">
        <i class="fas fa-times"></i>
    </button>
    
@endif
        
        <!-- Edit Button -->
<a href="#"
   class="btn btn-sm btn-outline-secondary"
   title="Edit Data"
   data-bs-toggle="modal" 
   data-bs-target="#editModal"
   onclick="openEditModal(
       {{ $pelamar->id }}, 
       '{{ addslashes($pelamar->nama) }}', 
       '{{ $pelamar->posisi_id ?? $pelamar->posisi }}', 
       '{{ $pelamar->status }}',
       '{{ addslashes($pelamar->email) }}',
       '{{ $pelamar->telepon }}',
       '{{ $pelamar->cv }}',
       '{{ $pelamar->ktp }}'
   )">
    <i class="fas fa-edit"></i>
</a>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                    <h6>Belum ada data pelamar</h6>
                                    <p class="mb-0">Mulai dengan menambahkan pelamar baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- @if(isset($pelamars) && $pelamars->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan {{ $pelamars->firstItem() }} - {{ $pelamars->lastItem() }} 
                    dari {{ $pelamars->total() }} pelamar
                </div>
                {{ $pelamars->links() }}
            </div>
        </div>
    @endif --}}

    {{-- ...existing code... --}}
@if(isset($pelamars) && method_exists($pelamars, 'hasPages') && $pelamars->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $pelamars->firstItem() }} - {{ $pelamars->lastItem() }} 
                dari {{ $pelamars->total() }} pelamar
            </div>
            {{ $pelamars->links() }}
        </div>
    </div>
@endif
{{-- ...existing code... --}}
</div>

<!-- Multi Delete Modal -->
    <div class="modal fade" id="multiDeleteModal" tabindex="-1" aria-labelledby="multiDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="multiDeleteModalLabel">
                        <i class="fas fa-trash me-2"></i>Konfirmasi Hapus Multiple
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label for="editNama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPosisi" class="form-label">
                                    <i class="fas fa-briefcase me-1"></i>Posisi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('posisi') is-invalid @enderror" 
                                        id="editPosisi" 
                                        name="posisi" 
                                        required>
                                    <option value="">-- Pilih Posisi --</option>
                                    @if(isset($posisis) && $posisis->isNotEmpty())
                                        @foreach($posisis as $posisiItem)
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
                                <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editTelepon" class="form-label">Telepon <span class="text-danger">*</span></label>
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
                                <input type="file" class="form-control" id="editCv" name="cv" accept=".pdf">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah file CV</div>
                                <div id="currentCv" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editKtp" class="form-label">KTP (PDF/JPG/PNG)</label>
                                <input type="file" class="form-control" id="editKtp" name="ktp" accept=".pdf,.jpg,.jpeg,.png">
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

@push('scripts')
<script>
    // Variables for status change
    let currentPelamarId = null;
    let newStatus = null;
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function () {
    // Ambil nilai status dari query parameter jika ada
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Atur dropdown berdasarkan parameter URL jika ada
    if (status) {
        document.getElementById('statusFilter').value = status;
    }

    // Panggil filterByStatus untuk memfilter data pelamar saat halaman dimuat
    filterByStatus();
});

    
// Fungsi untuk mengubah status pelamar
    function ubahStatus(pelamarId, status) {
        const statusMessages = {
            'interview': 'Apakah Anda yakin ingin memproses pelamar ini ke tahap interview?',
            'training': 'Apakah Anda yakin ingin memproses pelamar ini ke tahap training?',
            'diterima': 'Apakah Anda yakin ingin menerima pelamar ini?',
            'ditolak': 'Apakah Anda yakin ingin menolak pelamar ini?'
        };

        const statusMessageElement = document.getElementById('statusMessage');
        if (statusMessageElement) {
            statusMessageElement.innerText = statusMessages[status] || 'Apakah Anda yakin ingin mengubah status pelamar ini?';
        }

        // Menampilkan modal konfirmasi
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
        
        // Menyimpan status yang dipilih untuk diproses
        document.getElementById('confirmStatusBtn').addEventListener('click', function() {
            fetch(`/pelamar/${pelamarId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh halaman setelah status berhasil diubah
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            });
        });
    }
    
    // Confirm status change
    document.getElementById('confirmStatusBtn').addEventListener('click', function() {
        if (currentPelamarId && newStatus) {
            // Here you would typically send an AJAX request
            fetch(`/pelamar/${currentPelamarId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            });
        }
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        modal.hide();
    });
    
    // Filter by status
    function filterByStatus() {
        const filter = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#pelamarTable tbody tr[data-status]');
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === '' || status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Search pelamar
    function searchPelamar() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#pelamarTable tbody tr[data-name]');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Fungsi untuk membuka modal edit dan mengisi data
function openEditModal(id, nama, posisi, status, email, telepon, cv, ktp) {
    // Set input field values
    document.getElementById('editPelamarId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editPosisi').value = posisi;
    document.getElementById('editStatus').value = status;
    document.getElementById('editEmail').value = email;
    document.getElementById('editTelepon').value = telepon;
    
    // Display current files
    const currentCvElement = document.getElementById('currentCv');
    const currentKtpElement = document.getElementById('currentKtp');
    
    if (cv && cv !== '') {
        currentCvElement.innerHTML = `<small class="text-muted">File saat ini: <a href="/storage/${cv}" target="_blank" class="text-decoration-none"><i class="fas fa-file-pdf me-1"></i>Lihat CV</a></small>`;
    } else {
        currentCvElement.innerHTML = '<small class="text-muted">Belum ada file CV</small>';
    }
    
    if (ktp && ktp !== '') {
        currentKtpElement.innerHTML = `<small class="text-muted">File saat ini: <a href="/storage/${ktp}" target="_blank" class="text-decoration-none"><i class="fas fa-id-card me-1"></i>Lihat KTP</a></small>`;
    } else {
        currentKtpElement.innerHTML = '<small class="text-muted">Belum ada file KTP</small>';
    }
    
    // Clear file inputs
    document.getElementById('editCv').value = '';
    document.getElementById('editKtp').value = '';
}

// Menangani pengiriman form edit
document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const pelamarId = document.getElementById('editPelamarId').value;
    const nama = document.getElementById('editNama').value;
    const posisi = document.getElementById('editPosisi').value;
    const status = document.getElementById('editStatus').value;

    // Kirim data ke server untuk diperbarui
    fetch(`/pelamar/${pelamarId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ nama: nama, posisi: posisi, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tutup modal dan segarkan tabel
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.hide();
            location.reload(); // Refresh halaman setelah update
        } else {
            alert('Terjadi kesalahan saat mengubah data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah data');
    });
});

// Update selected count
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.pelamarCheckbox:checked').length;
    document.getElementById('selectedCount').innerText = selectedCount;
    // Show or hide the multi-delete actions based on selected count
    const multiDeleteActions = document.querySelector('.multi-delete-actions');
    if (selectedCount > 0) {
        multiDeleteActions.classList.add('show');
    } else {
        multiDeleteActions.classList.remove('show');
    }
}

// Clear selected checkboxes
function clearSelection() {
    const checkboxes = document.querySelectorAll('.pelamarCheckbox');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    updateSelectedCount();
}

// Multi delete action
function multiDelete() {
    const selectedIds = [];
    const checkboxes = document.querySelectorAll('.pelamarCheckbox:checked');
    checkboxes.forEach(checkbox => selectedIds.push(checkbox.value));
    
    if (selectedIds.length > 0) {
        // Call the API to delete selected pelamars
        fetch('/pelamar/multi-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page after deletion
            } else {
                alert('Terjadi kesalahan saat menghapus pelamar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pelamar');
        });
    }
}


</script>
@endpush