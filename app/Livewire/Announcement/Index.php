<?php
namespace App\Livewire\Announcement;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $modalTitle = 'Create Announcement';
    public $modalAction = 'create-announcement';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;
    public $getAnnouncement;

    // Form fields
    #[Rule('required')]
    public $title;
    #[Rule('required')]
    public $content;
    #[Rule('nullable')]
    public $author;
    #[Rule('nullable')]
    public $is_published;
    #[Rule('nullable')]
    public $published_at;
    #[Rule('nullable')]
    public $expires_at;
    #[Rule('nullable')]
    public $image;
    #[Rule('nullable')]
    public $link;
    #[Rule('nullable')]
    public $status;

    #[On('create-announcement')]
    public function save()
    {
        $this->validate();
        Announcement::create([
            'title' => $this->title,
            'content' => $this->content,
            'author' => auth()->user()->name,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'expires_at' => $this->expires_at,
            'image' => $this->image,
            'link' => $this->link,
            'status' => $this->status ?? 'active',
        ]);
        $this->dispatch('success', message: 'Announcement created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id)
    {
        $this->modalTitle = 'Edit Announcement';
        $this->modalAction = 'edit-announcement';
        $this->is_edit = true;
        $this->getAnnouncement = Announcement::findOrFail($id);
        $this->title = $this->getAnnouncement->title;
        $this->content = $this->getAnnouncement->content;
        $this->author = $this->getAnnouncement->author;
        $this->is_published = $this->getAnnouncement->is_published;
        $this->published_at = optional($this->getAnnouncement->published_at)->format('Y-m-d\TH:i');
        $this->expires_at = optional($this->getAnnouncement->expires_at)->format('Y-m-d\TH:i');
        $this->image = $this->getAnnouncement->image;
        $this->link = $this->getAnnouncement->link;
        $this->status = $this->getAnnouncement->status;
    }

    #[On('edit-announcement')]
    public function update()
    {
        $this->validate();
        $a = Announcement::findOrFail($this->getAnnouncement->id);
        $a->title = $this->title;
        $a->content = $this->content;
        $a->author = auth()->user()->name;
        $a->is_published = $this->is_published;
        $a->published_at = $this->published_at;
        $a->expires_at = $this->expires_at;
        $a->image = $this->image;
        $a->link = $this->link;
        $a->status = $this->status;
        $a->save();
        $this->dispatch('success', message: 'Announcement updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id)
    {
        Announcement::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Announcement deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-announcement-close')]
    #[On('edit-announcement-close')]
    public function resetFields()
    {
        $this->reset(['title', 'content', 'author', 'is_published', 'published_at', 'expires_at', 'image', 'link', 'status', 'getAnnouncement']);
        $this->modalTitle = 'Create Announcement';
        $this->modalAction = 'create-announcement';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Announcements')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.announcement.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = Announcement::query();

        // Search
        if (!empty($request['search']['value'])) {
            $query->where('title', 'like', '%' . $request['search']['value'] . '%')
                  ->orWhere('content', 'like', '%' . $request['search']['value'] . '%');
        }

        $total = $query->count();

        if ($length == -1) {
            $length = $total;
        }

        $query->skip($start)->take($length);

        $announcements = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($announcements as $index => $a) {
            $rows[] = [
                $index + 1,
                e($a->title),
                e($a->content),
                e($a->author),
                $a->is_published ? 'Yes' : 'No',
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $a->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $a->id . '"><i class="fa fa-trash"></i></a></span>'
                . '</div>',
            ];
        }

        return response()->json([
            'draw' => intval($request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
        ]);
    }
}
