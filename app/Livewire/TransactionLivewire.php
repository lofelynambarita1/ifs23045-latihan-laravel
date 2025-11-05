<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TransactionLivewire extends Component
{
    use WithFileUploads, WithPagination;

    // Form Properties
    public $type = 'expense';
    public $category_id;
    public $amount;
    public $description;
    public $date;
    public $attachment;
    
    // Edit Properties
    public $editId;
    public $editType;
    public $editCategoryId;
    public $editAmount;
    public $editDescription;
    public $editDate;
    public $editAttachment;
    
    // Delete Properties
    public $deleteId;
    public $deleteAmount;
    
    // Filter Properties
    public $search = '';
    public $filterType = 'all';
    public $filterCategory = 'all';
    public $filterDateFrom;
    public $filterDateTo;
    
    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'attachment' => 'nullable|image|max:2048',
        ];
    }

    protected function editRules()
    {
        return [
            'editType' => 'required|in:income,expense',
            'editCategoryId' => 'required|exists:categories,id',
            'editAmount' => 'required|numeric|min:0',
            'editDescription' => 'nullable|string',
            'editDate' => 'required|date',
            'editAttachment' => 'nullable|image|max:2048',
        ];
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->filterDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterDateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedType()
    {
        $this->category_id = null;
    }

    public function updatedEditType()
    {
        $this->editCategoryId = null;
    }

    public function addTransaction()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'type' => $this->type,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
        ];

        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('attachments', 'public');
        }

        Transaction::create($data);

        $this->reset(['type', 'category_id', 'amount', 'description', 'attachment']);
        $this->date = now()->format('Y-m-d');
        
        $this->dispatch('closeModal', ['id' => 'addTransactionModal']);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil ditambahkan'
        ]);
    }

    public function prepareEdit($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $this->editId = $transaction->id;
        $this->editType = $transaction->type;
        $this->editCategoryId = $transaction->category_id;
        $this->editAmount = $transaction->amount;
        $this->editDescription = $transaction->description;
        $this->editDate = $transaction->date->format('Y-m-d');
        
        $this->dispatch('showModal', ['id' => 'editTransactionModal']);
    }

    public function editTransaction()
    {
        $this->validate($this->editRules());

        $transaction = Transaction::findOrFail($this->editId);

        $data = [
            'type' => $this->editType,
            'category_id' => $this->editCategoryId,
            'amount' => $this->editAmount,
            'description' => $this->editDescription,
            'date' => $this->editDate,
        ];

        if ($this->editAttachment) {
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }
            $data['attachment'] = $this->editAttachment->store('attachments', 'public');
        }

        $transaction->update($data);

        $this->reset(['editId', 'editType', 'editCategoryId', 'editAmount', 'editDescription', 'editDate', 'editAttachment']);
        
        $this->dispatch('closeModal', ['id' => 'editTransactionModal']);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil diubah'
        ]);
    }

    public function prepareDelete($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $this->deleteId = $transaction->id;
        $this->deleteAmount = number_format($transaction->amount, 0, ',', '.');
        
        $this->dispatch('showModal', ['id' => 'deleteTransactionModal']);
    }

    public function deleteTransaction()
    {
        $transaction = Transaction::findOrFail($this->deleteId);
        
        if ($transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
        }
        
        $transaction->delete();

        $this->reset(['deleteId', 'deleteAmount']);
        
        $this->dispatch('closeModal', ['id' => 'deleteTransactionModal']);
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil dihapus'
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = 'all';
        $this->filterCategory = 'all';
        $this->filterDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterDateTo = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    public function getStatistics()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$this->filterDateFrom, $this->filterDateTo]);

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
        ];
    }

    public function getCategoryData()
    {
        $expenseByCategory = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->whereBetween('date', [$this->filterDateFrom, $this->filterDateTo])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return $expenseByCategory->map(function($item) {
            return [
                'name' => $item->category->name ?? 'Unknown',
                'total' => $item->total
            ];
        });
    }

    public function render()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$this->filterDateFrom, $this->filterDateTo]);

        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterCategory !== 'all') {
            $query->where('category_id', $this->filterCategory);
        }

        $transactions = $query->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::all();
        $statistics = $this->getStatistics();
        $categoryData = $this->getCategoryData();

        return view('livewire.transaction-livewire', compact(
            'transactions',
            'categories',
            'statistics',
            'categoryData'
        ));
    }
}