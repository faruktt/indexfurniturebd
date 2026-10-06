@extends('admin.layouts.app')

@section('title', 'Add New Page')
@section('page_title', 'Create Footer Page')

@section('content')
<div class="card" style="max-width: 850px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">New Page Information</h3>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf

            <!-- Page Title -->
            <div class="form-group">
                <label class="form-label">Page Title / Name <span style="color: red;">*</span></label>
                <input type="text" name="title" id="pageTitleInput" value="{{ old('title') }}" required class="form-control" placeholder="e.g. About Us, Privacy Policy, Delivery Coverage Area" onkeyup="autoGenerateSlug(this.value)">
                @if(isset($errors) && $errors->has('title'))
                    <div style="color: #dc2626; font-size: 11.5px; margin-top: 4px;">{{ $errors->first('title') }}</div>
                @endif
            </div>

            <!-- URL Slug & Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">URL Slug (Will be accessible at /page/{slug})</label>
                    <input type="text" name="slug" id="pageSlugInput" value="{{ old('slug') }}" class="form-control" placeholder="e.g. about-us">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                        Leave blank to auto-generate from Title.
                    </div>
                    @if(isset($errors) && $errors->has('slug'))
                        <div style="color: #dc2626; font-size: 11.5px; margin-top: 4px;">{{ $errors->first('slug') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Footer Placement Section <span style="color: red;">*</span></label>
                    <select name="section" required class="form-control">
                        <option value="company" {{ old('section') == 'company' ? 'selected' : '' }}>THE COMPANY (Col 2)</option>
                        <option value="information" {{ old('section') == 'information' ? 'selected' : '' }}>MORE INFORMATION (Col 3)</option>
                    </select>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                        Decides under which column in the footer this page link will appear.
                    </div>
                </div>
            </div>

            <!-- Page Content / Details -->
            <div class="form-group">
                <label class="form-label">Page Content / Details <span style="color: red;">*</span></label>
                <div style="margin-bottom: 6px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<h2>', '</h2>')" title="Heading 2"><b>H2</b></button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<h3>', '</h3>')" title="Heading 3"><b>H3</b></button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<b>', '</b>')" title="Bold"><b>B</b></button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<i>', '</i>')" title="Italic"><i>I</i></button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<p>', '</p>')" title="Paragraph">&para;</button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="insertHtmlTag('<ul>\n  <li>', '</li>\n</ul>')" title="List">&bull; List</button>
                </div>
                <textarea name="content" id="pageContentTextarea" required class="form-control" rows="14" placeholder="Enter the full page content or HTML here...">{{ old('content') }}</textarea>
                <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                    Supports standard HTML tags (e.g. &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;table&gt;) for rich presentation.
                </div>
                @if(isset($errors) && $errors->has('content'))
                    <div style="color: #dc2626; font-size: 11.5px; margin-top: 4px;">{{ $errors->first('content') }}</div>
                @endif
            </div>

            <!-- Order & Status -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center;">
                <div class="form-group">
                    <label class="form-label">Display Order in Footer</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" placeholder="0">
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                        Lower numbers appear first (e.g. 1, 2, 3...).
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="status" value="1" {{ old('status', '1') ? 'checked' : '' }} style="width: 16px; height: 16px;">
                        <span style="font-size: 13px; font-weight: 600; color: #334155;">Active (Show link in footer &amp; allow view)</span>
                    </label>
                </div>
            </div>

            <!-- SEO Settings (Collapsible) -->
            <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                    <i class="fas fa-search"></i> SEO Settings (Optional)
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control" placeholder="Defaults to Page Title if left empty">
                </div>

                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2" placeholder="Brief summary of page for search engines">{{ old('meta_description') }}</textarea>
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Page
                </button>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function autoGenerateSlug(title) {
    const slugInput = document.getElementById('pageSlugInput');
    // Only auto-generate if user hasn't typed a custom slug or it's empty
    if (!slugInput.dataset.manualEdited) {
        slugInput.value = title
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
}

document.getElementById('pageSlugInput').addEventListener('input', function() {
    this.dataset.manualEdited = "true";
});

function insertHtmlTag(openTag, closeTag) {
    const textarea = document.getElementById('pageContentTextarea');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end) || 'Text here';
    const replacement = openTag + selectedText + closeTag;
    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + openTag.length, start + openTag.length + selectedText.length);
}
</script>
@endsection
