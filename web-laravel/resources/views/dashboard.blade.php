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
      transition: left 0.3s ease;
    }
    .sidebar .nav-link { color: white; }
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
    /* Responsif */
    @media (max-width: 768px) {
      .sidebar {
        left: -250px;
        z-index: 1000;
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
        z-index: 1100;
        background-color: #27B3C6;
        border: none;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
      }
      .bg-info {
        padding: 1rem !important;
      }
      .card-box {
        padding: 15px !important;
      }
    }
  </style>
</head>
<body>

<!-- Tombol toggle sidebar (muncul di HP) -->
<button class="toggle-btn d-md-none" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar p-4">
  <div>
    <h4 class="fw-semibold mb-4">Dashboard Admin</h4>
    <nav class="nav flex-column">
      <a href="{{ route('dashboard') }}" class="nav-link">🏠 Dashboard</a>
      <button class="nav-link text-start" data-bs-toggle="collapse" data-bs-target="#suratMenu">
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

<!-- Main Content -->
<div class="main-content">
  <!-- Cards Atas -->
  <div class="bg-info p-4 rounded text-white mb-4">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="bg-white text-dark rounded shadow-sm p-3 position-relative">
          <div class="position-absolute top-0 end-0 m-2 bg-light rounded-circle p-2">
            <i class="bi bi-people-fill text-primary"></i>
          </div>
          <div class="small text-muted">Karyawan</div>
          <div class="h3 fw-bold">{{ $totalKaryawan }}</div>
          <div class="small text-muted">Total</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white text-dark rounded shadow-sm p-3 position-relative">
          <div class="position-absolute top-0 end-0 m-2 bg-light rounded-circle p-2">
            <i class="bi bi-envelope-paper-fill text-primary"></i>
          </div>
          <div class="small text-muted">Pengajuan Surat</div>
          <div class="h3 fw-bold">{{ $totalDraftSurat }}</div>
          <div class="small text-muted">Draft</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white text-dark rounded shadow-sm p-3 position-relative">
          <div class="position-absolute top-0 end-0 m-2 bg-light rounded-circle p-2">
            <i class="bi bi-hash text-primary"></i>
          </div>
          <div class="small text-muted">No Surat Digunakan</div>
          <div class="h3 fw-bold">{{ $totalNoSuratDigunakan }}</div>
          <div class="small text-muted">Digunakan</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section Chart -->
  <div class="row">
    <div class="col-md-6">
      <div class="card-box">
        <div class="fw-semibold mb-2">OVERALL STATISTIC (Mingguan)</div>
        <canvas id="barChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card-box">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-semibold">Histori Surat ACC per Kategori (Bulanan)</div>
        </div>
        <canvas id="lineChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Data & Script -->
<script>
  const bar = @json($barData);
  const line = @json($lineData);
  const lineLabels = @json($monthLabels);

  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
      labels: bar.minggu,
      datasets: [
        { label: 'Karyawan Baru', data: bar.karyawan, backgroundColor: '#42a5f5' },
        { label: 'Pengajuan Surat', data: bar.pengajuan, backgroundColor: '#66bb6a' },
        { label: 'No Surat Digunakan', data: bar.nosurat, backgroundColor: '#ffa726' }
      ]
    }
  });

  new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
      labels: lineLabels,
      datasets: Object.keys(line).map((kategori, index) => ({
        label: kategori,
        data: line[kategori],
        borderColor: ['#e91e63', '#03a9f4', '#8bc34a', '#ff9800'][index % 4],
        fill: false
      }))
    }
  });
</script>
</body>
</html>
