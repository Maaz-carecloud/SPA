<?php

namespace App\Livewire\Library\Book;

use App\Models\Book;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    // Remove books property, DataTable will fetch via AJAX

    #[Title('Library Books')]
    #[Layout('layouts.app')]
    public function render() {
        return view('livewire.library.book.index');
    }

    // Modal related methods
    public $modalTitle = 'Add New Book';
    public $modalAction = 'create-book';
    public $is_edit = false;
    public $getBook;

    // Form fields
    public $name;
    public $subject_code;
    public $author;
    public $price;
    public $quantity;
    public $due_quantity;
    public $rack;

    #[On('create-book')]
    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'due_quantity' => 'required|integer|min:0',
            'rack' => 'required|string|max:50',
        ]);
        Book::create([
            'name' => $this->name,
            'subject_code' => $this->subject_code,
            'author' => $this->author,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'due_quantity' => $this->due_quantity,
            'rack' => $this->rack,
            'available_quantity' => $this->quantity - $this->due_quantity,
        ]);
        $this->dispatch('success', message: 'Book added successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id) {
        $this->modalTitle = 'Edit Book';
        $this->modalAction = 'edit-book';
        $this->is_edit = true;
        $this->getBook = Book::findOrFail($id);
        $this->name = $this->getBook->name;
        $this->subject_code = $this->getBook->subject_code;
        $this->author = $this->getBook->author;
        $this->price = $this->getBook->price;
        $this->quantity = $this->getBook->quantity;
        $this->due_quantity = $this->getBook->due_quantity;
        $this->rack = $this->getBook->rack;
    }

    #[On('edit-book')]
    public function update() {
        $this->validate([
            'name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'due_quantity' => 'required|integer|min:0',
            'rack' => 'required|string|max:50',
        ]);
        $b = Book::findOrFail($this->getBook->id);
        $b->name = $this->name;
        $b->subject_code = $this->subject_code;
        $b->author = $this->author;
        $b->price = $this->price;
        $b->quantity = $this->quantity;
        $b->due_quantity = $this->due_quantity;
        $b->rack = $this->rack;
        $b->available_quantity = $this->quantity - $this->due_quantity;
        $b->save();
        $this->dispatch('success', message: 'Book updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Book::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Book deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-book-close')]
    #[On('edit-book-close')]
    public function resetFields(){
        $this->reset(['name', 'subject_code', 'author', 'price', 'quantity', 'due_quantity', 'rack', 'getBook']);
        $this->modalTitle = 'Add New Book';
        $this->modalAction = 'create-book';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    // Server-side DataTable AJAX handler
    public function getDataTableRows()
    {
        $request = request();
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        if ($length == -1) {
            $length = 1000; // Safe upper limit for 'All'
        }
        $query = Book::orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('subject_code', 'like', "%$search%") ;
        }

        $total = $query->count();
        $books = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($books as $index => $book) {
            $stockBadge = '<span class="badge bg-' . ($book->available_quantity > 0 ? 'success' : 'danger') . '">' . $book->available_quantity . '/' . $book->quantity . '</span>';
            $actions = '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $book->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $book->id . '"><i class="fa fa-trash"></i></a></span>'
                . '</div>';
            $data[] = [
                $start + $index + 1,
                e($book->name),
                e($book->subject_code),
                e($book->author),
                'Rs. ' . number_format($book->price),
                $stockBadge,
                '<span class="badge bg-secondary">' . e($book->rack) . '</span>',
                $actions
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
}
