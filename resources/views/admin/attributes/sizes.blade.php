@extends('admin.layouts.app')

@section('title', 'Sizes & Dimensions')
@section('page_title', 'Size & Variant Management')

@section('content')
<div style="display: grid; grid-template-columns: 350px 1fr; gap: 25px;">
    <!-- Add Size Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Size</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sizes.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Size Name *</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. King Size, Standard, 3 Seater">
                </div>
                <div class="form-group">
                    <label class="form-label">Code / Abbreviation</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. KING, STD, 3S">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-plus"></i> Save Size
                </button>
            </form>
        </div>
    </div>

    <!-- Sizes Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Sizes ({{ $sizes->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Size Name</th>
                        <th>Code</th>
                        <th>Associated Products</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sizes as $size)
                        <tr>
                            <td style="font-weight: 600;">{{ $size->name }}</td>
                            <td><code>{{ $size->code ?? 'N/A' }}</code></td>
                            <td>{{ $size->products_count }}</td>
                            <td style="text-align: right;">
                                <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this size?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 25px; color: #94a3b8;">No sizes added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
