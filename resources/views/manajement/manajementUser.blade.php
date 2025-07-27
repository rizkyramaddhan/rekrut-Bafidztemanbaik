@extends('layouts.app')

@section('title', 'Manajemen Akun - Sistem Rekrutmen')
@section('page_title', 'Manajemen Akun')
@section('page_description', 'Kelola semua akun pengguna dalam sistem')
@section('breadcrumb', 'Akun')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <x-card title="Total Account" value="{{ $totalAccount }}" icon="fas fa-users" />
        <x-card title="Total Status Aktif" value="{{ $statusAktif }}" icon="fas fa-users" />
        <x-card title="Total Status Non-Aktif" value="{{ $statusNonAktif }}" icon="fas fa-users" />
    </div>

    <!-- Quick Actions -->
    <div class="mb-4">
        <h5>Aksi Cepat</h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAccountModal" onclick="addAccount()">
                <i class="fas fa-plus"></i> Tambah Akun Baru
            </button>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> Daftar Akun
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="row g-2">
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Cari nama akun" id="searchInput">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%"><i class="fas fa-user"></i> Nama Akun</th>
                            <th width="15%"><i class="fas fa-phone"></i> Email </th>
                            <th width="15%"><i class="fas fa-building"></i> Role </th>
                            <th width="12%"><i class="fas fa-toggle-on"></i> Status</th>
                            <th width="15%"><i class="fas fa-calendar"></i> Tanggal Dibuat</th>
                            <th width="18%"><i class="fas fa-cog"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
    <tr data-status="{{ strtolower($user->status ?? 'suspended') }}" 
        data-name="{{ strtolower($user->name) }}" 
        data-email="{{ strtolower($user->email) }}"
        data-role="{{ strtolower($user->role ?? 'tidak ada') }}">
        <td>{{ $users->firstItem() + $loop->index }}</td>
        <td>
            <div class="d-flex align-items-center">
                
                <div>
                    <strong>{{ $user->name }}</strong>
                    <br><small class="text-muted">{{ $user->email }}</small>
                </div>
            </div>
        </td>
        <td>
            <span class="badge bg-info">{{ $user->email ?? 'Tidak ada' }}</span>  
        </td>
        <td>
            <span class="badge bg-secondary">{{ $user->role ?? 'Tidak ada' }}</span>  
        </td>
        <td>
            @if($user->status == 'aktif')
                <span class="badge bg-success">
                    <i class="fas fa-check-circle"></i> Aktif
                </span>
            @elseif($user->status == 'nonaktif')
                <span class="badge bg-warning">
                    <i class="fas fa-pause-circle"></i> Non-Aktif
                </span>
            @else
                <span class="badge bg-danger">
                    <i class="fas fa-ban"></i> Suspended
                </span>
            @endif
        </td>
        <td>
            <small class="text-muted">
                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
            </small>
        </td>
        <td>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-info" onclick="viewAccount({{ $user->id }})" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="editAccount({{ $user->id }})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                @if($user->status == 'aktif')
                    <button type="button" class="btn btn-outline-secondary" onclick="deactivateAccount({{ $user->id }})" title="Non-aktifkan">
                        <i class="fas fa-pause"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-outline-success" onclick="activateAccount({{ $user->id }})" title="Aktifkan">
                        <i class="fas fa-play"></i>
                    </button>
                @endif
                <button type="button" class="btn btn-outline-danger" onclick="deleteAccount({{ $user->id }})" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr id="emptyState">
        <td colspan="7" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-briefcase fa-3x mb-3 opacity-50"></i>
                <h5>Belum ada data akun</h5>
                <p>Mulai dengan menambahkan akun baru</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                    <i class="fas fa-plus"></i> Tambah Akun Baru
                </button>
            </div>
        </td>
    </tr>
@endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} 
                        dari {{ $users->total() }} hasil
                    </div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Akun -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountModalLabel"><i class="fas fa-plus"></i> <span id="modalTitle">Tambah Akun Baru</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="accountForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="accountId" name="account_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" id="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Non-aktif</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <span id="submitBtn">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    // CSRF Token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Search functionality
    $('#searchInput').on('keyup', function() {
        performSearch();
    });

    $('#searchBtn').on('click', function() {
        performSearch();
    });

    // Status filter functionality
    $('#statusFilter').on('change', function() {
        performFilter();
    });

    // Form submission
    $('#accountForm').on('submit', function(e) {
        e.preventDefault();
        submitAccountForm();
    });

    // Modal events
    $('#addAccountModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});

// Search function
function performSearch() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const statusFilter = $('#statusFilter').val().toLowerCase();
    
    $('tbody tr').each(function() {
        if ($(this).attr('id') === 'emptyState') return;
        
        const name = $(this).data('name') || '';
        const email = $(this).data('email') || '';
        const role = $(this).data('role') || '';
        const status = $(this).data('status') || '';
        
        const matchesSearch = name.includes(searchTerm) || 
                             email.includes(searchTerm) || 
                             role.includes(searchTerm);
        
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    toggleEmptyState();
}

// Filter function
function performFilter() {
    const statusFilter = $('#statusFilter').val().toLowerCase();
    const searchTerm = $('#searchInput').val().toLowerCase();
    
    $('tbody tr').each(function() {
        if ($(this).attr('id') === 'emptyState') return;
        
        const name = $(this).data('name') || '';
        const email = $(this).data('email') || '';
        const role = $(this).data('role') || '';
        const status = $(this).data('status') || '';
        
        const matchesSearch = !searchTerm || 
                             name.includes(searchTerm) || 
                             email.includes(searchTerm) || 
                             role.includes(searchTerm);
        
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    toggleEmptyState();
}

// Toggle empty state visibility
function toggleEmptyState() {
    const visibleRows = $('tbody tr:visible').not('#emptyState').length;
    
    if (visibleRows === 0) {
        if ($('#emptyState').length === 0) {
            $('tbody').append(`
                <tr id="emptyState">
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-search fa-3x mb-3 opacity-50"></i>
                            <h5>Tidak ada data yang ditemukan</h5>
                            <p>Coba gunakan kata kunci pencarian yang berbeda</p>
                        </div>
                    </td>
                </tr>
            `);
        }
        $('#emptyState').show();
    } else {
        $('#emptyState').hide();
    }
}

// Add new account
function addAccount() {
    resetForm();
    $('#modalTitle').text('Tambah Akun Baru');
    $('#submitBtn').text('Simpan');
    $('#formMethod').val('POST');
    $('#accountId').val('');
    
    // Reset password fields menjadi required untuk tambah akun baru
    $('#password').prop('required', true);
    $('#password_confirmation').prop('required', true);
}

// View account details
function viewAccount(id) {
    // Show loading dengan SweetAlert2
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/manajement/accounts/${id}`,
        type: 'GET',
        success: function(response) {
            Swal.close();
            if (response.success) {
                showAccountDetails(response.data);
            } else {
                showAlert('error', 'Gagal mengambil data akun');
            }
        },
        error: function() {
            Swal.close();
            showAlert('error', 'Terjadi kesalahan saat mengambil data');
        }
    });
}

// Edit account
function editAccount(id) {
    // Show loading dengan SweetAlert2
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/manajement/accounts/${id}`,
        type: 'GET',
        success: function(response) {
            Swal.close();
            if (response.success) {
                populateForm(response.data);
                $('#modalTitle').text('Edit Akun');
                $('#submitBtn').text('Update');
                $('#formMethod').val('PUT');
                $('#accountId').val(id);
                
                // Untuk edit, password tidak wajib diisi
                $('#password').prop('required', false);
                $('#password_confirmation').prop('required', false);
                
                $('#addAccountModal').modal('show');
            } else {
                showAlert('error', 'Gagal mengambil data akun');
            }
        },
        error: function() {
            Swal.close();
            showAlert('error', 'Terjadi kesalahan saat mengambil data');
        }
    });
}

// Fungsi untuk mengaktifkan/menonaktifkan akun (mengikuti pola dashboard)
function activateAccount(id) {
    toggleAccountStatus(id, 'aktif', 'Aktifkan', '#28a745');
}

function deactivateAccount(id) {
    toggleAccountStatus(id, 'nonaktif', 'Nonaktifkan', '#ffc107');
}

// Fungsi gabungan untuk toggle status (mengikuti pola dari dashboard)
function toggleAccountStatus(id, targetStatus, action, color) {
    const statusMessages = {
        'aktif': 'Apakah Anda yakin ingin mengaktifkan akun ini?',
        'nonaktif': 'Apakah Anda yakin ingin menonaktifkan akun ini?',
        'suspended': 'Apakah Anda yakin ingin mensuspend akun ini?'
    };

    Swal.fire({
        title: 'Konfirmasi',
        text: statusMessages[targetStatus] || `Yakin ingin ${action.toLowerCase()} akun ini?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: color,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Ya, ${action}`,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading dengan timer lebih pendek
            const loadingAlert = Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX request untuk mengubah status
            fetch(`/manajement/accounts/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: targetStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tutup loading dan tampilkan success dengan durasi pendek
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload langsung setelah notifikasi
                        location.reload();
                    });
                } else {
                    Swal.close();
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat mengubah status akun');
            });
        }
    });
}

// Delete account (mengikuti pola dashboard)
function deleteAccount(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Yakin ingin menghapus akun ini? Data yang sudah dihapus tidak dapat dikembalikan!',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading dengan timer lebih pendek
            const loadingAlert = Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX request untuk menghapus
            fetch(`/manajement/accounts/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tutup loading dan tampilkan success dengan durasi pendek
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload langsung setelah notifikasi
                        location.reload();
                    });
                } else {
                    Swal.close();
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menghapus akun');
            });
        }
    });
}

