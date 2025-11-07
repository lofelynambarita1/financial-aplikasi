<form wire:submit.prevent="uploadReceipt">
    <div class="modal fade" tabindex="-1" id="uploadReceiptModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Gambar Bukti Transaksi</label>
                        <input type="file" class="form-control" wire:model="uploadReceiptFile" accept="image/*">
                        @error('uploadReceiptFile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($uploadReceiptFile)
                        <div class="mb-3">
                            <label class="form-label">Preview:</label>
                            <img src="{{ $uploadReceiptFile->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <small>
                            <strong>Catatan:</strong>
                            <ul class="mb-0">
                                <li>Format file: JPG, PNG, atau JPEG</li>
                                <li>Ukuran maksimal: 2MB</li>
                            </ul>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @if (!$uploadReceiptFile) disabled @endif>
                        Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>