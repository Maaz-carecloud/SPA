<?php

namespace App\Livewire\Library\Book;

use App\Models\Book;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    public Book $bookModel;
    public $name = '';
    public $subject_code = '';
    public $author = '';
    public $price = '';
    public $quantity = '';
    public $due_quantity = '';
    public $rack = '';

    protected $rules = [
        'name' => 'required|string|max:60',
        'subject_code' => 'required|string',
        'author' => 'required|string|max:100',
        'price' => 'required|integer|min:1',
        'quantity' => 'required|integer|min:1',
        'due_quantity' => 'required|integer|min:0',
        'rack' => 'required|string',
    ];

    protected $messages = [
        'name.required' => 'Book title is required.',
        'name.max' => 'Book title cannot exceed 60 characters.',
        'subject_code.required' => 'Subject code is required.',
        'author.required' => 'Author name is required.',
        'author.max' => 'Author name cannot exceed 100 characters.',
        'price.required' => 'Price is required.',
        'price.integer' => 'Price must be a valid number.',
        'price.min' => 'Price must be at least 1.',
        'quantity.required' => 'Quantity is required.',
        'quantity.integer' => 'Quantity must be a valid number.',
        'quantity.min' => 'Quantity must be at least 1.',
        'due_quantity.required' => 'Due quantity is required.',
        'due_quantity.integer' => 'Due quantity must be a valid number.',
        'due_quantity.min' => 'Due quantity cannot be negative.',
        'rack.required' => 'Rack location is required.',
    ];

    public function mount($id)
    {
        $this->bookModel = Book::findOrFail($id);
        $this->name = $this->bookModel->name;
        $this->subject_code = $this->bookModel->subject_code;
        $this->author = $this->bookModel->author;
        $this->price = $this->bookModel->price;
        $this->quantity = $this->bookModel->quantity;
        $this->due_quantity = $this->bookModel->due_quantity;
        $this->rack = $this->bookModel->rack;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        try {
            // Ensure due_quantity is not greater than quantity
            if ($this->due_quantity > $this->quantity) {
                $this->addError('due_quantity', 'Due quantity cannot be greater than total quantity.');
                return;
            }

            $this->bookModel->update([
                'name' => $this->name,
                'subject_code' => $this->subject_code,
                'author' => $this->author,
                'price' => (int) $this->price,
                'quantity' => (int) $this->quantity,
                'due_quantity' => (int) $this->due_quantity,
                'rack' => $this->rack,
            ]);

            $this->dispatch('success', message: 'Book updated successfully.');
            return $this->redirect('/library/books', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating book: ' . $e->getMessage());
        }
    }

    #[Title('Edit Book')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.library.book.edit');
    }
}
