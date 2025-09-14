@props([
    // Data utama
    'pelamars', // LengthAwarePaginator / Collection of pelamar
    'posisiList' => [], // mapping posisi_id => label

    // Opsi UI
    'statusOptions' => ['proses', 'interview', 'training', 'ditolak'],
    'showFooter' => true, // tampilkan pagination footer
    'enableSearch' => true,
    'enableFilter' => true,

    // Rute & kolom dinamis
    'detailRoute' => 'pelamar.show', // nama route() untuk tombol detail
    'statusField' => 'status', // nama properti status di model
    'posisiField' => 'posisi', // nama properti posisi di model (atau posisi_id)
    'namaField' => 'nama',
    'emailField' => 'email',
    'teleponField' => 'telepon',
    'cvField' => 'cv',
    'ktpField' => 'ktp',

    // ID unik agar JS ter-scope (ubah bila punya lebih dari satu tabel di halaman)
    'tableId' => 'pelamarTable',
    'statusFilterId' => 'statusFilter',
    'searchInputId' => 'searchInput',
])

@php
    // Map warna & ikon status (bisa dioverride dari luar via slot <x-slot:name="statusMaps">)
    $statusColors = [
        'proses' => 'warning',
        'interview' => 'info',
        'training' => 'success',
        'ditolak' => 'danger',
        'diterima' => 'success',
    ];
    $statusIcons = [
        'proses' => 'clock',
        'interview' => 'calendar-check',
        'training' => 'graduation-cap',
        'ditolak' => 'times-circle',
        'diterima' => 'check-circle',
    ];

    // Helper aman
    $get = fn($item, $key, $fallback = '') => data_get($item, $key, $fallback);
@endphp

<!-- Main Content Table -->
<div
    {{ $attributes->merge([
        'class' => 'card',
        'data-module' => 'pelamarTable',
        'data-table-id' => $tableId,
        'data-status-filter-id' => $statusFilterId,
        'data-search-input-id' => $searchInputId,
    ]) }}>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Pelamar Terbaru
        </h5>

        <div class="d-flex gap-2">
            @if ($enableFilter)
                <!-- Filter Status -->
                <select class="form-select form-select-sm" id="{{ $statusFilterId }}"
                    onchange="filterByStatus_{{ $tableId }}()">
                    <option value="">Semua Status</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}" {{ request('status') == $opt ? 'selected' : '' }}>
                            {{ ucfirst($opt) }}
                        </option>
                    @endforeach
                </select>
            @endif

            @if ($enableSearch)
                <!-- Search -->
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Cari nama pelamar..."
                        id="{{ $searchInputId }}" onkeyup="searchPelamar_{{ $tableId }}()">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="{{ $tableId }}">
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
                        @php
                            $status = $get($pelamar, $statusField);
                            $nama = $get($pelamar, $namaField);
                            $email = $get($pelamar, $emailField);
                            $posisiVal = $get($pelamar, $posisiField);
                            $createdAt = $get($pelamar, 'created_at');
                        @endphp
                        <tr data-status="{{ $status }}" data-name="{{ strtolower($nama) }}">
                            <td class="align-middle">
                                <input type="checkbox" class="pelamarCheckbox" value="{{ $pelamar->id }}"
                                    onclick="updateSelectedCount_{{ $tableId }}()">
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-light text-dark">
                                    {{ method_exists($pelamars, 'firstItem') ? $pelamars->firstItem() + $index : $index + 1 }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="avatar-sm bg-primary-custom rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $nama }}</h6>
                                        <small class="text-muted">{{ $email ?? 'Email tidak tersedia' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-primary text-white">
                                    {{ $posisiList[$posisiVal] ?? 'Posisi Tidak Diketahui' }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                    <i class="fas fa-{{ $statusIcons[$status] ?? 'question' }} me-1"></i>
                                    {{ strtoupper($status) }}
                                </span>
                            </td>

                            <td class="align-middle">
                                @if ($createdAt)
                                    <span class="text-muted">{{ $createdAt->format('d M Y') }}</span>
                                    <small class="d-block text-muted">{{ $createdAt->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="align-middle text-center">
                                <div class="btn-group" role="group">
                                    <!-- Detail Button -->
                                    <a href="{{ route($detailRoute, $pelamar->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Lihat Detail"
                                        data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Status Action Buttons -->
                                    @if ($status === 'proses')
                                        <button class="btn btn-sm btn-success"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'interview')"
                                            title="Proses ke Interview">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'ditolak')"
                                            title="Tolak Pelamar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif ($status === 'interview')
                                        <button class="btn btn-sm btn-primary"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'training')"
                                            title="Proses ke Training">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'ditolak')"
                                            title="Tolak Pelamar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif ($status === 'training')
                                        <button class="btn btn-sm btn-success"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'diterima')"
                                            title="Terima Pelamar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="ubahStatus_{{ $tableId }}({{ $pelamar->id }}, 'ditolak')"
                                            title="Tolak Pelamar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <!-- Edit Button -->
                                    <a href="#" class="btn btn-sm btn-outline-secondary" title="Edit Data"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="openEditModal_{{ $tableId }}(
                                        {{ $pelamar->id }},
                                        '{{ addslashes($nama) }}',
                                        '{{ addslashes($posisiVal) }}',
                                        '{{ addslashes($status) }}',
                                        '{{ addslashes($get($pelamar, $emailField)) }}',
                                        '{{ addslashes($get($pelamar, $teleponField)) }}',
                                        '{{ addslashes($get($pelamar, $cvField)) }}',
                                        '{{ addslashes($get($pelamar, $ktpField)) }}'
                                   )">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Slot untuk aksi ekstra (opsional) --}}
                                    {{ $extraActions ?? '' }}
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

    {{-- Improved Card Footer with Professional Pagination --}}
    @if ($showFooter && isset($pelamars) && method_exists($pelamars, 'hasPages') && $pelamars->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                {{-- Pagination Links --}}
                <div class="pagination-wrapper">
                    {{ $pelamars->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
