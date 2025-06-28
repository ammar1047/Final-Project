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
  <h3 class="mb-4">Daftar Histori Surat</h3>

  <form method="GET" action="{{ route('histori.surat') }}" class="d-flex justify-content-between mb-3">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Search NIK / Nama">
    <select name="kategori" class="form-select w-25" onchange="this.form.submit()">
      <option value="">Choose</option>
      @foreach ($kategoriList as $kategori)
        <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
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
          <th>No Surat</th>
          <th>Kategori Surat</th>
          <th>Keterangan</th>
          <th>Tanggal Terbit</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($histori as $index => $item)
        <tr class="text-center">
          <td>{{ $loop->iteration + ($histori->currentPage() - 1) * $histori->perPage() }}</td>
          <td>{{ $item->nik }}</td>
          <td>{{ $item->nama_lengkap }}</td>
          <td>{{ $item->nomor_surat }}</td>
          <td>{{ $item->kategori }}</td>
          <td>{{ $item->keterangan ?? '-' }}</td>
          <td>{{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}</td>
          
            <td>
            @if ($item->status == 'selesai')
                <span class="badge bg-success">Selesai</span>
            @elseif ($item->status == 'ditolak')
                <span class="badge bg-danger">ditolak</span>
            @elseif ($item->status == 'dibatalkan')
                <span class="badge bg-secondary">Dibatalkan</span>
            @endif
           </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center">Tidak ada data histori surat.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $histori->appends(request()->except('page'))->links() }}
    </div>
  </div>
</div>
<script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }
</script>

</body>
</html>
