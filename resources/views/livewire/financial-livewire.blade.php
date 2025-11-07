<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <h3>Catatan Keuangan</h3>
            </div>
            <div>
                <a href="{{ route('app.financial.statistics') }}" class="btn btn-info me-2">
                    <i class="bi bi-graph-up"></i> Statistik
                </a>
                <a href="{{ route('auth.logout') }}" class="btn btn-warning">Keluar</a>
            </div>
        </div>
        <div class="card-body">
            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pemasukan</h6>
                            <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pengeluaran</h6>
                            <h3>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ $balance >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                        <div class="card-body">
                            <h6 class="card-title">Saldo</h6>
                            <h3>Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Cari judul, kategori...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipe</label>
                            <select class="form-select" wire:model.live="filterType">
                                <option value="all">Semua</option>
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" wire:model.live="filterCategory">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" wire:model.live="startDate">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" wire:model.live="endDate">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-secondary w-100" wire:click="resetFilters">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="d-flex mb-2">
                <div class="flex-fill">
                    <h4>Daftar Transaksi</h4>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                        Tambah Catatan
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th wire:click="sortByColumn('transaction_date')" style="cursor: pointer;">
                                Tanggal
                                @if ($sortBy === 'transaction_date')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortByColumn('title')" style="cursor: pointer;">
                                Judul
                                @if ($sortBy === 'title')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th wire:click="sortByColumn('amount')" style="cursor: pointer;">
                                Jumlah
                                @if ($sortBy === 'amount')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $key => $record)
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td>{{ $record->transaction_date->format('d/m/Y') }}</td>
                                <td>{{ $record->title }}</td>
                                <td><span class="badge bg-secondary">{{ $record->category }}</span></td>
                                <td>
                                    @if ($record->type === 'income')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $record->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $record->type === 'income' ? '+' : '-' }} Rp {{ number_format($record->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('app.financial.detail', ['record_id' => $record->id]) }}" class="btn btn-sm btn-info">
                                        Detail
                                    </a>
                                    <button wire:click="prepareEditRecord({{ $record->id }})" class="btn btn-sm btn-warning">
                                        Edit
                                    </button>
                                    <button wire:click="prepareDeleteRecord({{ $record->id }})" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $records->links() }}
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modals.financial.add')
    @include('components.modals.financial.edit')
    @include('components.modals.financial.delete')
</div>