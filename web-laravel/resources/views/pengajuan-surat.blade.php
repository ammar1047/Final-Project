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
      <a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a>

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

<div class="main-content">
  <h3 class="mb-4">Daftar Pengajuan Surat</h3>
  <form method="GET" action="{{ route('pengajuan.surat') }}" class="d-flex justify-content-between mb-3">
  <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Cari NIK atau Nama">
  
  <select name="kategori" class="form-select w-25" onchange="this.form.submit()">
    <option value="">Filter Kategori</option>
    @foreach ($kategoriList as $kategori)
      <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
        {{ $kategori->nama_kategori }}
      </option>
    @endforeach
  </select>
</form>
  <div class="card-box table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-primary text-center">
        <tr>
          <th>No</th>
          <th>NIK</th>
          <th>Nama Karyawan</th>
          <th>Kategori Surat</th>
          <th>Keterangan</th>
          <th>Tanggal Berlaku</th>
          <th>Action</th>
        </tr>
      </thead>
        <tbody>
            @foreach ($pengajuan as $index => $item)
            <tr class="text-center">
            <td>{{ $loop->iteration + ($pengajuan->currentPage() - 1) * $pengajuan->perPage() }}</td>
            <td>{{ $item->karyawan->nik ?? '-' }}</td>
            <td>{{ $item->karyawan->nama_lengkap ?? '-' }}</td>
            <td>{{ $item->template->kategori->nama_kategori ?? '-' }}</td>
            <td>{{ $item->keterangan ?? '-' }}</td>
            <td>{{ $item->tanggal_berlaku?? '-' }}
                <b>s/d</b>
                {{ $item->tanggal_berakhir?? '-' }}
            </td>
            <td>
              <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalSetujui{{ $item->id }}">Setujui</button>
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $item->id }}">Tolak</button>

            </td>
            </tr>

                <!-- Modal -->
                <div class="modal fade" id="modalSetujui{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('pengajuan.setujui', $item->id) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Setujui Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                        <input type="hidden" name="kategori_id" value="{{ $item->template->kategori_id }}">
                        <label>No Surat</label>
                        <input type="text" name="nomor_surat_preview" id="nomorSuratInput{{ $item->id }}" class="form-control" readonly>
                        </div>
                        <div class="modal-footer">
                        <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Setujui</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
                                <!-- Modal TOLAK -->
                <div class="modal fade" id="modalTolak{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('pengajuan.tolak', $item->id) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">Tolak Pengajuan</h5></div>
                        <div class="modal-body">
                        <label>Alasan Penolakan</label>
                        <textarea name="keterangan" class="form-control" required></textarea>
                        </div>
                        <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning" type="submit">Tolak</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>

            @endforeach
        </tbody>
    </table>
    




    <div class="d-flex justify-content-center">
      {{ $pengajuan->links() }}
    </div>
  </div>
</div>

</body>
<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.addEventListener('show.bs.modal', function (e) {
    const modal = e.target;

    if (!modal.id.startsWith('modalSetujui')) return;

    const id = modal.id.replace('modalSetujui', '');
    const kategoriId = modal.querySelector('input[name="kategori_id"]').value;
    const input = modal.querySelector(`#nomorSuratInput${id}`);

    input.value = 'Loading...';

    fetch(`/preview-nomor-surat/${kategoriId}`)
      .then(res => res.json())
      .then(data => {
        input.value = data.preview;
      })
      .catch(() => {
        input.value = 'Gagal generate nomor';
      });
  });
});

  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }

</script>


</html>
