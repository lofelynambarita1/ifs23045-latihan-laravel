<div class="modal fade" id="editTransactionModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form wire:submit.prevent="editTransaction">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe *</label>
                            <select class="form-select" wire:model.live="editType">
                                <option value="expense">Pengeluaran</option>
                                <option value="income">Pemasukan</option>
                            </select>
                            @error('editType') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" wire:model="editCategoryId">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories->where('type', $editType) as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('editCategoryId') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah *</label>
                            <input type="number" class="form-control" wire:model="editAmount" step="0.01" min="0">
                            @error('editAmount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal *</label>
                            <input type="date" class="form-control" wire:model="editDate">
                            @error('editDate') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3" wire:model="editDescription"></textarea>
                        @error('editDescription') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran (Gambar Baru)</label>
                        <input type="file" class="form-control" wire:model="editAttachment" accept="image/*">
                        @error('editAttachment') <span class="text-danger">{{ $message }}</span> @enderror
                        @if ($editAttachment)
                            <div class="mt-2">
                                <img src="{{ $editAttachment->temporaryUrl() }}" style="max-width: 200px">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>