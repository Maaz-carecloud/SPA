<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">Parent Details</h6>
                <a wire:navigate href='{{ route('parents') }}' class="btn btn-sm btn-primary btn-rounded-sm"><i class="fas fa-arrow-left me-1"></i>Go Back</a>
            </div>
            <div class="card-body">
                @if($parent)
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $parent->user->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $parent->user->email ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Username</dt>
                        <dd class="col-sm-9">{{ $parent->user->username ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9">{{ $parent->user->phone ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Gender</dt>
                        <dd class="col-sm-9">{{ ucfirst($parent->user->gender) ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Date of Birth</dt>
                        <dd class="col-sm-9">{{ $parent->user->dob ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">CNIC</dt>
                        <dd class="col-sm-9">{{ $parent->user->cnic ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Religion</dt>
                        <dd class="col-sm-9">{{ $parent->user->religion ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">{{ $parent->user->address ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Country</dt>
                        <dd class="col-sm-9">{{ $parent->user->country ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">City</dt>
                        <dd class="col-sm-9">{{ $parent->user->city ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">State</dt>
                        <dd class="col-sm-9">{{ $parent->user->state ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Blood Group</dt>
                        <dd class="col-sm-9">{{ $parent->user->blood_group ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Registration No</dt>
                        <dd class="col-sm-9">{{ $parent->user->registration_no ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Transport Status</dt>
                        <dd class="col-sm-9">{{ $parent->user->transport_status ? 'Active' : 'Inactive' }}</dd>
                        <dt class="col-sm-3">Transport ID</dt>
                        <dd class="col-sm-9">{{ $parent->user->transport_id ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Active</dt>
                        <dd class="col-sm-9">{{ $parent->user->is_active ? 'Yes' : 'No' }}</dd>
                        <dt class="col-sm-3">Father Profession</dt>
                        <dd class="col-sm-9">{{ $parent->father_profession ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Mother Name</dt>
                        <dd class="col-sm-9">{{ $parent->mother_name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Mother Contact</dt>
                        <dd class="col-sm-9">{{ $parent->mother_contact ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Mother Profession</dt>
                        <dd class="col-sm-9">{{ $parent->mother_profession ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">NTN No</dt>
                        <dd class="col-sm-9">{{ $parent->ntn_no ?? 'N/A' }}</dd>
                    </dl>
                @else
                    <p>Parent not found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
