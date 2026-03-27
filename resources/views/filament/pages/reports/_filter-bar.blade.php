{{--
  Shared filter bar partial.
  Usage: @include('filament.pages.reports._filter-bar', ['fields' => [...], 'runAction' => 'runReport'])
  Each field: ['type'=>'text|select|date', 'wire'=>'property', 'label'=>'...', 'options'=>[...]]
--}}
<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#64748b;">
            🔍 Filters
        </span>
        <button wire:click="resetFilters" type="button"
            style="font-size:0.78rem;color:#64748b;background:none;border:none;cursor:pointer;text-decoration:underline;padding:0;">
            Reset
        </button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:0.75rem;align-items:end;">
        @foreach($fields as $f)
        <div>
            <label style="display:block;font-size:0.78rem;font-weight:600;color:#475569;margin-bottom:0.3rem;">
                {{ $f['label'] }}
            </label>
            @if($f['type'] === 'text')
                <input type="text" wire:model.live.debounce.400ms="{{ $f['wire'] }}"
                    placeholder="{{ $f['placeholder'] ?? 'Search…' }}"
                    style="width:100%;padding:0.45rem 0.7rem;border:1px solid #cbd5e1;border-radius:6px;font-size:0.85rem;font-family:inherit;">
            @elseif($f['type'] === 'date')
                <input type="date" wire:model.live="{{ $f['wire'] }}"
                    style="width:100%;padding:0.45rem 0.7rem;border:1px solid #cbd5e1;border-radius:6px;font-size:0.85rem;font-family:inherit;">
            @elseif($f['type'] === 'select')
                <select wire:model.live="{{ $f['wire'] }}"
                    style="width:100%;padding:0.45rem 0.7rem;border:1px solid #cbd5e1;border-radius:6px;font-size:0.85rem;font-family:inherit;background:#fff;">
                    <option value="">{{ $f['placeholder'] ?? 'All' }}</option>
                    @foreach($f['options'] as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            @endif
        </div>
        @endforeach

        <div style="display:flex;gap:0.5rem;align-items:flex-end;padding-bottom:1px;">
            <button wire:click="runReport" type="button"
                style="padding:0.45rem 1.1rem;background:#0f172a;color:#fff;border:none;border-radius:6px;font-size:0.85rem;font-weight:600;cursor:pointer;white-space:nowrap;">
                Apply
            </button>
        </div>
    </div>
</div>