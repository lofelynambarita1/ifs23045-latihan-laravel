<div class="mt-3">
    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">💰 Pemasukan</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($statistics['income'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">💸 Pengeluaran</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($statistics['expense'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card {{ $statistics['balance'] >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">💵 Saldo</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($statistics['balance'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>📊 Pengeluaran per Kategori</h5>
        </div>
        <div class="card-body">
            <div id="categoryChart"></div>
        </div>
    </div>

    {{-- Filter dan Action --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>📝 Daftar Transaksi</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                ➕ Tambah Transaksi
            </button>
        </div>
        <div class="card-body">
            {{-- Filter Section --}}
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" placeholder="🔍 Cari..." wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-md-2 mb-2">
                    <select class="form-select" wire:model.live="filterType">
                        <option value="all">Semua Tipe</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select class="form-select" wire:model.live="filterCategory">
                        <option value="all">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" class="form-control" wire:model.live="filterDateFrom">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" class="form-control" wire:model.live="filterDateTo">
                </div>
                <div class="col-md-1 mb-2">
                    <button class="btn btn-secondary w-100" wire:click="resetFilters">Reset</button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Deskripsi</th>
                            <th>Lampiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $key }}</td>
                                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                                <td>{{ $transaction->category->icon }} {{ $transaction->category->name }}</td>
                                <td>
                                    <span class="badge {{ $transaction->type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>{{ Str::limit($transaction->description ?? '-', 30) }}</td>
                                <td>
                                    @if($transaction->attachment)
                                        <a href="{{ asset('storage/' . $transaction->attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                            📎 Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="prepareEdit({{ $transaction->id }})" class="btn btn-sm btn-warning">Edit</button>
                                    <button wire:click="prepareDelete({{ $transaction->id }})" class="btn btn-sm btn-danger">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modals.transactions.add')
    @include('components.modals.transactions.edit')
    @include('components.modals.transactions.delete')

    {{-- ApexCharts Script --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const categoryData = @json($categoryData);
            
            const options = {
                series: categoryData.map(item => parseFloat(item.total)),
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: categoryData.map(item => item.name),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(document.querySelector("#categoryChart"), options);
            chart.render();

            Livewire.on('showAlert', (event) => {
                Swal.fire({
                    icon: event.icon,
                    title: event.title,
                    text: event.text,
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
    @endpush
</div>