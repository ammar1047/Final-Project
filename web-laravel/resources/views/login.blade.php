<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
      display: flex;
      flex-wrap: wrap;
      height: 100vh;
    }
    .left-circle {
      width: 45%;
      background-color: #27B3C6; 
      border-top-right-radius: 50%;
      border-bottom-right-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .right-content {
      width: 55%;
      padding: 100px;
    }
    .login-title {
      font-size: 48px;
      margin-bottom: 30px;
      font-weight: 300;
    }
    /* Tambahan responsif */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }
      .left-circle {
        width: 100%;
        height: 200px;
        border-radius: 0;
      }
      .right-content {
        width: 100%;
        padding: 30px;
      }
      .login-title {
        font-size: 32px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="left-circle"></div>
    <div class="right-content d-flex justify-content-center align-items-center">
      <div class="card p-4 w-100" style="max-width: 400px; border-radius: 10px;">
        <h2 class="login-title text-center mb-4">LOGIN</h2>
        @if(session('error'))
          <div class="alert alert-danger text-center" role="alert">
            {{ session('error') }}
          </div>
        @endif
        
        <form action="{{ route('login.proses') }}" method="POST">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="mb-3 text-end">
            <a href="{{ route('password.request') }}">Lupa Password?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100">LOGIN</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
