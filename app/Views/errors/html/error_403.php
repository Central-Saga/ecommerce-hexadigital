<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Hexadigital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .error-container {
            max-width: 500px;
            margin: 10vh auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 700;
            color: #dc3545;
        }

        .error-message {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .btn-home {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-message">Akses ditolak.<br>Hanya untuk admin atau pegawai.</div>
        <a href="/" class="btn btn-primary btn-home">Kembali ke Beranda</a>
    </div>
</body>

</html>