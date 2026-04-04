<x-admin-layout title="Email Templates" section="cms" active="emails">
<style>
/* Template card layout */
.template-card { background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; transition:box-shadow 0.2s; margin-bottom:1rem; }
.template-card:hover { box-shadow:0 4px 12px rgba(0,0,0,0.08); }
.template-card-header { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.5rem; gap:1rem; flex-wrap:wrap; }
.template-meta { flex:1; min-width:0; }
.template-name { font-family:'Playfair Display',serif; font-size:1.05rem; font-weight:700; color:var(--dark); }
.template-key { font-size:0.75rem; font-weight:700; letter-spacing:0.06em; color:var(--muted); text-transform:uppercase; margin-top:0.15rem; }
.template-subject { font-size:0.85rem; color:var(--muted); margin-top:0.25rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:420px; }
.template-card-actions { display:flex; gap:0.5rem; flex-shrink:0; }
/* Inline edit form */
.edit-form-wrap { display:none; flex-direction:column; gap:1.1rem; padding:1.5rem; border-top:1.5px solid var(--border); background:#fafafa; }
.edit-form-wrap.open { display:flex; }
.field-group { display:flex; flex-direction:column; gap:0.35rem; }
.field-label { font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--muted); }
.field-input { width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--border); border-radius:0.5rem; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:var(--dark); background:#fff; outline:none; transition:border-color 0.15s; box-sizing:border-box; }
.field-input:focus { border-color:var(--orange); }
.field-textarea { width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--border); border-radius:0.5rem; font-family:'Courier New',monospace; font-size:0.82rem; color:var(--dark); background:#fff; outline:none; transition:border-color 0.15s; box-sizing:border-box; min-height:340px; resize:vertical; line-height:1.5; }
.field-textarea:focus { border-color:var(--orange); }
/* Variable pills */
.vars-pill-row { display:flex; flex-wrap:wrap; gap:0.4rem; margin-top:0.25rem; }
.var-pill { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; padding:0.2rem 0.55rem; border-radius:999px; font-size:0.75rem; font-family:'Courier New',monospace; cursor:pointer; transition:background 0.1s; user-select:none; }
.var-pill:hover { background:#ffedd5; }
.form-actions { display:flex; gap:0.6rem; align-items:center; }
/* Preview modal */
.preview-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:2rem; }
.preview-overlay.open { display:flex; }
.preview-modal { background:#fff; border-radius:12px; width:100%; max-width:640px; max-height:85vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 25px 50px rgba(0,0,0,0.2); }
.preview-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1.5px solid var(--border); flex-shrink:0; }
.preview-title { font-weight:700; color:var(--dark); font-size:0.95rem; }
.btn-close-preview { background:none; border:none; font-size:1.4rem; cursor:pointer; color:var(--muted); line-height:1; padding:0; }
.preview-iframe-wrap { flex:1; overflow:hidden; }
.preview-iframe { width:100%; height:100%; border:none; min-height:500px; }
</style>

{{-- Info Banner --}}
<div class="info-banner" style="margin-bottom:1.75rem;">
    <span style="font-size:1.3rem;flex-shrink:0;">📧</span>
    <div>
        These are the transactional email templates used by your store. Edit the <strong>subject</strong> and <strong>HTML body</strong> of each template.
        Use <code style="background:#ffedd5;padding:0 4px;border-radius:3px;">{{ '{{variable_name}}' }}</code> placeholders - the available variables are shown on each template.
        Click <strong>Preview</strong> to see how the template will render.
    </div>
</div>

