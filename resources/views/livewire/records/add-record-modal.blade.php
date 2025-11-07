<form wire:submit.prevent="addRecord">
    <div class="modal fade" tabindex="-1" id="addRecordModal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card p-2">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-glow">Tambah Catatan Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="addType">
                            <option value="">Pilih Tipe</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        @error('addType') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="addTitle" placeholder="Contoh: Gaji Bulanan">
                        @error('addTitle') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="addCategory" placeholder="Contoh: Gaji, Makan, Transport">
                        @error('addCategory') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" wire:model="addAmount" placeholder="0" step="0.01" min="0">
                        @error('addAmount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" wire:model="addTransactionDate">
                        @error('addTransactionDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="3" wire:model="addDescription" placeholder="Catatan tambahan (opsional)"></textarea>
                        @error('addDescription') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
