<?php

namespace App\Livewire\Library\Issue;

use App\Models\Issue;
use App\Models\Book;
use App\Models\LibraryMember;
use App\Models\Fine;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        $user = Auth::user();
        return view('livewire.library.issue.index');
    }
}
