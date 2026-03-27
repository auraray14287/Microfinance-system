<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Transfer — RafikiBora</title>
    @filamentStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin:0; font-family: ui-sans-serif, system-ui, sans-serif; background:#f3f4f6; color:#111827; min-height:100vh; }
        .topbar { background:#fff; border-bottom:1px solid #e5e7eb; padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .topbar-brand { font-weight:700; font-size:1rem; color:#16a34a; text-decoration:none; }
        .topbar-back { font-size:0.85rem; color:#6b7280; text-decoration:none; }
        .topbar-back:hover { color:#2563eb; }
        .page-body { padding:2rem 1rem; max-width:720px; margin:0 auto; }
        .card { background:#fff; border-radius:12px; box-shadow:0 1px 12px rgba(0,0,0,0.08); padding:2rem; }
        .page-title { font-size:1.25rem; font-weight:700; margin:0 0 0.2rem; }
        .page-subtitle { font-size:0.875rem; color:#6b7280; margin:0 0 1.5rem; }
        .member-info { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.5rem; }
        .member-name { font-weight:700; font-size:1rem; color:#166534; margin:0 0 0.4rem; }
        .member-info p { margin:0.2rem 0; font-size:0.875rem; color:#374151; }
        .alert-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem; font-size:0.9rem; }
        .alert-error   { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem; font-size:0.875rem; }
        .section-label { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; display:block; margin-bottom:0.85rem; }
        .form-group { margin-bottom:1.15rem; }
        .form-group label { display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.35rem; }
        .form-group input[type="text"], .form-group textarea {
            width:100%; padding:0.6rem 0.85rem; border:1px solid #d1d5db; border-radius:8px;
            font-size:0.9rem; color:#111827; background:#fff; font-family:inherit;
            transition:border-color 0.15s, box-shadow 0.15s;
        }
        .form-group input:focus, .form-group textarea:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
        .form-group input[readonly] { background:#f9fafb; color:#6b7280; cursor:not-allowed; }
        .form-group textarea { resize:vertical; min-height:100px; }
        .field-error { color:#dc2626; font-size:0.8rem; margin-top:0.25rem; }
        .autocomplete-wrap { position:relative; }
        .suggestions-list {
            position:absolute; z-index:100; width:100%; background:#fff;
            border:1px solid #d1d5db; border-top:none; border-radius:0 0 8px 8px;
            box-shadow:0 4px 16px rgba(0,0,0,0.1); max-height:260px; overflow-y:auto; display:none;
        }
        .suggestion-item { padding:0.65rem 0.85rem; cursor:pointer; border-bottom:1px solid #f3f4f6; transition:background 0.1s; }
        .suggestion-item:last-child { border-bottom:none; }
        .suggestion-item:hover, .suggestion-item.active { background:#eff6ff; }
        .suggestion-name { font-size:0.9rem; font-weight:600; color:#111827; }
        .suggestion-sub  { font-size:0.775rem; color:#6b7280; margin-top:1px; }
        .no-results { padding:0.65rem 0.85rem; font-size:0.875rem; color:#9ca3af; font-style:italic; }
        .divider { border:none; border-top:1px solid #e5e7eb; margin:1.25rem 0; }
        .req { color:#dc2626; }
        .btn-row { display:flex; gap:0.75rem; margin-top:1.75rem; justify-content:flex-end; }
        .btn { padding:0.6rem 1.5rem; border-radius:8px; font-size:0.9rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; display:inline-block; transition:background 0.15s; font-family:inherit; }
        .btn-primary { background:#2563eb; color:#fff; }
        .btn-primary:hover { background:#1d4ed8; }
        .btn-cancel { background:#f1f5f9; color:#374151; border:1px solid #d1d5db; }
        .btn-cancel:hover { background:#e2e8f0; }
    </style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('filament.admin.pages.member-profile') }}" class="topbar-brand">RafikiBora</a>
    <a href="{{ route('filament.admin.pages.member-profile') }}" class="topbar-back">← Back to Member Profile</a>
</div>

<div class="page-body">
<div class="card">

    <p class="page-title">Group Transfer</p>
    <p class="page-subtitle">Transfer member to a different group</p>

    <div class="member-info">
        <p class="member-name">{{ strtoupper(trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . $member->last_name)) }}</p>
        <p>ID Number: <strong>{{ $member->id_number }}</strong></p>
        <p>Mobile: <strong>{{ $member->mobile_no ?? '—' }}</strong></p>
        <p>Current Group: <strong>{{ $currentGroup?->name ?? '—' }}</strong></p>
    </div>

    @if(session('transfer_success'))
        <div class="alert-success">✓ {{ session('transfer_success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('member.transfer.submit', ['id' => $member->id]) }}" id="transferForm">
        @csrf
        <span class="section-label">Transfer Details</span>

        <div class="form-group">
            <label>Current Group</label>
            <input type="text" value="{{ $currentGroup?->name ?? 'No group assigned' }}" readonly>
        </div>

        <div class="form-group">
            <label for="group_search_input">Transfer To <span class="req">*</span></label>
            <div class="autocomplete-wrap" id="autocompleteWrap">
                <input type="text" id="group_search_input" placeholder="Type to search groups…" autocomplete="off">
                <div class="suggestions-list" id="suggestionsList"></div>
            </div>
            <input type="hidden" name="new_group_id" id="new_group_id" value="{{ old('new_group_id', '') }}">
            @error('new_group_id')<div class="field-error" id="groupSelectError">{{ $message }}</div>@enderror
        </div>

        <hr class="divider">

        <div class="form-group">
            <label for="reason">Reason for Transfer <span class="req">*</span></label>
            <textarea name="reason" id="reason" placeholder="e.g. member relocated, group dissolved, member request…" required>{{ old('reason') }}</textarea>
            @error('reason')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="btn-row">
            <a href="{{ route('filament.admin.pages.member-profile') }}" class="btn btn-cancel">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Transfer</button>
        </div>
    </form>

</div>
</div>

<script>
(function () {
    var allGroups = {!! $groupsJson !!};

    var input       = document.getElementById('group_search_input');
    var hiddenInput = document.getElementById('new_group_id');
    var list        = document.getElementById('suggestionsList');
    var activeIndex = -1;

    function showSuggestions(term) {
        var q = term.trim().toLowerCase();
        if (!q) { list.style.display = 'none'; return; }

        var matches = allGroups.filter(function(g) {
            return !g.current && g.name.toLowerCase().indexOf(q) !== -1;
        }).slice(0, 8);

        if (!matches.length) {
            list.innerHTML = '<div class="no-results">No groups found</div>';
            list.style.display = 'block';
            return;
        }

        list.innerHTML = matches.map(function(g, i) {
            var sub = [g.reg, g.loc].filter(Boolean).join(' · ');
            var idx = g.name.toLowerCase().indexOf(q);
            var highlighted = g.name.slice(0, idx) +
                '<strong style="color:#2563eb">' + g.name.slice(idx, idx + q.length) + '</strong>' +
                g.name.slice(idx + q.length);
            return '<div class="suggestion-item" data-id="' + g.id + '" data-name="' + g.name.replace(/"/g, '&quot;') + '" data-index="' + i + '">' +
                '<div class="suggestion-name">' + highlighted + '</div>' +
                (sub ? '<div class="suggestion-sub">' + sub + '</div>' : '') +
                '</div>';
        }).join('');

        list.style.display = 'block';
        activeIndex = -1;

        list.querySelectorAll('.suggestion-item').forEach(function(item) {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                selectGroup(this.dataset.id, this.dataset.name);
            });
        });
    }

    function selectGroup(id, name) {
        hiddenInput.value = id;
        input.value = name;
        list.style.display = 'none';
        input.style.borderColor = '#16a34a';
        input.style.boxShadow = '0 0 0 3px rgba(22,163,74,0.12)';
        var err = document.getElementById('groupSelectError');
        if (err) err.style.display = 'none';
    }

    function clearSelection() {
        hiddenInput.value = '';
        input.style.borderColor = '';
        input.style.boxShadow = '';
    }

    input.addEventListener('input', function() {
        clearSelection();
        showSuggestions(this.value);
    });

    input.addEventListener('blur', function() {
        setTimeout(function() { list.style.display = 'none'; }, 150);
    });

    input.addEventListener('focus', function() {
        if (this.value) showSuggestions(this.value);
    });

    input.addEventListener('keydown', function(e) {
        var items = list.querySelectorAll('.suggestion-item');
        if (!items.length) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            var item = items[activeIndex];
            selectGroup(item.dataset.id, item.dataset.name);
            return;
        } else if (e.key === 'Escape') {
            list.style.display = 'none'; return;
        }
        items.forEach(function(item, i) { item.classList.toggle('active', i === activeIndex); });
        if (activeIndex >= 0) items[activeIndex].scrollIntoView({ block: 'nearest' });
    });

    document.getElementById('transferForm').addEventListener('submit', function(e) {
        if (!hiddenInput.value) {
            e.preventDefault();
            input.style.borderColor = '#dc2626';
            input.style.boxShadow = '0 0 0 3px rgba(220,38,38,0.15)';
            input.focus();
            var err = document.getElementById('groupSelectError');
            if (!err) {
                err = document.createElement('div');
                err.id = 'groupSelectError';
                err.className = 'field-error';
                err.textContent = 'Please select a group from the list.';
                document.getElementById('autocompleteWrap').parentNode.appendChild(err);
            } else {
                err.style.display = '';
                err.textContent = 'Please select a group from the list.';
            }
        }
    });
})();
</script>

@filamentScripts
</body>
</html>