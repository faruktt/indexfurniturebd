@extends('admin.layouts.app')

@section('title', 'Colors')
@section('page_title', 'Color Management')

@section('content')
<div style="display: grid; grid-template-columns: 350px 1fr; gap: 25px;">
    <!-- Add Color Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Color</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.colors.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Color Name *</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. American Teak-Beige, Royal Purple">
                </div>
                <div class="form-group">
                    <label class="form-label">Color Hex/Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. #C4A482">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-plus"></i> Save Color
                </button>
            </form>
        </div>
    </div>

    <!-- Colors Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Colors ({{ $colors->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">Preview</th>
                        <th>Color Name</th>
                        <th>Code</th>
                        <th>Associated Products</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colors as $color)
                        <tr>
                            <td>
                                <div style="width: 24px; height: 24px; border-radius: 4px; border: 1px solid #ccc; background-color: {{ $color->code ?? '#ddd' }};"></div>
                            </td>
                            <td style="font-weight: 600;">{{ $color->name }}</td>
                            <td><code>{{ $color->code ?? 'N/A' }}</code></td>
                            <td>{{ $color->products_count }}</td>
                            <td style="text-align: right;">
                                <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this color?')">
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
                            <td colspan="5" style="text-align: center; padding: 25px; color: #94a3b8;">No colors added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
