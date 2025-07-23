<?php

namespace App\Livewire\Library\Member;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Section;
use App\Models\LibraryMember;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public function render()
    {
        return view('livewire.library.member.index');
    }
}
