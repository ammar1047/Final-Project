<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="main-content">
  <h3 class="mb-4">Data Karyawan</h3>
  <div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-2">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
        <i class="bi bi-person-plus"></i> Tambah Karyawan
      </button>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
        <i class="bi bi-person-gear"></i> Tambah Admin
      </button>
     

    </div>
  </div>
  <div id="tableKaryawan" class="card-box table-responsive">
    <table class="table table-bordered table-hover text-center">
    <input type="text" id="searchKaryawan" class="form-control mb-3 w-25" placeholder="Cari NIK, Nama, atau Email">
      <thead class="table-primary">
        <tr>
          <th>NIK</th>
          <th>Nama Lengkap</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($karyawan as $data)
            <tr>
              <td>{{ $data->nik }}</td>
              <td>{{ $data->nama_lengkap }}</td>
              <td>{{ $data->user->email ?? '-' }}</td>
              <td>
                <div class="d-flex justify-content-center gap-2">
                  <button type="button" class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditKaryawan"
                    data-id="{{ $data->id }}"
                    data-nama="{{ $data->nama_lengkap }}"
                    data-email="{{ $data->user->email ?? '-' }}">
                    <i class="bi bi-pencil-square"></i> Edit
                  </button>

                  <form action="{{ route('karyawan.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit"><i class="bi bi-trash"></i> Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Karyawan -->
<div class="modal fade" id="modalTambahKaryawan" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('karyawan.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
            @error('nama_lengkap')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.store') }}">
      @csrf
      <input type="hidden" name="is_admin" value="1">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Tambah Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
            @error('nama_lengkap')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit Karyawan -->
<div class="modal fade" id="modalEditKaryawan" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="formEditKaryawan">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Data Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password Baru (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  @if ($errors->any())
    @if (old('is_admin'))
      var modal = new bootstrap.Modal(document.getElementById('modalTambahAdmin'));
      modal.show();
    @else
      var modal = new bootstrap.Modal(document.getElementById('modalTambahKaryawan'));
      modal.show();
    @endif
  @endif
</script>
<script>
  document.getElementById("searchKaryawan").addEventListener("keyup", function () {
      let input = this.value.toLowerCase();
      let rows = document.querySelectorAll("#tableKaryawan tbody tr");

      rows.forEach(function (row) {
          let nik = row.cells[0].innerText.toLowerCase();
          let nama = row.cells[1].innerText.toLowerCase();
          let email = row.cells[2].innerText.toLowerCase();

          if (nik.includes(input) || nama.includes(input) || email.includes(input)) {
              row.style.display = "";
          } else {
              row.style.display = "none";
          }
      });
  });
</script>
<script>
  const modalEdit = document.getElementById('modalEditKaryawan');
  modalEdit.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const email = button.getAttribute('data-email');

    modalEdit.querySelector('#edit_nama').value = nama;
    modalEdit.querySelector('#edit_email').value = email;

    const form = document.getElementById('formEditKaryawan');
    form.action = `/karyawan/${id}`;
  });

  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }
</script>


</body>
</html>
