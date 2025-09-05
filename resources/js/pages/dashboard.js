// resources/js/pages/dashboard.js
export default function initPelamarTable(root) {
  // Ambil id elemen dari data-attribute
  const tableId = root.dataset.tableId || 'pelamarTable';
  const statusFilterId = root.dataset.statusFilterId || 'statusFilter';
  const searchInputId  = root.dataset.searchInputId  || 'searchInput';

  const pelamarTable = document.getElementById(tableId);
  if (!pelamarTable) return;

  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const SUFFIX = `_${tableId}`;

  // ==== Helpers ====
  async function patchJson(url, payload) {
    const resp = await fetch(url, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify(payload),
    });
    return resp.json();
  }
  async function postJson(url, payload) {
    const resp = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify(payload),
    });
    return resp.json();
  }

  // ==== Set filter awal dari URL ====
  (function initFilterFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status) {
      const sf = document.getElementById(statusFilterId);
      if (sf) sf.value = status;
    }
    filterByStatus(); // panggil fungsi lokal (tanpa suffix)
  })();

  // ==== Ubah Status (modal konfirmasi) ====
  let _pending = { id: null, status: null };
  const statusMessages = {
    interview: 'Apakah Anda yakin ingin memproses pelamar ini ke tahap interview?',
    training:  'Apakah Anda yakin ingin memproses pelamar ini ke tahap training?',
    diterima:  'Apakah Anda yakin ingin menerima pelamar ini?',
    ditolak:   'Apakah Anda yakin ingin menolak pelamar ini?',
  };

  function ubahStatus(pelamarId, status) {
    const msg = statusMessages[status] || 'Apakah Anda yakin ingin mengubah status pelamar ini?';
    const msgEl = document.getElementById('statusMessage');
    if (msgEl) msgEl.innerText = msg;
    _pending = { id: pelamarId, status };

    const modalEl = document.getElementById('statusModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }

  const confirmBtn = document.getElementById('confirmStatusBtn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', async () => {
      if (!_pending.id || !_pending.status) return;
      try {
        const data = await patchJson(`/pelamar/${_pending.id}/status`, { status: _pending.status });
        if (data?.success) {
          window.location.reload();
        } else {
          alert('Terjadi kesalahan: ' + (data?.message || 'Gagal mengubah status'));
        }
      } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan saat mengubah status');
      } finally {
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        modal?.hide();
        _pending = { id: null, status: null };
      }
    });
  }

  // ==== Filter & Search ====
  function filterByStatus() {
    const filter = (document.getElementById(statusFilterId)?.value || '').toLowerCase();
    pelamarTable.querySelectorAll('tbody tr[data-status]').forEach((row) => {
      const status = row.getAttribute('data-status');
      row.style.display = (!filter || status === filter) ? '' : 'none';
    });
  }
  function searchPelamar() {
    const q = (document.getElementById(searchInputId)?.value || '').toLowerCase();
    pelamarTable.querySelectorAll('tbody tr[data-name]').forEach((row) => {
      const name = row.getAttribute('data-name') || '';
      row.style.display = name.includes(q) ? '' : 'none';
    });
  }

  // ==== Modal Edit ====
  function openEditModal(id, nama, posisi, status, email, telepon, cv, ktp) {
    document.getElementById('editPelamarId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editPosisi').value = posisi;
    document.getElementById('editStatus').value = status;
    document.getElementById('editEmail').value = email;
    document.getElementById('editTelepon').value = telepon;

    const currentCv  = document.getElementById('currentCv');
    const currentKtp = document.getElementById('currentKtp');
    if (currentCv) {
      currentCv.innerHTML = (cv && cv !== '')
        ? `<small class="text-muted">File saat ini: <a href="/storage/${cv}" target="_blank" class="text-decoration-none"><i class="fas fa-file-pdf me-1"></i>Lihat CV</a></small>`
        : '<small class="text-muted">Belum ada file CV</small>';
    }
    if (currentKtp) {
      currentKtp.innerHTML = (ktp && ktp !== '')
        ? `<small class="text-muted">File saat ini: <a href="/storage/${ktp}" target="_blank" class="text-decoration-none"><i class="fas fa-id-card me-1"></i>Lihat KTP</a></small>`
        : '<small class="text-muted">Belum ada file KTP</small>';
    }
    const cvInput  = document.getElementById('editCv');
    const ktpInput = document.getElementById('editKtp');
    if (cvInput)  cvInput.value  = '';
    if (ktpInput) ktpInput.value = '';
  }

  const editForm = document.getElementById('editForm');
  if (editForm) {
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const pelamarId = document.getElementById('editPelamarId').value;
      const payload = {
        nama:    document.getElementById('editNama').value,
        posisi:  document.getElementById('editPosisi').value,
        status:  document.getElementById('editStatus').value,
        email:   document.getElementById('editEmail').value,
        telepon: document.getElementById('editTelepon').value,
      };
      try {
        const data = await patchJson(`/pelamar/${pelamarId}`, payload);
        if (data?.success) {
          const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal'));
          modal.hide();
          window.location.reload();
        } else {
          alert('Terjadi kesalahan saat mengubah data');
        }
      } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan saat mengubah data');
      }
    });
  }

  // ==== MULTI SELECT & MULTI DELETE (sesuai HTML kamu & scoped ke tabel ini) ====
