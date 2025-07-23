<?php

namespace App\Livewire\Library\Book;

use App\Models\Book;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    #[Title('Library Books')]
    public function render()
    {
        return view('livewire.library.book.index');
    }
}
