@extends('admin.layouts.app')

@section('title', 'Order Statuses Management')
@section('page_title', 'Order Statuses Management')

@section('content')
<div style="display: grid; grid-template-columns: 380px 1fr; gap: 25px; align-items: start;">
    <!-- Add / Edit Status Form -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3 class="card-title">
                @if(isset($editStatus))
                    <i class="fas fa-edit" style="color: var(--admin-primary);"></i> Edit Status: {{ $editStatus->name }}
                @else
                    <i class="fas fa-plus-circle" style="color: var(--admin-primary);"></i> Add Order Status
                @endif
            </h3>
            @if(isset($editStatus))
                <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i> Cancel
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(isset($editStatus))
                <form action="{{ route('admin.order-statuses.update', $editStatus->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Status Name <span style="color: red;">*</span></label>
                        <input type="text" name="name" id="statusNameInput" value="{{ old('name', $editStatus->name) }}" required class="form-control" placeholder="e.g. Out for Delivery, Packaging, Returned" onkeyup="updateLiveBadge()">
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL / Identifier Slug <span style="color: red;">*</span></label>
                        <input type="text" name="slug" id="statusSlugInput" value="{{ old('slug', $editStatus->slug) }}" required class="form-control" placeholder="e.g. out-for-delivery">
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                            Unique key stored in the orders table.
                        </div>
                    </div>

                    <!-- Color Pickers & Live Preview -->
                    <div class="form-group">
                        <label class="form-label">Badge Styling &amp; Colors</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                            <div>
                                <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 4px;">Background Color:</span>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <input type="color" id="bgColorPicker" name="bg_color" value="{{ old('bg_color', $editStatus->bg_color) }}" style="width: 36px; height: 36px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 2px;" onchange="updateLiveBadge()">
                                    <input type="text" id="bgHexInput" value="{{ old('bg_color', $editStatus->bg_color) }}" class="form-control" style="font-size: 12px; padding: 6px;" onkeyup="syncColor('bg')">
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 4px;">Text Color:</span>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <input type="color" id="textColorPicker" name="text_color" value="{{ old('text_color', $editStatus->text_color) }}" style="width: 36px; height: 36px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 2px;" onchange="updateLiveBadge()">
                                    <input type="text" id="textHexInput" value="{{ old('text_color', $editStatus->text_color) }}" class="form-control" style="font-size: 12px; padding: 6px;" onkeyup="syncColor('text')">
                                </div>
                            </div>
                        </div>

                        <!-- Color Presets -->
                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 5px;">Quick Presets:</span>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #fef3c7; color: #b45309;" onclick="applyPreset('#fef3c7', '#b45309')">Amber / Pending</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #e0f2fe; color: #0369a1;" onclick="applyPreset('#e0f2fe', '#0369a1')">Blue / Processing</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #f3e8ff; color: #7e22ce;" onclick="applyPreset('#f3e8ff', '#7e22ce')">Purple / Shipped</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #dcfce7; color: #15803d;" onclick="applyPreset('#dcfce7', '#15803d')">Green / Delivered</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #fee2e2; color: #b91c1c;" onclick="applyPreset('#fee2e2', '#b91c1c')">Red / Cancelled</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #ffedd5; color: #c2410c;" onclick="applyPreset('#ffedd5', '#c2410c')">Orange / Packaging</button>
                            </div>
                        </div>

                        <!-- Live Badge Preview -->
                        <div style="padding: 10px 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 12px; color: #64748b;">Live Preview:</span>
                            <span id="liveBadgePreview" style="background: {{ $editStatus->bg_color }}; color: {{ $editStatus->text_color }}; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $editStatus->name }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="order" value="{{ old('order', $editStatus->order) }}" class="form-control" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="status" value="1" {{ old('status', $editStatus->status) ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <span style="font-weight: 600; color: #334155;">Active (Available in dropdown &amp; tabs)</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                        <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            @else
                <!-- Add Form -->
                <form action="{{ route('admin.order-statuses.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Status Name <span style="color: red;">*</span></label>
                        <input type="text" name="name" id="statusNameInput" value="{{ old('name') }}" required class="form-control" placeholder="e.g. Out for Delivery, Packaging, Returned" onkeyup="autoFillSlugAndBadge(this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL / Identifier Slug</label>
                        <input type="text" name="slug" id="statusSlugInput" value="{{ old('slug') }}" class="form-control" placeholder="e.g. out-for-delivery">
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                            Leave empty to auto-generate from Status Name.
                        </div>
                    </div>

                    <!-- Color Pickers & Live Preview -->
                    <div class="form-group">
                        <label class="form-label">Badge Styling &amp; Colors</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                            <div>
                                <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 4px;">Background Color:</span>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <input type="color" id="bgColorPicker" name="bg_color" value="#e0f2fe" style="width: 36px; height: 36px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 2px;" onchange="updateLiveBadge()">
                                    <input type="text" id="bgHexInput" value="#e0f2fe" class="form-control" style="font-size: 12px; padding: 6px;" onkeyup="syncColor('bg')">
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 4px;">Text Color:</span>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <input type="color" id="textColorPicker" name="text_color" value="#0369a1" style="width: 36px; height: 36px; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; padding: 2px;" onchange="updateLiveBadge()">
                                    <input type="text" id="textHexInput" value="#0369a1" class="form-control" style="font-size: 12px; padding: 6px;" onkeyup="syncColor('text')">
                                </div>
                            </div>
                        </div>

                        <!-- Color Presets -->
                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 11px; color: #64748b; display: block; margin-bottom: 5px;">Quick Presets:</span>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #fef3c7; color: #b45309;" onclick="applyPreset('#fef3c7', '#b45309')">Amber / Pending</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #e0f2fe; color: #0369a1;" onclick="applyPreset('#e0f2fe', '#0369a1')">Blue / Processing</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #f3e8ff; color: #7e22ce;" onclick="applyPreset('#f3e8ff', '#7e22ce')">Purple / Shipped</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #dcfce7; color: #15803d;" onclick="applyPreset('#dcfce7', '#15803d')">Green / Delivered</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #fee2e2; color: #b91c1c;" onclick="applyPreset('#fee2e2', '#b91c1c')">Red / Cancelled</button>
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 2px 8px; font-size: 11px; background: #ffedd5; color: #c2410c;" onclick="applyPreset('#ffedd5', '#c2410c')">Orange / Packaging</button>
                            </div>
                        </div>

                        <!-- Live Badge Preview -->
                        <div style="padding: 10px 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 12px; color: #64748b;">Live Preview:</span>
                            <span id="liveBadgePreview" style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                Sample Status
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="status" value="1" checked style="width: 16px; height: 16px;">
                            <span style="font-weight: 600; color: #334155;">Active (Available in dropdown &amp; tabs)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">
                        <i class="fas fa-plus"></i> Save Order Status
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Statuses Table -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3 class="card-title">Configured Order Statuses ({{ $statuses->count() }})</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-shopping-cart"></i> View All Orders
            </a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Order</th>
                        <th>Status Name &amp; Badge</th>
                        <th>Identifier Slug</th>
                        <th>Assigned Orders</th>
                        <th style="width: 90px;">Active</th>
                        <th style="width: 130px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statuses as $st)
                        <tr style="{{ isset($editStatus) && $editStatus->id === $st->id ? 'background: #fff1f2;' : '' }}">
                            <td style="font-weight: 700; color: #64748b;">#{{ $st->order }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    {!! $st->badge_html !!}
                                    <span style="font-weight: 700; color: #0f172a; font-size: 13px;">{{ $st->name }}</span>
                                    @if($st->is_default)
                                        <span style="font-size: 10px; background: #e2e8f0; color: #475569; padding: 2px 6px; border-radius: 4px;">Default</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <code style="font-size: 11.5px; background: #f1f5f9; padding: 2px 6px; border-radius: 3px;">
                                    {{ $st->slug }}
                                </code>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.index', ['status' => $st->slug]) }}" title="Filter orders with this status" style="color: var(--admin-primary); font-weight: 600; text-decoration: underline; font-size: 12.5px;">
                                    {{ $st->orders_count }} order(s)
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.order-statuses.toggle', $st->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        @if($st->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Disabled</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.order-statuses.index', ['edit' => $st->id]) }}" class="btn btn-outline btn-sm" title="Edit this status">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.order-statuses.destroy', $st->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Are you sure you want to delete status: {{ $st->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete Status">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">
                                No order statuses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function autoFillSlugAndBadge(name) {
    const slugInput = document.getElementById('statusSlugInput');
    if (slugInput && !slugInput.dataset.manual) {
        slugInput.value = name.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
    }
    updateLiveBadge();
}

