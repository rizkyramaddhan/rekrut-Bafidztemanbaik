@extends('layouts.app')

@section('title', 'Manajemen Posisi - Sistem Rekrutmen')
@section('page_title', 'Manajemen Posisi')
@section('page_description', 'Kelola semua posisi dan jabatan yang tersedia')
@section('breadcrumb', 'Posisi')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <x-card title="Total Lowongan Posisi" value="{{ $totalPosisi }}" icon="fas fa-users" href="{{ route('posisi') }}" />
<x-card title="Total Status Aktif" value="{{ $totalStatusAktif }}" icon="fas fa-users" href="{{ route('posisi', ['status' => 'aktif']) }}" />
<x-card title="Total Status Non-Aktif" value="{{ $totalStatusNonAktif }}" icon="fas fa-users" href="{{ route('posisi', ['status' => 'non-aktif']) }}" />
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted mb-3">Aksi Cepat</h6>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Posisi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Posisi
        </h5>
        <div class="d-flex gap-2">
            <!-- Filter Status -->
            <select class="form-select form-select-sm" id="statusFilter" onchange="filterByStatus()">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>

            
            <!-- Search -->
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Cari nama posisi..." id="searchInput" onkeyup="searchPosisi()">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="posisiTable">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">No</th>
                        <th class="border-0">
                            <i class="fas fa-briefcase me-1"></i>Nama Posisi
                        </th>
                        <th class="border-0">
                            <i class="fas fa-info-circle me-1"></i>Status
                        </th>
                        <th class="border-0">
                            <i class="fas fa-calendar me-1"></i>Tanggal Dibuat
                        </th>
                        <th class="border-0 text-center">
                            <i class="fas fa-cog me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posisis as $index => $posisi)
                        <tr data-status="{{ $posisi->status }}" data-name="{{ strtolower($posisi->nama_posisi) }}">
                            <td class="align-middle">
                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary-custom rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-briefcase text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $posisi->nama_posisi }}</h6>
                                        <small class="text-muted">{{ Str::limit($posisi->deskripsi, 50) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                @if($posisi->status == 'aktif')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>AKTIF
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-pause-circle me-1"></i>NON-AKTIF
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $posisi->created_at->format('d M Y') }}</span>
                                <small class="d-block text-muted">{{ $posisi->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group" role="group">
                                    <!-- Detail Button -->
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="showDetail({{ $posisi->id }})"
                                            title="Lihat Detail"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-outline-secondary"
                                            onclick="editPosisi({{ $posisi->id }})"
                                            title="Edit Posisi"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <!-- Toggle Status Button -->
                                    @if($posisi->status == 'aktif')
                                        <button class="btn btn-sm btn-outline-warning" 
                                                onclick="toggleStatus({{ $posisi->id }}, 'non-aktif')"
                                                title="Non-Aktifkan"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="toggleStatus({{ $posisi->id }}, 'aktif')"
                                                title="Aktifkan"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Delete Button -->
                                    {{-- <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deletePosisi({{ $posisi->id }})"
                                            title="Hapus Posisi"
                                            data-bs-toggle="tooltip"
                                            {{ $posisi->lamaran_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-briefcase fa-3x mb-3 opacity-50"></i>
                                    <h6>Belum ada data posisi</h6>
                                    <p class="mb-0">Mulai dengan menambahkan posisi baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($posisis) && method_exists($posisis, 'hasPages') && $posisis->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Menampilkan {{ $posisis->firstItem() }} - {{ $posisis->lastItem() }} 
                    dari {{ $posisis->total() }} posisi
                </div>
                {{ $posisis->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Add Position Modal -->
<!-- Add Position Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPositionModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Posisi Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPositionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_posisi" class="form-label">Nama Posisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_posisi" name="nama_posisi" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Posisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Position Modal -->
<!-- Edit Position Modal -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPositionModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Posisi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPositionForm">
                <input type="hidden" id="edit_position_id" name="position_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_posisi" class="form-label">Nama Posisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_posisi" name="nama_posisi" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Posisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Detail Position Modal -->
<!-- Detail Position Modal -->
<div class="modal fade" id="detailPositionModal" tabindex="-1" aria-labelledby="detailPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPositionModalLabel">
                    <i class="fas fa-eye me-2"></i>Detail Posisi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailPositionContent">
                <!-- Detail content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>




<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmationMessage">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips for all icons in the page
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Filter by status (Aktif / Non-Aktif)
        document.getElementById('statusFilter').addEventListener('change', function () {
            filterByStatus();
        });

        // Search for posisi by name
        document.getElementById('searchInput').addEventListener('keyup', function () {
            searchPosisi();
        });

        // Memanggil filterByStatus untuk menyesuaikan tampilan tabel dengan status yang dipilih
    filterByStatus();
    });

    // Filter positions based on the selected status
    function filterByStatus() {
        const filter = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#posisiTable tbody tr[data-status]');

        let found = false;
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === '' || status === filter) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            // alert("Tidak ada posisi yang sesuai dengan status yang dipilih.");
        }
    }

    // Search positions based on the input value in the search bar
    function searchPosisi() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#posisiTable tbody tr[data-name]');

        let found = false;
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(input) || input === '') {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (!found) {
            // alert("Tidak ada posisi yang ditemukan.");
        }
    }

    // Add a new position through form submission
    document.getElementById('addPositionForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('/posisi', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat menambah posisi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambah posisi');
            });
    });

    // Edit position form submission
    document.getElementById('editPositionForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const positionId = document.getElementById('edit_position_id').value;
        const formData = new FormData(this);

        fetch(`/posisi/${positionId}`, {
            method: 'POST', // Should be PUT or PATCH for editing
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat mengupdate posisi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate posisi');
            });
    });

    // Show detail of a specific position
    function showDetail(positionId) {
        fetch(`/posisi/${positionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const posisi = data.data;
                    const content = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Nama Posisi</h6>
                                <p class="fw-bold">${posisi.nama_posisi}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Status</h6>
                                <p><span class="badge bg-${posisi.status === 'aktif' ? 'success' : 'warning'}">${posisi.status.toUpperCase()}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Tanggal Dibuat</h6>
                                <p>${new Date(posisi.created_at).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Terakhir Update</h6>
                                <p>${new Date(posisi.updated_at).toLocaleDateString('id-ID')}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('detailPositionContent').innerHTML = content;
                    const modal = new bootstrap.Modal(document.getElementById('detailPositionModal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil detail posisi');
            });
    }

    // Toggle the status (Aktif / Non-Aktif) for a position
    function toggleStatus(positionId, newStatus) {
        currentPositionId = positionId;
        currentAction = 'toggleStatus';

        const message = newStatus === 'aktif'
            ? 'Apakah Anda yakin ingin mengaktifkan posisi ini?'
            : 'Apakah Anda yakin ingin menonaktifkan posisi ini?';

        document.getElementById('confirmationMessage').innerText = message;
        document.getElementById('confirmActionBtn').onclick = function () {
            executeToggleStatus(positionId, newStatus);
        };

        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();
    }

    // Execute the toggle status for the position
    function executeToggleStatus(positionId, newStatus) {
        fetch(`/posisi/${positionId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat mengubah status posisi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status posisi');
            });
    }

    function deletePosisi(positionId) {
    const confirmation = confirm("Apakah Anda yakin ingin menghapus posisi ini?");
    if (confirmation) {
        fetch(`/posisi/${positionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Posisi berhasil dihapus');
                location.reload(); // Reload halaman setelah posisi dihapus
            } else {
                alert('Terjadi kesalahan saat menghapus posisi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus posisi');
        });
    }
}

// Fungsi untuk mengedit posisi
// Mengambil detail posisi untuk diedit
function editPosisi(positionId) {
   fetch(`/posisi/${positionId}/edit`)  // Pastikan ID yang dikirim valid
    .then(response => response.json())  // Parsing JSON
    .then(data => {
        if (data.success) {
            // Lakukan pengolahan data di sini
            console.log(data.data); // Lakukan pengisian form dengan data posisi
            document.getElementById('edit_position_id').value = data.data.id;
            document.getElementById('edit_nama_posisi').value = data.data.nama_posisi;
            document.getElementById('edit_status').value = data.data.status;
            // Tampilkan modal untuk edit posisi
            new bootstrap.Modal(document.getElementById('editPositionModal')).show();
        } else {
            alert(data.message || 'Posisi tidak ditemukan.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data posisi.');
    });


}



</script>
@endpush

