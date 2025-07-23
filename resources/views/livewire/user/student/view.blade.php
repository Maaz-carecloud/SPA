<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">Student Details</h6>
                <a wire:navigate href='{{ route('students') }}' class="btn btn-sm btn-primary btn-rounded-sm"><i class="fas fa-arrow-left me-1"></i>Go Back</a>
            </div>
            <div class="card-body">
                @if($student)
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $student->user->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $student->user->email ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Username</dt>
                        <dd class="col-sm-9">{{ $student->user->username ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9">{{ $student->user->phone ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Gender</dt>
                        <dd class="col-sm-9">{{ ucfirst($student->user->gender) ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Date of Birth</dt>
                        <dd class="col-sm-9">{{ $student->user->dob ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">CNIC</dt>
                        <dd class="col-sm-9">{{ $student->user->cnic ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Religion</dt>
                        <dd class="col-sm-9">{{ $student->user->religion ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">{{ $student->user->address ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Country</dt>
                        <dd class="col-sm-9">{{ $student->user->country ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">City</dt>
                        <dd class="col-sm-9">{{ $student->user->city ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">State</dt>
                        <dd class="col-sm-9">{{ $student->user->state ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Blood Group</dt>
                        <dd class="col-sm-9">{{ $student->user->blood_group ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Registration No</dt>
                        <dd class="col-sm-9">{{ $student->user->registration_no ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Transport Status</dt>
                        <dd class="col-sm-9">{{ $student->user->transport_status ? 'Active' : 'Inactive' }}</dd>
                        <dt class="col-sm-3">Transport ID</dt>
                        <dd class="col-sm-9">{{ $student->user->transport_id ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Active</dt>
                        <dd class="col-sm-9">{{ $student->user->is_active ? 'Yes' : 'No' }}</dd>
                        <dt class="col-sm-3">Parent</dt>
                        <dd class="col-sm-9">{{ $student->parent->user->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Admission Date</dt>
                        <dd class="col-sm-9">{{ $student->admission_date }}</dd>
                        <dt class="col-sm-3">Class</dt>
                        <dd class="col-sm-9">{{ $student->class->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Section</dt>
                        <dd class="col-sm-9">{{ $student->section->name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">Roll No</dt>
                        <dd class="col-sm-9">{{ $student->roll_no }}</dd>
                        <dt class="col-sm-3">Library Status</dt>
                        <dd class="col-sm-9">{{ $student->library_status ? 'Active' : 'Inactive' }}</dd>
                        <dt class="col-sm-3">Hostel Status</dt>
                        <dd class="col-sm-9">{{ $student->hostel_status ? 'Active' : 'Inactive' }}</dd>
                    </dl>
                @else
                    <p>Student not found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
@endpush