if (document.getElementById('statusSlugInput')) {
    document.getElementById('statusSlugInput').addEventListener('input', function() {
        this.dataset.manual = "true";
    });
}

function syncColor(type) {
    if (type === 'bg') {
        const hex = document.getElementById('bgHexInput').value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            document.getElementById('bgColorPicker').value = hex;
        }
    } else {
        const hex = document.getElementById('textHexInput').value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            document.getElementById('textColorPicker').value = hex;
        }
    }
    updateLiveBadge();
}

function applyPreset(bg, text) {
    document.getElementById('bgColorPicker').value = bg;
    document.getElementById('bgHexInput').value = bg;
    document.getElementById('textColorPicker').value = text;
    document.getElementById('textHexInput').value = text;
    updateLiveBadge();
}

function updateLiveBadge() {
    const name = document.getElementById('statusNameInput').value || 'Sample Status';
    const bg = document.getElementById('bgColorPicker').value;
    const text = document.getElementById('textColorPicker').value;

    document.getElementById('bgHexInput').value = bg;
    document.getElementById('textHexInput').value = text;

    const preview = document.getElementById('liveBadgePreview');
    if (preview) {
        preview.textContent = name;
        preview.style.backgroundColor = bg;
        preview.style.color = text;
    }
}
</script>
@endsection
