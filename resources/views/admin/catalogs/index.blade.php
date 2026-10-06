@extends('admin.layouts.app')

@section('title', 'E-Catalogs Management')
@section('page_title', 'E-Catalogs & Downloadable PDFs')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-book-open" style="color: var(--admin-primary);"></i>
                E-Catalogs ({{ $catalogs->total() }})
            </h3>
            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                Active catalogs will automatically appear in the right-side floating E-Catalog menu on the storefront.
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="toggleAddCatalogModal()">
            <i class="fas fa-plus"></i> Upload New Catalog
        </button>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Order</th>
                    <th>Catalog Name / Title</th>
                    <th>PDF File</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Uploaded Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($catalogs as $catalog)
                    <tr>
                        <td style="font-weight: 700; color: #64748b; text-align: center;">
                            {{ $catalog->order }}
                        </td>
                        <td style="font-weight: 700; color: #0f172a;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 34px; height: 34px; border-radius: 6px; background: #fee2e2; color: #ea1d24; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div style="font-size: 13px; color: #0f172a;">{{ $catalog->title }}</div>
                                    <div style="font-size: 10.5px; color: #94a3b8;">Slug: {{ $catalog->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($catalog->pdf_file)
                                <a href="{{ $catalog->pdf_url }}" target="_blank" class="btn btn-outline btn-sm" style="padding: 3px 9px; font-size: 11.5px; color: #0284c7; border-color: #bae6fd;">
                                    <i class="fas fa-external-link-alt"></i> View PDF
                                </a>
                            @else
                                <span style="color: #94a3b8; font-size: 11px;">No file</span>
                            @endif
                        </td>
                        <td style="color: #64748b; font-size: 12px; max-width: 250px;">
                            {{ $catalog->description ?? '-' }}
                        </td>
                        <td>
                            <form action="{{ route('admin.catalogs.toggle', $catalog->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Click to toggle status">
                                    @if($catalog->status)
                                        <span class="badge badge-success"><i class="fas fa-check-circle" style="font-size: 9px; margin-right: 3px;"></i> Active</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle" style="font-size: 9px; margin-right: 3px;"></i> Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td style="color: #64748b; font-size: 11.5px;">
                            {{ $catalog->created_at->format('M d, Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px;"
                                    onclick="openEditCatalogModal({{ json_encode($catalog->only(['id', 'title', 'description', 'order', 'status', 'pdf_file', 'pdf_url'])) }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <form action="{{ route('admin.catalogs.destroy', $catalog->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Delete this catalog?')">
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
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fas fa-book-open" style="font-size: 32px; margin-bottom: 8px; display: block; opacity: 0.4;"></i>
                            No E-Catalogs uploaded yet. Click <strong>Upload New Catalog</strong> to add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($catalogs->hasPages())
        <div style="padding: 15px 20px; border-top: 1px solid var(--admin-card-border);">
            {{ $catalogs->links() }}
        </div>
    @endif
</div>

<!-- Modal to Upload New Catalog -->
<div id="addCatalogModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 520px; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding: 16px 20px; background: #0f172a; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-upload" style="color: var(--admin-primary);"></i> Upload New E-Catalog
            </h3>
            <button type="button" onclick="toggleAddCatalogModal()" style="background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form action="{{ route('admin.catalogs.store') }}" method="POST" enctype="multipart/form-data" style="padding: 20px;">
            @csrf

            <div class="form-group">
                <label class="form-label">Catalog Title / Name *</label>
                <input type="text" name="title" required class="form-control" placeholder="e.g. Living Room Catalog 2026, Office Solutions">
            </div>

            <div class="form-group">
                <label class="form-label">PDF File Document *</label>
                <input type="file" name="pdf_file" required class="form-control" accept="application/pdf">
                <small style="color: #64748b; font-size: 11px;">Upload a PDF catalog file (Max: 30MB).</small>
            </div>

            <div class="form-group">
                <label class="form-label">Brief Description (Optional)</label>
                <textarea name="description" rows="2" class="form-control" placeholder="Short description or season/year notes"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" value="0" class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; padding-top: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="status" value="1" checked>
                        <span>Active (Show in floating menu)</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleAddCatalogModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-cloud-upload-alt"></i> Upload Catalog
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal to Edit Catalog -->
<div id="editCatalogModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 520px; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding: 16px 20px; background: #0f172a; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit" style="color: var(--admin-primary);"></i> Edit E-Catalog
            </h3>
            <button type="button" onclick="closeEditCatalogModal()" style="background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="editCatalogForm" method="POST" enctype="multipart/form-data" style="padding: 20px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Catalog Title / Name *</label>
                <input type="text" name="title" id="edit_catalog_title" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Replace PDF File (Optional)</label>
                <div style="margin-bottom: 6px;">
                    <a id="edit_catalog_current_link" href="#" target="_blank" class="btn btn-outline btn-sm" style="font-size: 11px; padding: 3px 8px;">
                        <i class="fas fa-file-pdf" style="color: #ea1d24;"></i> Current PDF Document
                    </a>
                </div>
                <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                <small style="color: #64748b; font-size: 11px;">Leave empty to keep existing PDF.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Brief Description (Optional)</label>
                <textarea name="description" id="edit_catalog_description" rows="2" class="form-control"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" id="edit_catalog_order" class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; padding-top: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="status" id="edit_catalog_status" value="1">
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeEditCatalogModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAddCatalogModal() {
        const modal = document.getElementById('addCatalogModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    function openEditCatalogModal(catalog) {
        const modal = document.getElementById('editCatalogModal');
        const form = document.getElementById('editCatalogForm');

        form.action = "{{ url('admin/catalogs') }}/" + catalog.id;
        document.getElementById('edit_catalog_title').value = catalog.title;
        document.getElementById('edit_catalog_description').value = catalog.description || '';
        document.getElementById('edit_catalog_order').value = catalog.order;
        document.getElementById('edit_catalog_status').checked = Boolean(catalog.status);

        const currentLink = document.getElementById('edit_catalog_current_link');
        if (catalog.pdf_url) {
            currentLink.href = catalog.pdf_url;
            currentLink.style.display = 'inline-flex';
        } else {
            currentLink.style.display = 'none';
        }

        modal.style.display = 'flex';
    }

    function closeEditCatalogModal() {
        document.getElementById('editCatalogModal').style.display = 'none';
    }
</script>
@endpush
