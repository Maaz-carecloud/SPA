<div>
    <div class="row g-3">
        <div class="col-md-12">
            <x-form.input 
                label="Module Name"
                name="name"
                id="create-module-name" 
                model="name"
                placeholder="Enter module name"
                :required="true"
            />
            @error('name') 
                <div class="text-danger mt-1">{{ $message }}</div> 
            @enderror
        </div>
    </div>
</div>
