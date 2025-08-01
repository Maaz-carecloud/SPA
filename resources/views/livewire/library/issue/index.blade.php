<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Library Books Issued</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Student', 'Book', 'Issue Date', 'Due Date', 'Return Date', 'Notes', 'Status', 'Action'];
        $ajaxUrl = route('datatable.library.issues');
    @endphp
    <livewire:data-table :columns="$columns" table-id="issuesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <div wire:key="user-select-{{ $refreshKey }}">
                <x-form.select2 id="user_id" name="user_id" label="Student" wire:model="user_id" :options="$users" placeholder="Select a student..." />
            </div>
            <div wire:key="book-select-{{ $refreshKey }}">
                <x-form.select2 id="book_id" name="book_id" label="Book" wire:model="book_id" :options="$books" placeholder="Select a book..." />
            </div>
            
            @if($is_edit)
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input id="issue_date" type="date" name="issue_date" label="Issue Date" wire:model.live="issue_date" :error="$errors->first('issue_date')" />
                    </div>
                    <div class="col-md-6">
                        <x-form.input id="return_date" type="date" name="return_date" label="Return Date" wire:model="return_date" :error="$errors->first('return_date')" />
                    </div>
                </div>
            @else
                <x-form.input id="issue_date" type="date" name="issue_date" label="Issue Date" wire:model.live="issue_date" :error="$errors->first('issue_date')" />
            @endif
            
            <x-form.textarea id="notes" name="notes" label="Notes" wire:model="notes" :error="$errors->first('notes')" />
            
            <div wire:key="due-date-{{ $due_date }}">
                @if($due_date)
                    <div class="alert alert-info mt-2">
                        <small><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($due_date)->format('M d, Y') }} (14 days from issue date)</small>
                    </div>
                @endif
            </div>
        </form>
    </x-modal>

    <!-- Return Book Modal -->
    <x-modal id="returnModal" title="Return Book" action="returnBook" :is_edit="false" :is_not_crud="false">
        <form>
            @if($getIssue)
                <div class="alert alert-info mb-3">
                    <h6>Book Issue Details</h6>
                    <p><strong>Student:</strong> {{ $getIssue->user->name ?? 'N/A' }}</p>
                    <p><strong>Book:</strong> {{ $getIssue->book->name ?? 'N/A' }}</p>
                    <p><strong>Issue Date:</strong> {{ $getIssue->issue_date ? \Carbon\Carbon::parse($getIssue->issue_date)->format('M d, Y') : 'N/A' }}</p>
                    <p><strong>Due Date:</strong> {{ $getIssue->return_date ? \Carbon\Carbon::parse($getIssue->return_date)->format('M d, Y') : 'N/A' }}</p>
                    @if($getIssue->calculateFine() > 0)
                        <div class="alert alert-warning mt-2">
                            <strong>Fine Amount:</strong> Rs. {{ number_format($getIssue->calculateFine(), 2) }}
                            <br><small>{{ $getIssue->getDaysOverdue() }} days overdue @ Rs. 10/day</small>
                        </div>
                    @else
                        <div class="alert alert-success mt-2">
                            <strong>No Fine:</strong> Book returning on time
                        </div>
                    @endif
                </div>
            @endif

            <x-form.input id="actual_return_date" type="date" name="actual_return_date" label="Actual Return Date" 
                wire:model="actual_return_date" :error="$errors->first('actual_return_date')" />
            
            <x-form.textarea id="return_notes" name="return_notes" label="Return Notes" 
                wire:model="return_notes" :error="$errors->first('return_notes')" />
        </form>
    </x-modal>

    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('close-modal-js', (event) => {
                const modalId = event.modal;
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });

        // Add Bootstrap modal event listeners to refresh DataTable when modals are closed
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for when the create/edit modal is hidden
            const createModal = document.getElementById('createModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function () {
                    // Refresh the DataTable when modal is completely hidden
                    Livewire.dispatch('datatable-reinit');
                });
            }

            // Listen for when the return modal is hidden  
            const returnModal = document.getElementById('returnModal');
            if (returnModal) {
                returnModal.addEventListener('hidden.bs.modal', function () {
                    // Refresh the DataTable when return modal is completely hidden
                    Livewire.dispatch('datatable-reinit');
                });
            }
        });
    </script>
</x-sections.default>
