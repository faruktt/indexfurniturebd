@extends('admin.layouts.app')

@section('title', 'Shipping Charges')
@section('page_title', 'Shipping & Delivery Configuration')

@section('content')
<div style="display: grid; grid-template-columns: 380px 1fr; gap: 25px; align-items: start;">
    <!-- Add / Edit Shipping Form -->
    <div class="card" id="shippingFormCard">
        <div class="card-header" style="justify-content: space-between;">
            <h3 class="card-title">
                @if(isset($editCharge))
                    <i class="fas fa-edit" style="color: var(--admin-primary);"></i> Edit Delivery Method
                @else
                    <i class="fas fa-plus-circle" style="color: var(--admin-primary);"></i> Add Delivery Method
                @endif
            </h3>
            @if(isset($editCharge))
                <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline btn-sm" title="Cancel Editing">
                    <i class="fas fa-times"></i> Cancel
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(isset($editCharge))
                <form action="{{ route('admin.shipping.update', $editCharge->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Method Title <span style="color: red;">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $editCharge->title) }}" required class="form-control" placeholder="e.g. Express Dhaka Delivery">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Delivery Charge (BDT) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="charge" value="{{ old('charge', $editCharge->charge) }}" required class="form-control" placeholder="1094.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description / Estimated Delivery Time</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="e.g. Delivery within 2-3 business days">{{ old('description', $editCharge->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="status" value="1" {{ old('status', $editCharge->status) ? 'checked' : '' }} style="width: 16px; height: 16px;">
                            <span style="font-weight: 600; color: #334155;">Active Status</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Update Delivery Method
                        </button>
                        <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline">
                            Cancel
                        </a>
                    </div>
                </form>
            @else
                <form action="{{ route('admin.shipping.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Method Title <span style="color: red;">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-control" placeholder="e.g. Express Dhaka Delivery">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Delivery Charge (BDT) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="charge" value="{{ old('charge') }}" required class="form-control" placeholder="1094.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description / Estimated Delivery Time</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="e.g. Delivery within 2-3 business days">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px;">
                            <input type="checkbox" name="status" value="1" checked style="width: 16px; height: 16px;">
                            <span style="font-weight: 600; color: #334155;">Active Status</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">
                        <i class="fas fa-plus"></i> Save Delivery Method
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Shipping Charges Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configured Delivery Zones &amp; Charges ({{ $charges->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title / Method Name</th>
                        <th>Delivery Charge</th>
                        <th>Description / Estimated Time</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 130px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($charges as $charge)
                        <tr style="{{ isset($editCharge) && $editCharge->id === $charge->id ? 'background: #fff1f2;' : '' }}">
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">{{ $charge->title }}</div>
                                <div style="font-size: 11px; color: #64748b; font-family: monospace;">Key: {{ $charge->key }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #ea1d24; font-size: 14px;">
                                    BDT {{ number_format($charge->charge, 0) }}
                                </div>
                            </td>
                            <td style="color: #475569; font-size: 12px;">{{ $charge->description ?? '-' }}</td>
                            <td>
                                @if($charge->status)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle" style="font-size: 10px; margin-right: 3px;"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle" style="font-size: 10px; margin-right: 3px;"></i> Disabled
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <!-- Edit Button (Loads edit form) -->
                                    <a href="{{ route('admin.shipping.index', ['edit' => $charge->id]) }}" class="btn btn-outline btn-sm" title="Edit this shipping method">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.shipping.destroy', $charge->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Delete shipping method: {{ $charge->title }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 35px; color: #94a3b8;">
                                No shipping methods found. Fill the form to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
