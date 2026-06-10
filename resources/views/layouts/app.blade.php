<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Marketplace')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #1f2937;
        }

        a { color: inherit; text-decoration: none; }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .nav a {
            font-size: 14px;
            color: #4b5563;
        }

        .container {
            max-width: 1180px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            color: #111827;
        }

        .page-header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .btn {
            border: none;
            cursor: pointer;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .btn-primary { background: #111827; color: #ffffff; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-danger { background: #fee2e2; color: #991b1b; }
        .btn-small { padding: 8px 12px; font-size: 13px; }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-grid { display: grid; gap: 18px; }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
            background: #ffffff;
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 13px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px;
        }

        td {
            border-bottom: 1px solid #f3f4f6;
            padding: 14px 12px;
            font-size: 14px;
            vertical-align: middle;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .product-card-body {
            padding: 16px;
        }

        .product-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .empty-thumb {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            background: #f3f4f6;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
        }

        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }

        .inline-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .image-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 10px;
            background: #ffffff;
        }

        .image-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .muted { color: #6b7280; font-size: 14px; }

        .logout-form { margin: 0; }

        .auth-wrapper {
            max-width: 440px;
            margin: 48px auto;
        }

        .public-product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .main-product-image {
            width: 100%;
            height: 360px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }

        .price {
            font-size: 32px;
            font-weight: bold;
            margin: 24px 0;
        }

        @media (max-width: 800px) {
            .public-product-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            .topbar {
                padding: 14px 18px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .page-header { flex-direction: column; align-items: flex-start; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header class="topbar">
    <a href="{{ route('home') }}" class="brand">
        Marketplace
    </a>

    <nav class="nav">
        <a href="{{ route('home') }}">Home</a>

        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('sales.index') }}">Minhas Vendas</a>
            <a href="{{ route('purchases.index') }}">Minhas Compras</a>
            <a href="{{ route('products.index') }}">Produtos</a>
            <a href="{{ route('affiliates.index') }}">Afiliar-se</a>
            <a href="{{ route('affiliates.my') }}">Minhas Afiliações</a>
            <a href="{{ route('affiliate.earnings.index') }}">Vendas Afiliados </a>
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="admin-link">
                    Admin
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-secondary btn-small">Sair</button>
            </form>
        @else
            <a href="{{ route('login') }}">Entrar</a>
            <a href="{{ route('register') }}">Cadastrar</a>
        @endauth
    </nav>
</header>

<main class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $erro)
                <div>{{ $erro }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>