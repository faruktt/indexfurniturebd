@extends('frontend.layouts.app')

@section('title', 'Customer Login | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

@section('content')
<div class="container" style="max-width: 480px; margin: 50px auto;">
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 700; color: #111111; margin-bottom: 6px; text-transform: uppercase;">Customer Login</h2>
        <p style="color: #666666; font-size: 12px; margin-bottom: 24px;">Login to manage your {{ \App\Models\SiteSetting::get('site_name', 'Indexfurniture') }} orders and profile.</p>

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; font-size: 12px; margin-bottom: 15px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'customer@indexfurniture.com') }}" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" value="password123" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 12px;">
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="#forgot" style="color: #0077b6;">Forgot Password?</a>
            </div>

            <button type="submit" style="width: 100%; padding: 10px; background: var(--otobi-red); color: #ffffff; font-weight: 700; text-transform: uppercase; font-size: 12.5px; border: none; border-radius: 3px; cursor: pointer; letter-spacing: 0.05em;">
                Login to Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666666; border-top: 1px solid #f0f0f0; padding-top: 15px;">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--otobi-red); font-weight: 600;">Sign Up</a>
        </div>
    </div>
</div>
@endsection
