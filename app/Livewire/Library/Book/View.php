<?php

namespace App\Livewire\Library\Book;

use App\Models\Book;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class View extends Component
{
    public Book $book;

    public function mount($id)
    {
        $this->book = Book::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.library.book.view');
    }
}
