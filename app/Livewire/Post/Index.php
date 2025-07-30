<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $posts;

    public function mount(){
    }

    //Modal related methods
    public $modalTitle = 'Create Post';
    public $modalAction = 'create-post';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    //form related methods
    #[Rule('required')]
    public $title;
    #[Rule('required')]
    public $description;
    #[Rule('required')]
    public $author;
    #[Rule('nullable')]
    public $is_published;

    public $getPosts;

    #[On('create-post')]
    public function save(){
        $this->validate();
        Post::create([
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'is_published' => $this->is_published
        ]);
        $this->dispatch('success', message: 'Post created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Post';
        $this->modalAction = 'edit-post';
        $this->is_edit = true;

        $this->getPosts = Post::findOrfail($id);
        $this->title = $this->getPosts->title;
        $this->description = $this->getPosts->description;
        $this->author = $this->getPosts->author;
        $this->is_published = $this->getPosts->is_published;
       
    }

    #[On('edit-post')]
    public function update(){
        $this->validate();

        $p = Post::findOrFail($this->getPosts->id);
        $p->title = $this->title;
        $p->description = $this->description;
        $p->author = $this->author;
        $p->is_published = $this->is_published;
        $p->save();
        $this->dispatch('success', message: 'User updated successfully');

        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Post::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Post deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-post-close')]
    #[On('edit-post-close')]
    public function resetFields(){
        $this->reset(['title', 'description', 'author', 'is_published', 'getPosts']);
        $this->modalTitle = 'Create Post';
        $this->modalAction = 'create-post';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('All Posts')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.post.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = Post::query();

        // Search
        if (!empty($request['search']['value'])) {
            $query->where('title', 'like', '%' . $request['search']['value'] . '%');
        }

        $total = $query->count();

        if ($length == -1) {
            // Set a reasonable max limit for 'All'
            $length = $total;
        }

        $query->skip($start)->take($length);

        $posts = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($posts as $index => $post) {
            $rows[] = [
                $index + 1,
                e($post->title),
                e($post->description),
                e($post->author),
                $post->is_published == 1 ? 'Yes' : 'No',
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $post->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $post->id . '"><i class="fa fa-trash"></i></a></span>'
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
