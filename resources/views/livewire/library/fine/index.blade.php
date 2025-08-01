<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Library Fines</h3>
    </div>

    @php
        $columns = ['#', 'Student', 'Book', 'Amount', 'Reason', 'Fine Date', 'Paid Date', 'Status', 'Action'];
        $ajaxUrl = route('datatable.library.fines');
    @endphp
    <livewire:data-table :columns="$columns" table-id="finesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            @if($getFine)
                <div class="alert alert-info mb-3">
                    <h6>Fine Details</h6>
                    <p><strong>Student:</strong> {{ $getFine->issue->user->name ?? 'N/A' }}</p>
                    <p><strong>Book:</strong> {{ $getFine->issue->book->name ?? 'N/A' }}</p>
                    <p><strong>Fine Amount:</strong> Rs. {{ number_format($getFine->amount, 2) }}</p>
                    <p><strong>Reason:</strong> {{ $getFine->reason }}</p>
                </div>
            @endif

            <x-form.select id="payment_status" name="payment_status" label="Payment Status" wire:model="payment_status" 
                :options="['paid' => 'Mark as Paid', 'waived' => 'Waive Fine']" :error="$errors->first('payment_status')" />
            
            @if($payment_status === 'paid')
                <x-form.input id="paid_amount" type="number" step="0.01" name="paid_amount" label="Paid Amount" 
                    wire:model="paid_amount" :error="$errors->first('paid_amount')" />
            @endif
            
            <x-form.textarea id="payment_note" name="payment_note" label="Payment Note" 
                wire:model="payment_note" :error="$errors->first('payment_note')" />
        </form>
    </x-modal>
</x-sections.default>
