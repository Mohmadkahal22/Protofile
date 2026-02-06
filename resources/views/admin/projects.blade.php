@extends('layouts.admin')
@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')
<div class="table-card">
    <div class="table-header">
        <h2><i class="fas fa-briefcase" style="color:var(--green);margin-right:0.5rem;font-size:0.85rem;"></i> Projects</h2>
        <div class="table-actions">
            <input type="text" class="search-input" id="search" placeholder="Search projects..." oninput="filterTable()">
            <button class="btn btn-primary" onclick="openModal()"><i class="fas fa-plus"></i> Add Project</button>
        </div>
    </div>
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Service</th><th>Client</th><th>Status</th><th>Images</th><th style="text-align:right;">Actions</th></tr></thead>
        <tbody id="table-body"><tr class="loading-row"><td colspan="6"><span class="spinner"></span> Loading...</td></tr></tbody>
    </table>
    <div class="pagination-bar" id="pagination-bar" style="display:none;">
        <div class="pagination-info" id="pagination-info"></div>
        <div class="pagination-btns" id="pagination-btns"></div>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modal-overlay">
    <div class="modal" style="max-width:700px;">
        <div class="modal-header">
            <h3 id="modal-title">Add Project</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="modal-form" onsubmit="saveItem(event)">
            <div class="modal-body">
                <input type="hidden" id="edit-id">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" class="form-input" id="f-title" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service *</label>
                        <select class="form-select" id="f-services_id" required>
                            <option value="">Select service...</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea class="form-textarea" id="f-description" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-input" id="f-client_name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="f-status">
                            <option value="completed">Completed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="planned">Planned</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Project URL</label>
                        <input type="url" class="form-input" id="f-project_url" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub URL</label>
                        <input type="url" class="form-input" id="f-github_url" placeholder="https://github.com/...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-input" id="f-start_date">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-input" id="f-end_date">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Technologies (comma separated)</label>
                    <input type="text" class="form-input" id="f-technologies" placeholder="Laravel, Vue.js, MySQL...">
                </div>
                <div class="form-group">
                    <label class="form-label">Images</label>
                    <input type="file" class="form-input" id="f-images" accept="image/*" multiple>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="save-btn"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
var allItems = [], currentPage = 1, perPage = 10;

// Load services for dropdown
axios.get(API_BASE + '/services/index').then(function(r) {
    var sel = document.getElementById('f-services_id');
    (r.data.data || []).forEach(function(s) {
        sel.innerHTML += '<option value="' + s.id + '">' + s.title + '</option>';
    });
});