{{-- Template List --}}
<div class="template-list">
    @foreach($templates as $tpl)
    <div class="template-card" id="card-{{ $tpl->id }}">

        {{-- Card header --}}
        <div class="template-card-header">
            <div class="template-meta">
                <p class="template-name">{{ $tpl->name }}</p>
                <p class="template-key">{{ $tpl->key }}</p>
                <p class="template-subject">Subject: {{ $tpl->subject }}</p>
            </div>
            <div class="template-card-actions">
                <button type="button" class="act-btn blue" onclick="openPreview({{ $tpl->id }})">
                    👁 Preview
                </button>
                <button type="button" class="act-btn" id="editBtn-{{ $tpl->id }}"
                        onclick="toggleEdit({{ $tpl->id }})">
                    ✏️ Edit
                </button>
            </div>
        </div>

        {{-- Inline edit form --}}
        <div class="edit-form-wrap" id="editForm-{{ $tpl->id }}">
            <form method="POST" action="/admin/email-templates/{{ $tpl->id }}">
                @csrf @method('PUT')

                {{-- Subject --}}
                <div class="field-group">
                    <label class="field-label" for="subject-{{ $tpl->id }}">Subject Line</label>
                    <input type="text" id="subject-{{ $tpl->id }}" name="subject"
                           class="field-input" value="{{ $tpl->subject }}" required>
                </div>

                {{-- Variables helper --}}
                @if($tpl->variables)
                <div class="field-group">
                    <span class="field-label">Available Variables — click to copy</span>
                    <div class="vars-pill-row">
                        @foreach($tpl->variableList() as $var)
                        @php $varTag = '{{' . $var . '}}'; @endphp
                        <span class="var-pill" data-var="{{ $varTag }}"
                              onclick="copyVar(this.dataset.var, this)"
                              title="Click to copy">{{ $varTag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Body --}}
                <div class="field-group">
                    <label class="field-label" for="body-{{ $tpl->id }}">HTML Body</label>
                    <textarea id="body-{{ $tpl->id }}" name="body"
                              class="field-textarea" required>{{ $tpl->body }}</textarea>
                </div>

                {{-- Variables list (editable) --}}
                <div class="field-group">
                    <label class="field-label" for="variables-{{ $tpl->id }}">Variables List (comma-separated)</label>
                    <input type="text" id="variables-{{ $tpl->id }}" name="variables"
                           class="field-input" value="{{ $tpl->variables }}"
                           placeholder="e.g. customer_name, order_id, order_total">
                </div>

                <div class="form-actions">
                    <button type="submit" class="add-btn">Save Template</button>
                    <button type="button" class="act-btn"
                            onclick="toggleEdit({{ $tpl->id }})">Cancel</button>
                </div>
            </form>
        </div>

    </div>
    @endforeach
</div>

{{-- Preview Modal --}}
<div class="preview-overlay" id="previewOverlay" onclick="if(event.target===this)closePreview()">
    <div class="preview-modal">
        <div class="preview-header">
            <span class="preview-title" id="previewTitle">Template Preview</span>
            <button type="button" class="btn-close-preview" onclick="closePreview()">×</button>
        </div>
        <div class="preview-iframe-wrap">
            <iframe class="preview-iframe" id="previewFrame" src="about:blank"></iframe>
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const form   = document.getElementById('editForm-' + id);
    const btn    = document.getElementById('editBtn-' + id);
    const isOpen = form.classList.contains('open');
    // Close all other open forms
    document.querySelectorAll('.edit-form-wrap.open').forEach(f => {
        f.classList.remove('open');
        const b = document.getElementById('editBtn-' + f.id.replace('editForm-', ''));
        if (b) b.classList.remove('active');
    });
    if (!isOpen) {
        form.classList.add('open');
        btn.classList.add('active');
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function copyVar(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = el.textContent;
        el.textContent = '✓ Copied!';
        el.style.background = '#dcfce7';
        el.style.color = '#16a34a';
        el.style.borderColor = '#86efac';
        setTimeout(() => {
            el.textContent = orig;
            el.style.background = '';
            el.style.color = '';
            el.style.borderColor = '';
        }, 1200);
    });
}

function openPreview(id) {
    const overlay = document.getElementById('previewOverlay');
    const frame   = document.getElementById('previewFrame');
    overlay.classList.add('open');
    frame.src = '/admin/email-templates/' + id + '/preview';
}

function closePreview() {
    const overlay = document.getElementById('previewOverlay');
    const frame   = document.getElementById('previewFrame');
    overlay.classList.remove('open');
    frame.src = 'about:blank';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });
</script>

</x-admin-layout>
