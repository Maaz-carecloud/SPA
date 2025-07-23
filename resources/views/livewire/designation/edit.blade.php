<div>
    @if($designationId)
        <div class="row g-3">
            <div class="col-md-12">
                <x-form.input 
                    label="Designation Name"
                    name="name"
                    id="edit-designation-name" 
                    model="name"
                    placeholder="Enter designation name"
                    :required="true"
                />
                @error('name') 
                    <div class="text-danger mt-1">{{ $message }}</div> 
                @enderror
            </div>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-muted">No designation selected for editing.</p>
        </div>
    @endif
</div>