function loadData(page) {
    currentPage = page || 1;
    var tbody = document.getElementById('table-body');
    tbody.innerHTML = '<tr class="loading-row"><td colspan="6"><span class="spinner"></span> Loading...</td></tr>';
    axios.get(API_BASE + '/projects/index?page=' + currentPage + '&per_page=' + perPage)
        .then(function(r) {
            allItems = r.data.data || [];
            renderTable(allItems);
            if (r.data.pagination) renderPagination(r.data.pagination);
        })
        .catch(function() { tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><p>Failed to load</p></td></tr>'; });
}

function renderTable(items) {
    var tbody = document.getElementById('table-body');
    if (!items.length) { tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><i class="fas fa-briefcase" style="display:block;"></i><p>No projects found</p></td></tr>'; return; }
    tbody.innerHTML = '';
    items.forEach(function(p) {
        var svcName = p.service ? p.service.title : '—';
        var imgCount = p.images ? p.images.length : 0;
        var statusBadge = '<span class="status-badge status-' + (p.status||'completed') + '">' + (p.status||'completed') + '</span>';
        tbody.innerHTML += '<tr><td style="color:var(--text-primary);font-weight:600;">' + (p.title||'—') + '</td><td>' + svcName + '</td><td>' + (p.client_name||'—') + '</td><td>' + statusBadge + '</td><td>' + imgCount + '</td><td style="text-align:right;"><button class="btn btn-outline btn-sm" onclick="editItem(' + p.id + ')" style="margin-right:0.25rem;"><i class="fas fa-edit"></i></button><button class="btn btn-danger btn-sm" onclick="deleteItem(' + p.id + ')"><i class="fas fa-trash"></i></button></td></tr>';
    });
}

function renderPagination(pag) {
    document.getElementById('pagination-bar').style.display = 'flex';
    document.getElementById('pagination-info').textContent = 'Showing ' + (pag.from||0) + '-' + (pag.to||0) + ' of ' + pag.total;
    var btns = document.getElementById('pagination-btns');
    btns.innerHTML = '<button ' + (pag.current_page<=1?'disabled':'') + ' onclick="loadData(' + (pag.current_page-1) + ')"><i class="fas fa-chevron-left"></i></button>';
    for (var i=1;i<=pag.last_page;i++) btns.innerHTML += '<button class="' + (i===pag.current_page?'active':'') + '" onclick="loadData(' + i + ')">' + i + '</button>';
    btns.innerHTML += '<button ' + (pag.current_page>=pag.last_page?'disabled':'') + ' onclick="loadData(' + (pag.current_page+1) + ')"><i class="fas fa-chevron-right"></i></button>';
}

function filterTable() {
    var q = document.getElementById('search').value.toLowerCase();
    renderTable(allItems.filter(function(p) { return ((p.title||'') + ' ' + (p.client_name||'') + ' ' + (p.status||'')).toLowerCase().indexOf(q) > -1; }));
}

function openModal(item) {
    document.getElementById('modal-title').textContent = item ? 'Edit Project' : 'Add Project';
    document.getElementById('edit-id').value = item ? item.id : '';
    document.getElementById('f-title').value = item ? item.title||'' : '';
    document.getElementById('f-services_id').value = item ? item.services_id||'' : '';
    document.getElementById('f-description').value = item ? item.description||'' : '';
    document.getElementById('f-client_name').value = item ? item.client_name||'' : '';
    document.getElementById('f-status').value = item ? item.status||'completed' : 'completed';
    document.getElementById('f-project_url').value = item ? item.project_url||'' : '';
    document.getElementById('f-github_url').value = item ? item.github_url||'' : '';
    document.getElementById('f-start_date').value = item ? (item.start_date||'').substring(0,10) : '';
    document.getElementById('f-end_date').value = item ? (item.end_date||'').substring(0,10) : '';
    document.getElementById('f-technologies').value = item ? item.technologies||'' : '';
    document.getElementById('f-images').value = '';
    document.getElementById('modal-overlay').classList.add('open');
}

function closeModal() { document.getElementById('modal-overlay').classList.remove('open'); document.getElementById('modal-form').reset(); }

function editItem(id) {
    axios.get(API_BASE + '/projects/show/' + id)
        .then(function(r) { openModal(r.data.data); })
        .catch(function() { showToast('Failed to load', 'error'); });
}

function saveItem(e) {
    e.preventDefault();
    var id = document.getElementById('edit-id').value;
    var btn = document.getElementById('save-btn');
    btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Saving...';

    var fd = new FormData();
    fd.append('title', document.getElementById('f-title').value);
    fd.append('services_id', document.getElementById('f-services_id').value);
    fd.append('description', document.getElementById('f-description').value);
    fd.append('client_name', document.getElementById('f-client_name').value);
    fd.append('status', document.getElementById('f-status').value);
    fd.append('project_url', document.getElementById('f-project_url').value);
    fd.append('github_url', document.getElementById('f-github_url').value);
    fd.append('start_date', document.getElementById('f-start_date').value);
    fd.append('end_date', document.getElementById('f-end_date').value);
    fd.append('technologies', document.getElementById('f-technologies').value);
    var files = document.getElementById('f-images').files;
    for (var i = 0; i < files.length; i++) fd.append('images[]', files[i]);

    var url = id ? API_BASE + '/projects/update/' + id : API_BASE + '/projects/store';
    axios.post(url, fd, { headers:{'Content-Type':'multipart/form-data'} })
        .then(function() { showToast(id ? 'Project updated!' : 'Project created!'); closeModal(); loadData(currentPage); })
        .catch(function(err) {
            var msg = 'Save failed';
            if (err.response && err.response.data) { msg = err.response.data.errors ? Object.values(err.response.data.errors).flat().join(', ') : (err.response.data.message || msg); }
            showToast(msg, 'error');
        })
        .finally(function() { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Save'; });
}

function deleteItem(id) {
    if (!confirm('Delete this project?')) return;
    axios.delete(API_BASE + '/projects/delete/' + id)
        .then(function() { showToast('Project deleted!'); loadData(currentPage); })
        .catch(function() { showToast('Delete failed', 'error'); });
}

loadData(1);
</script>
@endpush

