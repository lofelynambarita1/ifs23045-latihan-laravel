<form wire:submit.prevent="deleteTodo">
    <div class="modal fade" id="deleteTodoModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Todo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger">
                        ⚠️ Apakah kamu yakin ingin menghapus todo dengan judul:
                        <strong>"{{ $deleteTodoTitle }}"</strong>?
                        <br>
                        <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ketik ulang judul untuk konfirmasi</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="Masukkan ulang judul todo" 
                            wire:model="deleteTodoConfirmTitle"
                        >
                        @error('deleteTodoConfirmTitle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Ya, Hapus Todo
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
