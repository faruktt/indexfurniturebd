@extends('frontend.layouts.app')

@section('title', 'Customer Registration | ' . \App\Models\SiteSetting::get('site_name', 'Indexfurniture') . ' Furniture')

@section('content')
<div class="container" style="max-width: 520px; margin: 50px auto;">
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 700; color: #111111; margin-bottom: 6px; text-transform: uppercase;">Create Customer Account</h2>
        <p style="color: #666666; font-size: 12px; margin-bottom: 24px;">Register for an exclusive personalized shopping experience.</p>

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; font-size: 12px; margin-bottom: 15px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Phone Number *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Password *</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11.5px; font-weight: 600; margin-bottom: 5px;">Confirm Password *</label>
                <input type="password" name="password_confirmation" required 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 3px; font-size: 13px; outline: none;">
            </div>

            <button type="submit" style="width: 100%; padding: 10px; background: var(--otobi-red); color: #ffffff; font-weight: 700; text-transform: uppercase; font-size: 12.5px; border: none; border-radius: 3px; cursor: pointer; letter-spacing: 0.05em;">
                Register Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666666; border-top: 1px solid #f0f0f0; padding-top: 15px;">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--otobi-red); font-weight: 600;">Login</a>
        </div>
    </div>
</div>
@endsection