(function initMultiDelete() {
  const modalEl          = document.getElementById('multiDeleteModal');
  const confirmBtn       = document.getElementById('confirmMultiDeleteBtn');
  const deleteCountEl    = document.getElementById('deleteCount');
  const deleteListEl     = document.getElementById('deleteList');
  const actionBox        = document.querySelector('.multi-delete-actions');
  const selectedCountEl  = document.getElementById('selectedCount');

  // Escape aman untuk nama
  function escapeHtml(str = '') {
    return String(str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // Ambil baris terpilih HANYA dari tabel ini
  function getSelectedRows() {
    const checkboxes = pelamarTable.querySelectorAll('.pelamarCheckbox:checked');
    return Array.from(checkboxes).map(cb => {
      const tr   = cb.closest('tr');
      const id   = cb.value;
      const name = tr?.getAttribute('data-name')
        || tr?.querySelector('h6')?.textContent
        || '';
      return { id, name };
    });
  }

  // Update UI jumlah terpilih + tampil/sembunyi kotak aksi
  function updateSelectedCountUI() {
    const selected = getSelectedRows().length;
    if (selectedCountEl) selectedCountEl.textContent = selected;
    if (actionBox) {
      if (selected > 0) actionBox.classList.add('show');
      else actionBox.classList.remove('show');
    }
  }

  // ——— Ekspos untuk onclick di checkbox baris
  function updateSelectedCount() { updateSelectedCountUI(); }

  // Hapus centang semua
  function clearSelection() {
    pelamarTable.querySelectorAll('.pelamarCheckbox').forEach(cb => (cb.checked = false));
    updateSelectedCountUI();
  }

  // Buka modal + isi daftar sebelum hapus
  function multiDelete() {
    const items = getSelectedRows();
    if (!items.length) {
      window.Swal
        ? Swal.fire('Tidak ada data', 'Pilih minimal 1 pelamar.', 'info')
        : alert('Pilih minimal 1 pelamar.');
      return;
    }

    if (deleteCountEl) deleteCountEl.textContent = items.length;
    if (deleteListEl) {
      deleteListEl.innerHTML = items.map(it => `
        <div class="d-flex align-items-center mb-2">
          <span class="badge bg-light text-dark me-2">${escapeHtml(it.id)}</span>
          <span>${escapeHtml(it.name)}</span>
        </div>
      `).join('');
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  }

  // Kirim hapus saat tombol konfirmasi ditekan
  if (confirmBtn) {
    confirmBtn.addEventListener('click', async () => {
      const ids = getSelectedRows().map(it => it.id);
      if (!ids.length) return;

      confirmBtn.disabled = true;
      try {
        const resp = await fetch('/pelamar/multi-delete', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF,
          },
          body: JSON.stringify({ ids }),
        });
        const data = await resp.json();

        if (data?.success) {
          bootstrap.Modal.getInstance(modalEl)?.hide();
          window.location.reload();
        } else {
          const msg = data?.message || 'Terjadi kesalahan saat menghapus pelamar';
          window.Swal ? Swal.fire('Gagal', msg, 'error') : alert(msg);
        }
      } catch (e) {
        console.error(e);
        window.Swal
          ? Swal.fire('Error', 'Terjadi kesalahan saat menghapus pelamar', 'error')
          : alert('Terjadi kesalahan saat menghapus pelamar');
      } finally {
        confirmBtn.disabled = false;
      }
    });
  }

  // Sinkron awal (jika ada yang sudah tercentang)
  updateSelectedCountUI();

  // ==== Ekspos ke global dengan suffix yang sama seperti di Blade ====
  window[`filterByStatus${SUFFIX}`]    = filterByStatus;
  window[`searchPelamar${SUFFIX}`]     = searchPelamar;
  window[`openEditModal${SUFFIX}`]     = openEditModal;
  window[`ubahStatus${SUFFIX}`]        = ubahStatus;
window[`updateSelectedCount${SUFFIX}`] = updateSelectedCount;
  window[`clearSelection${SUFFIX}`]      = clearSelection;
  window[`multiDelete${SUFFIX}`]         = multiDelete;

  // Alias non-suffix agar onclick="multiDelete()" & "clearSelection()" tetap jalan
  // (aman jika hanya ada satu tabel di halaman)
  if (!window.updateSelectedCount) window.updateSelectedCount = updateSelectedCount;
  if (!window.clearSelection)      window.clearSelection      = clearSelection;
  if (!window.multiDelete)         window.multiDelete         = multiDelete;
})();

}
