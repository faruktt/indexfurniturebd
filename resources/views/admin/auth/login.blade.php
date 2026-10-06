<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal Login | {{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-title {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.05em;
            color: #000000;
        }
        .brand-title span { color: #ea1d24; }
        .brand-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
        }
        .form-control:focus { border-color: #ea1d24; box-shadow: 0 0 0 2px rgba(234, 29, 36, 0.15); }
        .btn-submit {
            width: 100%;
            padding: 11px;
            background: #ea1d24;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-submit:hover { background: #c41218; }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 18px;
        }
        .demo-credentials {
            margin-top: 25px;
            padding: 12px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            font-size: 11.5px;
            color: #475569;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-header">
            @if($adminLogo = \App\Models\SiteSetting::logoUrl())
                <div style="margin-bottom: 12px; display: flex; justify-content: center;">
                    <img src="{{ $adminLogo }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}" style="max-height: 48px; max-width: 180px; object-fit: contain;">
                </div>
            @endif
            <div class="brand-title">{{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }}<span>.admin</span></div>
            <div class="brand-subtitle">Management Control Center</div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label"> Email </label>
                <input type="email" name="email"  required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-control">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-size: 12px;">
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="remember"> Keep me signed in
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-lock"></i> Access Admin Portal
            </button>
        </form>

    </div
</body>
</html>
