<form wire:submit.prevent="deleteRecord">
    <div class="modal fade" tabindex="-1" id="deleteRecordModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Catatan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        Apakah kamu yakin ingin menghapus catatan dengan judul <strong>"{{ $deleteRecordTitle }}"</strong>?
                    </div>
                    <p class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                    <div class="mb-3">
                        <label class="form-label">Ketik judul catatan untuk konfirmasi</label>
                        <input type="text" class="form-control" wire:model="deleteConfirmTitle" placeholder="Ketik judul catatan">
                        @error('deleteConfirmTitle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>