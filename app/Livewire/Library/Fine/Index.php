<?php

namespace App\Livewire\Library\Fine;

use App\Models\Fine;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Index extends Component
{
    public $fines;

    public function mount(){
        $this->loadOptions();
    }

    public function loadOptions(){
        $this->issues = Issue::with(['user', 'book'])
                           ->whereHas('fines')
                           ->orderBy('created_at', 'desc')
                           ->pluck('id', 'id')
                           ->map(function($id) {
                               $issue = Issue::with(['user', 'book'])->find($id);
                               return $issue->user->name . ' - ' . $issue->book->name;
                           })->toArray();
    }

    // Modal related methods
    public $modalTitle = 'Process Fine Payment';
    public $modalAction = 'process-payment';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required|exists:fines,id')]
    public $fine_id;
    #[Rule('required|numeric|min:0')]
    public $paid_amount;
    #[Rule('nullable|string')]
    public $payment_note;
    #[Rule('required|in:paid,waived')]
    public $payment_status;

    public $getFine;
    public $issues = [];

    #[On('process-payment')]
    public function processPayment(){
        $this->validate();
        
        $fine = Fine::findOrFail($this->fine_id);
        
        if ($this->payment_status === 'paid') {
            $fine->markAsPaid($this->paid_amount, $this->payment_note);
        } else {
            $fine->markAsWaived($this->payment_note);
        }
        
        $this->dispatch('success', message: 'Fine payment processed successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('payment-mode')]
    public function loadPaymentModal($id){
        $this->modalTitle = 'Process Fine Payment';
        $this->modalAction = 'process-payment';
        $this->is_edit = true;

        $this->getFine = Fine::with(['issue.user', 'issue.book'])->findOrFail($id);
        $this->fine_id = $this->getFine->id;
        $this->paid_amount = $this->getFine->amount;
        $this->payment_note = '';
        $this->payment_status = 'paid';
    }

    #[On('delete-record')]
    public function delete($id) {
        Fine::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Fine deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('process-payment-close')]
    public function resetFields(){
        $this->reset(['fine_id', 'paid_amount', 'payment_note', 'payment_status', 'getFine']);
        $this->modalTitle = 'Process Fine Payment';
        $this->modalAction = 'process-payment';
        $this->is_edit = false;
        $this->loadOptions();
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Library Fines')]
    #[Layout('layouts.app')]
    public function render()
    {
        // Ensure options are always available
        if (empty($this->issues)) {
            $this->loadOptions();
        }
        
        return view('livewire.library.fine.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = Fine::with(['issue.user', 'issue.book']);

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhere('amount', 'like', '%' . $search . '%')
                  ->orWhereHas('issue.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('issue.book', function($bookQuery) use ($search) {
                      $bookQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('author', 'like', '%' . $search . '%');
                  });
            });
        }

        $total = $query->count();

        if ($length == -1) {
            $length = $total;
        }

        $query->skip($start)->take($length);

        $fines = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($fines as $index => $fine) {
            $statusBadge = '';
            if ($fine->status === 'paid') {
                $statusBadge = '<span class="badge bg-success">Paid</span>';
            } elseif ($fine->status === 'waived') {
                $statusBadge = '<span class="badge bg-info">Waived</span>';
            } else {
                $statusBadge = '<span class="badge bg-danger">Pending</span>';
            }

            $actions = '<div class="action-items">';
            if ($fine->status === 'pending') {
                $actions .= '<span><a href="#" onclick="Livewire.dispatch(\'payment-mode\', {id: ' . $fine->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-credit-card"></i></a></span>';
            }
            $actions .= '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $fine->id . '"><i class="fa fa-trash"></i></a></span>';
            $actions .= '</div>';

            $rows[] = [
                $index + 1,
                e($fine->issue->user->name ?? 'N/A'),
                e($fine->issue->book->name ?? 'N/A'),
                'Rs. ' . number_format($fine->amount, 2),
                e($fine->reason),
                e($fine->fine_date ? $fine->fine_date->format('d M Y') : 'N/A'),
                e($fine->paid_date ? $fine->paid_date->format('d M Y') : 'Not Paid'),
                $statusBadge,
                $actions,
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
