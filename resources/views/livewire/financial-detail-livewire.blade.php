<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <a href="{{ route('app.financial.index') }}" class="text-decoration-none">
                    <small class="text-muted">&lt; Kembali</small>
                </a>
                <h3 class="mt-2">{{ $record->title }}</h3>
            </div>
            <div>
                @if ($record->receipt)
                    <button class="btn btn-danger" wire:click="deleteReceipt" wire:confirm="Yakin ingin menghapus bukti transaksi?">
                        Hapus Bukti
                    </button>
                @else
                    <button class="btn btn-primary" data-bs-target="#uploadReceiptModal" data-bs-toggle="modal">
                        Upload Bukti
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            {{-- Receipt Image --}}
            @if ($record->receipt)
                <div class="mb-4">
                    <label class="form-label fw-bold">Bukti Transaksi:</label>
                    <div>
                        <img src="{{ asset('storage/' . $record->receipt) }}" alt="Receipt" class="img-fluid rounded" style="max-width: 100%; max-height: 500px;">
                    </div>
                </div>
                <hr>
            @endif

            {{-- Details --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipe Transaksi:</label>
                    <div>
                        @if ($record->type === 'income')
                            <span class="badge bg-success fs-6">Pemasukan</span>
                        @else
                            <span class="badge bg-danger fs-6">Pengeluaran</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kategori:</label>
                    <div>
                        <span class="badge bg-secondary fs-6">{{ $record->category }}</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jumlah:</label>
                    <div>
                        <h4 class="{{ $record->type === 'income' ? 'text-success' : 'text-danger' }}">
                            {{ $record->type === 'income' ? '+' : '-' }} Rp {{ number_format($record->amount, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Transaksi:</label>
                    <div>
                        <p class="fs-5">{{ $record->transaction_date->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi:</label>
                <div class="p-3 bg-light rounded">
                    <p style="font-size: 16px; white-space: pre-wrap;">{{ $record->description ?: 'Tidak ada deskripsi' }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Dibuat pada:</label>
                    <p>{{ $record->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Terakhir diubah:</label>
                    <p>{{ $record->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Upload Receipt --}}
    @include('components.modals.financial.upload-receipt')
</div>