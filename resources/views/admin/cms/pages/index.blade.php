<x-admin-layout title="CMS Pages" section="cms" active="pages">

    <div style="margin-bottom:1.25rem;">
        <button type="button" class="add-btn" onclick="adminNavigate('{{ route('admin.cms.pages.create') }}')">+ Add Page</button>
    </div>

    @if($pages->isEmpty())
        <div class="admin-empty">No pages yet. Add your first page!</div>
    @else
        <div class="card" style="padding:0;overflow:hidden;">
            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>Page Title</th>
                        <th>URL Key</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr>
                        <td style="color:var(--muted);font-size:13px;">{{ $page->id }}</td>
                        <td>
                            <span style="color:var(--orange);font-weight:500;cursor:pointer;"
                                  onclick="adminNavigate('{{ route('admin.cms.pages.edit', $page) }}')">
                                {{ $page->title }}
                            </span>
                        </td>
                        <td style="font-family:monospace;font-size:13px;color:var(--muted);">/{{ $page->slug }}</td>
                        <td>
                            @if($page->status === 'active')
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-amber">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <button type="button" class="act-btn" onclick="previewCmsPage('{{ $page->slug }}')">
                                    👁 View
                                </button>
                                <button type="button" class="act-btn" onclick="adminNavigate('{{ route('admin.cms.pages.edit', $page) }}')">Edit</button>
                                <form method="POST" action="{{ route('admin.cms.pages.destroy', $page) }}"
                                      onsubmit="return confirm('Delete &quot;{{ $page->title }}&quot;?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-btn red">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
