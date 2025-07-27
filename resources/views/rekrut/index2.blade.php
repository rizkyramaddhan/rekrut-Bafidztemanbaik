@extends('layouts.app')

@section('title', 'Dashboard - Sistem Rekrutmen')
@section('page_title', 'Dashboard Rekrutmen')
@section('page_description', 'Kelola semua aktivitas rekrutmen dan pelamar')
@section('breadcrumb', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="text-primary mb-0">{{ $totalPelamars ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Pelamar</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="text-warning mb-0">{{ $prosesPelamars ?? 0 }}</h3>
                        <p class="text-muted mb-0">Sedang Proses</p>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="text-info mb-0">{{ $interviewPelamars ?? 0 }}</h3>
                        <p class="text-muted mb-0">Interview</p>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="text-success mb-0">{{ $trainingPelamars ?? 0 }}</h3>
                        <p class="text-muted mb-0">Training</p>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-3">Aksi Cepat</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('rekrutmen.form') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pelamar Baru
                    </a>
                    <a href="" class="btn btn-outline-success">
                        <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
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
                <option value="proses">Proses</option>
                <option value="interview">Interview</option>
                <option value="training">Training</option>
                <option value="ditolak">Ditolak</option>
                <option value="diterima">Diterima</option>
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
        <!-- Multi Select Controls -->
        <div class="bg-light p-3 border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    <label class="form-check-label fw-bold" for="selectAll">
                        Pilih Semua yang Ditampilkan
                    </label>
                </div>
                
                <div class="d-flex gap-2">
                    <!-- Quick Select by Status -->
                    <button class="btn btn-sm btn-outline-warning" onclick="selectByStatus('proses')">
                        <i class="fas fa-clock me-1"></i>Pilih Proses
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="selectByStatus('interview')">
                        <i class="fas fa-calendar-check me-1"></i>Pilih Interview
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="selectByStatus('training')">
                        <i class="fas fa-graduation-cap me-1"></i>Pilih Training
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="selectByStatus('ditolak')">
                        <i class="fas fa-times-circle me-1"></i>Pilih Ditolak
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="pelamarTable">
                <thead class="table-light">
                    <tr>
                        <th class="border-0" style="width: 50px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllTable" onchange="toggleSelectAllTable()">
                            </div>
                        </th>
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
                        <tr data-status="{{ $pelamar->status }}" data-name="{{ strtolower($pelamar->nama) }}" data-id="{{ $pelamar->id }}">
                            <td class="align-middle">
                                <div class="form-check">
                                    <input class="form-check-input pelamar-checkbox" 
                                           type="checkbox" 
                                           value="{{ $pelamar->id }}" 
                                           data-status="{{ $pelamar->status }}"
                                           onchange="updateSelectedCount()">
                                </div>
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
                                <span class="badge bg-primary text-white">{{ strtoupper($pelamar->posisi) }}</span>
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
                                <span class="badge bg-{{ $statusColors[$pelamar->status] ?? 'secondary' }} ">
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
                                    @elseif ($pelamar->status == 'diterima' || $pelamar->status == 'ditolak')
                                        <!-- Tidak ada tombol jika sudah diterima atau ditolak -->
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-check-circle"></i> Karyawan
                                        </button>
                                    @endif

                                    <!-- Edit Button -->
                                    <a href="" 
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Edit Data"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
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

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Konfirmasi Perubahan Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="statusMessage">Apakah Anda yakin ingin mengubah status pelamar ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusBtn">Konfirmasi</button>
                </div>
            </div>
        </div>
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
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables for status change
        let currentPelamarId = null;
        let newStatus = null;

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Function to show modal and handle status change confirmation
        function ubahStatus(pelamarId, status) {
            currentPelamarId = pelamarId;
            newStatus = status;

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

            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        // Confirm status change
        document.getElementById('confirmStatusBtn').addEventListener('click', function() {
            if (currentPelamarId && newStatus) {
                // Send PATCH request to update status
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
                        location.reload();  // Refresh page after success
                    } else {
                        alert(data.message);  // Show error message if status can't be changed
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status');
                });
            }

            // Close modal after confirming
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
                const name = row.getAttribute('data-name').toLowerCase();
                if (name.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
