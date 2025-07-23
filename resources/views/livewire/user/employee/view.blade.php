<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">Employee Details</h6>
                <a wire:navigate href='{{ route('employees') }}' class="btn btn-sm btn-primary btn-rounded-sm"><i class="fas fa-arrow-left me-1"></i>Go Back</a>
            </div>
            <div class="card-body">
                @if($employee)
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $employee->user->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $employee->user->email ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Username</dt>
                        <dd class="col-sm-9">{{ $employee->user->username ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9">{{ $employee->user->phone ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Gender</dt>
                        <dd class="col-sm-9">{{ ucfirst($employee->user->gender) ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Date of Birth</dt>
                        <dd class="col-sm-9">{{ $employee->user->dob ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">CNIC</dt>
                        <dd class="col-sm-9">{{ $employee->user->cnic ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Religion</dt>
                        <dd class="col-sm-9">{{ $employee->user->religion ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">{{ $employee->user->address ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Country</dt>
                        <dd class="col-sm-9">{{ $employee->user->country ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">City</dt>
                        <dd class="col-sm-9">{{ $employee->user->city ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">State</dt>
                        <dd class="col-sm-9">{{ $employee->user->state ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Blood Group</dt>
                        <dd class="col-sm-9">{{ $employee->user->blood_group ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Registration No</dt>
                        <dd class="col-sm-9">{{ $employee->user->registration_no ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Transport Status</dt>
                        <dd class="col-sm-9">{{ $employee->user->transport_status ? 'Active' : 'Inactive' }}</dd>
                        <dt class="col-sm-3">Transport ID</dt>
                        <dd class="col-sm-9">{{ $employee->user->transport_id ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Active</dt>
                        <dd class="col-sm-9">{{ $employee->user->is_active ? 'Yes' : 'No' }}</dd>
                        <dt class="col-sm-3">Designation</dt>
                        <dd class="col-sm-9">{{ $employee->designation->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Joining Date</dt>
                        <dd class="col-sm-9">{{ $employee->joining_date ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Qualification</dt>
                        <dd class="col-sm-9">{{ $employee->qualification ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Basic Salary</dt>
                        <dd class="col-sm-9">{{ $employee->basic_salary ?? 'N/A' }}</dd>
                    </dl>
                @else
                    <p>Employee not found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
