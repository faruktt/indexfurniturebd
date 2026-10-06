@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page_title', 'Store & Site Configuration')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">General Store &amp; Brand Settings</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Info -->
            <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                Brand Identity
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Brand / Store Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Indexfurniture' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Brand Tagline</label>
                    <input type="text" name="tagline" value="{{ $settings['tagline'] ?? 'Living Reimagined' }}" class="form-control">
                </div>
            </div>

            <!-- Uploads: Logo & Favicon -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label">Brand Logo (Uploads to public/uploads/)</label>
                    @if(isset($settings['logo']))
                        <div style="margin-bottom: 6px;">
                            <img src="{{ asset('uploads/' . $settings['logo']) }}" alt="Logo" style="max-height: 40px; border: 1px solid #ccc; padding: 2px;">
                            <div style="font-size: 11px; color: #64748b;">Filename: <code>{{ $settings['logo'] }}</code></div>
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label">Favicon (Uploads to public/uploads/)</label>
                    @if(isset($settings['favicon']))
                        <div style="margin-bottom: 6px;">
                            <img src="{{ asset('uploads/' . $settings['favicon']) }}" alt="Favicon" style="max-height: 30px;">
                        </div>
                    @endif
                    <input type="file" name="favicon" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- Contact Hotline & Email -->
            <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin: 20px 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                Customer Care &amp; Contact Info
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Customer Care Phone 1</label>
                    <input type="text" name="phone_primary" value="{{ $settings['phone_primary'] ?? '+880 9613808080' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Customer Care Phone 2</label>
                    <input type="text" name="phone_secondary" value="{{ $settings['phone_secondary'] ?? '+88 01847088444' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Customer Care Working Hours</label>
                    <input type="text" name="customer_care_hours" value="{{ $settings['customer_care_hours'] ?? '9 AM – 6 PM' }}" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Official Email</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? 'info@indexfurniture.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Headquarters / Center Address</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? 'Indexfurniture Center (3rd Floor), Plot 12, Block CWS (C), Gulshan South Avenue, Gulshan-1, Dhaka 1212, Bangladesh.' }}" class="form-control">
                </div>
            </div>

            <!-- Social Links -->
            <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin: 20px 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                Social Media Channels
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Facebook Page URL</label>
                    <input type="url" name="facebook" value="{{ $settings['facebook'] ?? 'https://facebook.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter / X Profile URL</label>
                    <input type="url" name="twitter" value="{{ $settings['twitter'] ?? 'https://twitter.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin" value="{{ $settings['linkedin'] ?? 'https://linkedin.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram" value="{{ $settings['instagram'] ?? 'https://instagram.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">YouTube Channel URL</label>
                    <input type="url" name="youtube" value="{{ $settings['youtube'] ?? 'https://youtube.com' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp Helpline / Number</label>
                    <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '+8801847088444' }}" class="form-control" placeholder="+8801847088444 or https://wa.me/...">
                </div>
            </div>

            <!-- Footer Text -->
            <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin: 20px 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
                Footer &amp; Copyright Notice
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Copyright Notice</label>
                    <input type="text" name="copyright" value="{{ $settings['copyright'] ?? 'Copyright ' . date('Y') . ' © Indexfurniture. All Rights Reserved.' }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Developer Credit Text</label>
                    <input type="text" name="developed_by" value="{{ $settings['developed_by'] ?? 'Developed By Access InfoTech Ltd.' }}" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 24px; margin-top: 15px;">
                <i class="fas fa-save"></i> Save Site Settings
            </button>
        </form>
    </div>
</div>
@endsection
