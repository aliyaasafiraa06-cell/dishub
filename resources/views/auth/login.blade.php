<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dishub Surakarta</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F5F7FB; /* Page Background dari design.md */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #FFFFFF; /* Surface */
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 420px;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-logo h2 {
            color: #172B4D; /* Navy */
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .brand-logo p {
            color: #778397; /* Text Secondary */
            font-size: 14px;
            margin: 0;
        }
        .form-label {
            font-weight: 600;
            color: #182230; /* Text Primary */
            font-size: 13px;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #E7ECF3; /* Border */
        }
        .form-control:focus {
            border-color: #2878E8; /* Primary Blue */
            box-shadow: 0 0 0 3px rgba(40, 120, 232, 0.15);
        }
        .btn-login {
            background-color: #2878E8; /* Primary Blue */
            color: #FFFFFF;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #174A8B; /* Primary Dark */
            color: #FFFFFF;
        }
        .alert {
            border-radius: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <h2>Dishub Surakarta</h2>
            <p>Sistem Monitoring Laporan Pemesanan</p>
        </div>

        {{-- Tampilkan Error jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Login --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-login">Masuk</button>
        </form>
    </div>

</body>
</html>