// Submit account form dengan reload (mengikuti pola dashboard)
function submitAccountForm() {
    const formData = new FormData($('#accountForm')[0]);
    const method = $('#formMethod').val();
    const id = $('#accountId').val();
    
    let url = '/manajement/accounts';
    if (method === 'PUT') {
        url = `/manajement/accounts/${id}`;
        formData.append('_method', 'PUT');
    }
    
    // Show loading pada tombol
    const originalText = $('#submitBtn').html();
    $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    $('#submitBtn').prop('disabled', true);
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                $('#addAccountModal').modal('hide');
                
                // Tampilkan success message dengan durasi pendek
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload langsung setelah modal ditutup
                    location.reload();
                });
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                displayValidationErrors(response.errors);
            } else {
                showAlert('error', 'Terjadi kesalahan saat menyimpan data');
            }
        },
        complete: function() {
            $('#submitBtn').html(originalText).prop('disabled', false);
        }
    });
}

// Show account details in modal
function showAccountDetails(account) {
    Swal.fire({
        title: 'Detail Akun',
        html: `
            <div class="text-start">
                <div class="row mb-3">
                    <div class="col-4"><strong>Nama:</strong></div>
                    <div class="col-8">${account.name}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Email:</strong></div>
                    <div class="col-8">${account.email}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Role:</strong></div>
                    <div class="col-8">
                        <span class="badge bg-secondary">${account.role || 'Tidak ada'}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Status:</strong></div>
                    <div class="col-8">
                        <span class="badge bg-${getStatusBadgeClass(account.status)}">
                            ${getStatusText(account.status)}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Dibuat:</strong></div>
                    <div class="col-8">${formatDate(account.created_at)}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Diupdate:</strong></div>
                    <div class="col-8">${formatDate(account.updated_at)}</div>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '500px'
    });
}

// Populate form with account data
function populateForm(account) {
    $('#name').val(account.name);
    $('#email').val(account.email);
    $('#role').val(account.role);
    $('#status').val(account.status);
    // Password fields dikosongkan untuk edit
    $('#password').val('');
    $('#password_confirmation').val('');
}

// Reset form
function resetForm() {
    $('#accountForm')[0].reset();
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// Display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Display new errors
    Object.keys(errors).forEach(field => {
        const input = $(`#${field}`);
        if (input.length) {
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
        }
    });
}

// Show alert message dengan SweetAlert2 (sama seperti dashboard)
function showAlert(type, message) {
    const config = {
        icon: type,
        title: message,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        timerProgressBar: true
    };
    
    Swal.fire(config);
}

// Helper functions
function getStatusBadgeClass(status) {
    switch (status) {
        case 'aktif': return 'success';
        case 'nonaktif': return 'warning';
        case 'suspended': return 'danger';
        default: return 'secondary';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'aktif': return 'Aktif';
        case 'nonaktif': return 'Non-Aktif';
        case 'suspended': return 'Suspended';
        default: return 'Tidak Diketahui';
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
</script>
@endpush
