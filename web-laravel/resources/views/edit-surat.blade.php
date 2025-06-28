<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fa;
    }
    .sidebar {
      width: 250px;
      background-color: #27B3C6;
      color: white;
      position: fixed;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .sidebar .nav-link {
      color: white;
    }
    .sidebar .nav-link:hover {
      background-color: #1e9eb4;
      border-radius: 5px;
    }
    .main-content {
      margin-left: 250px;
      padding: 30px;
    }
    .card-box {
      background: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
        @media (max-width: 768px) {
      .sidebar {
        left: -250px;
        position: fixed;
        z-index: 1000;
        transition: left 0.3s ease;
      }
      .sidebar.active {
        left: 0;
      }
      .main-content {
        margin-left: 0 !important;
        padding: 15px;
      }
      .toggle-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1101;
        background-color: #27B3C6;
        border: none;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
      }
    }

  </style>
</head>
<body>
<!-- Tombol Toggle Sidebar untuk Mobile -->
<button class="toggle-btn d-md-none" onclick="toggleSidebar()">☰</button>

  <div class="sidebar p-4">
    <div>
      <h4 class="fw-semibold mb-4">Dashboard Admin</h4>
      <nav class="nav flex-column">
        <a href="{{ route('dashboard') }}" class="nav-link" href="#">🏠 Dashboard</a>

        <button class="nav-link text-start" data-bs-toggle="collapse" data-bs-target="#suratMenu" aria-expanded="false">
          ✉️ Surat
        </button>
        <div class="collapse ps-3" id="suratMenu">
        <a href="{{ route('surat.index') }}" class="nav-link">Edit Surat</a>
          <a href="{{ route('histori.surat') }}" class="nav-link">Daftar Histori Surat</a>
          <a href="{{ route('pengajuan.surat') }}" class="nav-link">Pengajuan Surat</a>
        </div>
        <a href="{{ route('karyawan.index') }}" class="nav-link">👤 Karyawan</a>
        
      </nav>
    </div>

    <div class="pt-3 border-top border-white">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link nav-link p-0">Logout</button>
      </form>
    </div>
  </div>

  <!-- Ganti bagian ini di dalam <div class="main-content"> -->
<div class="main-content">
  <!-- Section Tabel Template Surat -->
<div class="card-box">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
      <input type="text" id="searchInput" class="form-control" placeholder="Search">
    </div>
      <!-- Trigger Button -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSurat">
      <i class="bi bi-plus-lg"></i> Tambah Surat
    </button>

  </div>

<!-- Modal Tambah Surat -->
<div class="modal fade" id="modalTambahSurat" tabindex="-1" aria-labelledby="modalTambahSuratLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTambahSuratLabel">Tambah Template Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="judul_template" class="form-label">Judul Surat</label>
            <input type="text" name="judul_template" id="judul_template" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="file_template" class="form-label">Upload File Surat (.docx / .pdf)</label>
            <input type="file" name="file_template" id="file_template" class="form-control" accept=".docx,.pdf" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <div class="table-responsive">
    <table class="table table-bordered" id="tableSurat">
      <thead class="table-primary text-center">
        <tr>
          <th>Nama Surat</th>
          <th>Kategori</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
  @foreach ($templates as $template)
    <tr>
      <td>{{ $template->judul_template }}</td>
      <td>{{ $template->kategori->nama_kategori ?? '-' }}</td>
      <td class="text-center">
  <div class="d-flex justify-content-center gap-2">
    <!-- Tombol Edit -->
    <button type="button" class="btn btn-sm btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalEditSurat"
            data-id="{{ $template->id }}"
            data-judul="{{ $template->judul_template }}"
            data-kategori="{{ $template->kategori_id }}">
      <i class="bi bi-pencil"></i> Edit
    </button>

    <!-- Tombol Download -->
    <a href="{{ url('/download-template/' . basename($template->file_path)) }}" 
       target="_blank" 
       class="btn btn-sm btn-info">
       <i class="bi bi-download"></i> Download
    </a>

    <!-- Tombol Delete -->
    <form action="{{ route('surat.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
      @csrf
      @method('DELETE')
      <button type="button" class="btn btn-sm btn-danger" 
              data-bs-toggle="modal" 
              data-bs-target="#modalDeleteSurat"
              data-id="{{ $template->id }}">
        <i class="bi bi-trash"></i> Delete
      </button>
    </form>
  </div>
</td>


    </tr>
  @endforeach
</tbody>


    </table>

<!-- Modal Delete Surat -->
<div class="modal fade" id="modalDeleteSurat" tabindex="-1" aria-labelledby="modalDeleteSuratLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="formDeleteSurat">
      @csrf
      @method('DELETE')
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalDeleteSuratLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          Apakah kamu yakin ingin menghapus surat ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Surat -->
<div class="modal fade" id="modalEditSurat" tabindex="-1" aria-labelledby="modalEditSuratLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="formEditSurat">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEditSuratLabel">Edit Template Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Judul Surat</label>
            <input type="text" name="judul_template" id="edit_judul" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" id="edit_kategori" class="form-select" required>
              @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>



  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-center mt-3">
    {{ $templates->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
  </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalLabel">Perhatian</h5>
      </div>
      <div class="modal-body">
        File ini akan dihapus! Tekan <strong>Lanjutkan</strong>, Apakah anda yakin?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
        <button type="button" class="btn btn-danger">Lanjutkan</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let input = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableSurat tbody tr");

    rows.forEach(function (row) {
        let nama = row.cells[0].innerText.toLowerCase();
        let kategori = row.cells[1].innerText.toLowerCase();
        if (nama.includes(input) || kategori.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('modalDeleteSurat');
    const formDelete = document.getElementById('formDeleteSurat');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        // Set action form ke route destroy
        formDelete.setAttribute('action', `/edit-surat/${id}`);
    });
});
document.addEventListener('DOMContentLoaded', function () {
  const editModal = document.getElementById('modalEditSurat');
  const formEdit = document.getElementById('formEditSurat');

  editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const judul = button.getAttribute('data-judul');
    const kategori = button.getAttribute('data-kategori');

    // isi form
    formEdit.setAttribute('action', `/edit-surat/${id}`);
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_kategori').value = kategori;
  });
});
function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('active');
}

</script>
