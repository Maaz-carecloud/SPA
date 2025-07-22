<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $posts;

    public function mount(){
        // $this->posts = Post::orderByDesc('created_at')->get();
        $this->loadPosts();
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

        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Post::findOrFail($id)->delete();
        $this->loadPosts();
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
        $this->loadPosts();
        $this->dispatch('datatable-reinit');
    }

    public function loadPosts(){
        $this->posts = Post::orderByDesc('created_at')->get();
    }

    #[Title('All Posts')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.post.index');
    }

}
