<div class="mt-3">
    <div class="card">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <a href="{{ route('app.financial.index') }}" class="text-decoration-none">
                    <small class="text-muted">&lt; Kembali</small>
                </a>
                <h3 class="mt-2">Statistik Keuangan</h3>
            </div>
        </div>
        <div class="card-body">
            {{-- Filter --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" wire:model.live="selectedYear">
                        @for ($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bulan (untuk kategori)</label>
                    <select class="form-select" wire:model.live="selectedMonth">
                        @for ($month = 1; $month <= 12; $month++)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pemasukan ({{ $selectedYear }})</h6>
                            <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pengeluaran ({{ $selectedYear }})</h6>
                            <h3>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ ($totalIncome - $totalExpense) >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                        <div class="card-body">
                            <h6 class="card-title">Saldo ({{ $selectedYear }})</h6>
                            <h3>Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Monthly Trend Chart --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tren Bulanan {{ $selectedYear }}</h5>
                    <div id="monthlyTrendChart"></div>
                </div>
            </div>

            <div class="row mb-4">
                {{-- Income by Category --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pemasukan per Kategori</h5>
                            <div id="incomeCategoryChart"></div>
                        </div>
                    </div>
                </div>

                {{-- Expense by Category --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pengeluaran per Kategori</h5>
                            <div id="expenseCategoryChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Categories Tables --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Top 5 Kategori Pemasukan</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topIncomeCategories as $cat)
                                        <tr>
                                            <td>{{ $cat->category }}</td>
                                            <td class="text-end text-success fw-bold">Rp {{ number_format($cat->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Top 5 Kategori Pengeluaran</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topExpenseCategories as $cat)
                                        <tr>
                                            <td>{{ $cat->category }}</td>
                                            <td class="text-end text-danger fw-bold">Rp {{ number_format($cat->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:init', () => {
        // Monthly Trend Chart
        const monthlyData = @json($monthlyData);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        const incomeData = Array(12).fill(0);
        const expenseData = Array(12).fill(0);
        
        monthlyData.forEach(item => {
            incomeData[item.month - 1] = parseFloat(item.total_income);
            expenseData[item.month - 1] = parseFloat(item.total_expense);
        });

        const monthlyOptions = {
            series: [{
                name: 'Pemasukan',
                data: incomeData
            }, {
                name: 'Pengeluaran',
                data: expenseData
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: months,
            },
            yaxis: {
                title: {
                    text: 'Jumlah (Rp)'
                },
                labels: {
                    formatter: function (val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            colors: ['#28a745', '#dc3545']
        };

        const monthlyChart = new ApexCharts(document.querySelector("#monthlyTrendChart"), monthlyOptions);
        monthlyChart.render();

        // Income Category Chart
        const categoryData = @json($categoryData);
        const incomeCategories = categoryData.filter(item => item.type === 'income');
        const expenseCategories = categoryData.filter(item => item.type === 'expense');

        if (incomeCategories.length > 0) {
            const incomeOptions = {
                series: incomeCategories.map(item => parseFloat(item.total)),
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: incomeCategories.map(item => item.category),
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            const incomeChart = new ApexCharts(document.querySelector("#incomeCategoryChart"), incomeOptions);
            incomeChart.render();
        } else {
            document.querySelector("#incomeCategoryChart").innerHTML = '<p class="text-center text-muted">Belum ada data pemasukan</p>';
        }

        // Expense Category Chart
        if (expenseCategories.length > 0) {
            const expenseOptions = {
                series: expenseCategories.map(item => parseFloat(item.total)),
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: expenseCategories.map(item => item.category),
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            const expenseChart = new ApexCharts(document.querySelector("#expenseCategoryChart"), expenseOptions);
            expenseChart.render();
        } else {
            document.querySelector("#expenseCategoryChart").innerHTML = '<p class="text-center text-muted">Belum ada data pengeluaran</p>';
        }
    });
</script>
@endpush