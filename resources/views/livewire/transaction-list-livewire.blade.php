<div>
  <div class="mb-3 d-flex gap-2">
    <input wire:model.debounce.400ms="search" class="form-control" placeholder="Cari judul atau deskripsi...">
    <select wire:model="filterType" class="form-select">
      <option value="">Semua Tipe</option>
      <option value="income">Pemasukan</option>
      <option value="expense">Pengeluaran</option>
    </select>
    <select wire:model="filterCategory" class="form-select">
      <option value="">Semua Kategori</option>
      @foreach($categories as $cat)
         <option value="{{ $cat->id }}">{{ $cat->name }}</option>
      @endforeach
    </select>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Tambah</button>
  </div>

  <table class="table">
    <thead> ... </thead>
    <tbody>
      @foreach($transactions as $t)
        <tr>
           <td>{{ $t->transaction_date?->format('Y-m-d') }}</td>
           <td>{{ $t->title }}</td>
           <td>{{ $t->category?->name }}</td>
           <td>{{ number_format($t->amount, 2) }}</td>
           <td>
             <button wire:click="$emitTo('transaction-form-livewire','openEdit', {{ $t->id }})" class="btn btn-sm btn-warning">Edit</button>
             <button wire:click="$emitTo('transaction-delete-modal','confirmDelete', {{ $t->id }})" class="btn btn-sm btn-danger">Hapus</button>
           </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $transactions->links() }} {{-- pagination (20 per page) --}}
</div>

{{-- mount modals (Livewire components) --}}
<livewire:transaction-form-livewire />
<livewire:transaction-delete-modal />
