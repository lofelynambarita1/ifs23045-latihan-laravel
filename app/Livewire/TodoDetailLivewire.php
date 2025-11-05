<?php

namespace App\Livewire;

use App\Models\Todo;
use App\Models\Category; // ✅ Tambahkan model Category
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TodoDetailLivewire extends Component
{
    use WithFileUploads;

    public $todo;
    public $auth;
    public $categories = []; // ✅ Tambahkan properti untuk kategori
    public $type = 'todo';   // ✅ Tambahkan properti default agar tidak undefined

    public function mount()
    {
        $this->auth = Auth::user();

        $todo_id = request()->route('todo_id');
        $targetTodo = Todo::where('id', $todo_id)->first();
        if (!$targetTodo) {
            return redirect()->route('app.home');
        }

        $this->todo = $targetTodo;

        // ✅ Ambil semua kategori dari database agar tidak undefined
        $this->categories = Category::all();
    }

    public function render()
    {
        // ✅ Pastikan data dikirim ke view Livewire
        return view('livewire.todo-detail-livewire', [
            'categories' => $this->categories,
            'type' => $this->type,
        ]);
    }

    // ==========================================================
    // Ubah Cover Todo
    // ==========================================================
    public $editCoverTodoFile;

    public function editCoverTodo()
    {
        $this->validate([
            'editCoverTodoFile' => 'required|image|max:2048', // Maks 2MB
        ]);

        if ($this->editCoverTodoFile) {
            // Hapus cover lama jika ada
            if ($this->todo->cover && Storage::disk('public')->exists($this->todo->cover)) {
                Storage::disk('public')->delete($this->todo->cover);
            }

            $userId = $this->auth->id;
            $dateNumber = now()->format('YmdHis');
            $extension = $this->editCoverTodoFile->getClientOriginalExtension();
            $filename = $userId . '_' . $dateNumber . '.' . $extension;
            $path = $this->editCoverTodoFile->storeAs('covers', $filename, 'public');

            $this->todo->cover = $path;
            $this->todo->save();
        }

        $this->reset(['editCoverTodoFile']);
        $this->dispatch('closeModal', id: 'editCoverTodoModal');
    }
}
