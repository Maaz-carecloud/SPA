<div class="row">
    <div class="col-md-6">
        <div class="card bgs-card mb-3">
            <div class="card-header">User Information</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Date of Birth:</strong> {{ $user->dob }}</p>
                <p><strong>Gender:</strong> {{ $user->gender }}</p>
                <p><strong>CNIC:</strong> {{ $user->cnic }}</p>
                <p><strong>Religion:</strong> {{ $user->religion }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                <p><strong>Address:</strong> {{ $user->address }}</p>
                <p><strong>Country:</strong> {{ $user->country }}</p>
                <p><strong>City:</strong> {{ $user->city }}</p>
                <p><strong>State:</strong> {{ $user->state }}</p>
                <p><strong>Blood Group:</strong> {{ $user->blood_group }}</p>
                <p><strong>Registration No:</strong> {{ $user->registration_no }}</p>
                <p><strong>Transport Status:</strong> {{ $user->transport_status ? 'Active' : 'Inactive' }}</p>
                <p><strong>Transport ID:</strong> {{ $user->transport_id }}</p>
                <p><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                @if($user->avatar)
                    <p><strong>Avatar:</strong><br><img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="80"></p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bgs-card mb-3">
            <div class="card-header">Teacher Information</div>
            <div class="card-body">
                <p><strong>Designation:</strong> {{ $teacher->designation->name ?? '' }}</p>
                <p><strong>Joining Date:</strong> {{ $teacher->joining_date }}</p>
                <p><strong>Qualification:</strong> {{ $teacher->qualification }}</p>
                <p><strong>Basic Salary:</strong> {{ $teacher->basic_salary }}</p>
            </div>
        </div>
    </div>
</div>
