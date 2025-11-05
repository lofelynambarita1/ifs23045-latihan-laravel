<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Judul *</label>
        <input type="text" class="form-control" wire:model="title">
        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori *</label>
        <select class="form-select" wire:model="category_id">
            <option value="">Pilih Kategori</option>

            {{-- ✅ Aman dari error meski $categories kosong --}}
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories->where('type', $type ?? 'todo') as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            @else
                <option value="" disabled>Tidak ada kategori</option>
            @endif
        </select>
        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <input id="description-add" type="hidden" wire:model="description">
    <trix-editor input="description-add"></trix-editor>
    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jumlah *</label>
        <input type="number" class="form-control" wire:model="amount" step="0.01" min="0">
        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
