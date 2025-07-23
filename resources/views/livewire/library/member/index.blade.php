<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>Library Members
            </h4>
            <p class="text-muted mb-0">Manage student library memberships</p>
        </div>
    </div>

    <!-- Class Filter -->
    @if(Auth::user()->user_type !== 'student')
        <div class="card bgs-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Select Class</label>
                        <select wire:model.live="selectedClassId" class="form-select">
                            <option value="0">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedClassId > 0)
                        <div class="col-md-4">
                            <label class="form-label">Search Students</label>
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                   class="form-control" placeholder="Search by name, email, or roll...">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Students Table -->
    @if(count($students) > 0)
        <div class="card bgs-card">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'all') active @endif" 
                                wire:click="setActiveTab('all')" 
                                type="button">
                            All Students ({{ count($students) }})
                        </button>
                    </li>
                    @foreach($sections as $section)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($activeTab === 'section_'.$section->id) active @endif" 
                                    wire:click="setActiveTab('section_{{ $section->id }}')" 
                                    type="button">
                                {{ $section->name }} 
                                @if(isset($allSectionStudents[$section->id]))
                                    ({{ count($allSectionStudents[$section->id]) }})
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3">
                    <!-- All Students Tab -->
                    @if($activeTab === 'all')
                        <x-data-table
                            :headers="['S.No', 'Photo', 'Name', 'Roll No', 'Email', 'Actions']"
                            :items="$students"
                            tableId="students-table"
                            :showPagination="false"
                            :showPerPageFilter="false"
                            :showSearch="false"
                            :showExport="false"
                        >
                            @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=35&background=007bff&color=ffffff' }}" 
                                             alt="{{ $student->name }}" 
                                             class="rounded-circle" 
                                             width="35" height="35">
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $student->name }}</div>
                                        @if($student->student && $student->student->section)
                                            <small class="text-muted">{{ $student->student->section->name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $student->registration_no ?? 'N/A' }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if(!$this->isLibraryMember($student->id))
                                            <a href="{{ route('library.member.create', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                               wire:navigate
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-plus me-1"></i>Add to Library
                                            </a>
                                        @else
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('library.member.view', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                                   wire:navigate
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('library.member.edit', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                                   wire:navigate
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button wire:click="deleteMember({{ $student->id }})" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to remove this library member?\n\nThis action cannot be undone and the student\'s library membership will be permanently removed.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </x-data-table>
                    @endif

                    <!-- Section-wise Tabs -->
                    @foreach($sections as $section)
                        @if($activeTab === 'section_'.$section->id && isset($allSectionStudents[$section->id]))
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="8%">S.No</th>
                                            <th width="12%">Photo</th>
                                            <th width="25%">Name</th>
                                            <th width="15%">Roll No</th>
                                            <th width="20%">Email</th>
                                            <th width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allSectionStudents[$section->id] as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=35&background=007bff&color=ffffff' }}" 
                                                         alt="{{ $student->name }}" 
                                                         class="rounded-circle" 
                                                         width="35" height="35">
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $student->name }}</div>
                                                    <small class="text-muted">{{ $section->name }}</small>
                                                </td>
                                                <td>{{ $student->registration_no ?? 'N/A' }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>
                                                    @if(!$this->isLibraryMember($student->id))
                                                        <a href="{{ route('library.member.create', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                                           wire:navigate
                                                           class="btn btn-sm btn-success">
                                                            <i class="fas fa-plus me-1"></i>Add to Library
                                                        </a>
                                                    @else
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('library.member.view', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                                               wire:navigate
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('library.member.edit', ['student' => $student->id, 'class' => $selectedClassId]) }}" 
                                                               wire:navigate
                                                               class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button wire:click="deleteMember({{ $student->id }})" 
                                                                    class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Are you sure you want to remove this library member?\n\nThis action cannot be undone and the student\'s library membership will be permanently removed.')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($selectedClassId > 0)
        <!-- No Students Found -->
        <div class="card bgs-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Students Found</h5>
                <p class="text-muted">No students found for the selected class or search criteria.</p>
            </div>
        </div>
    @else
        <!-- Select Class First -->
        <div class="card bgs-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-school fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Select a Class</h5>
                <p class="text-muted">Please select a class to view library members.</p>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to remove this library member?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>This action cannot be undone.</strong> The student's library membership will be permanently removed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" wire:click="confirmDelete" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-trash me-1"></i>Yes, Remove Member
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: 1px solid transparent;
        border-bottom: 1px solid #dee2e6;
    }
    
    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        border-top: 3px solid #007bff;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        let studentToDelete = null;
        
        Livewire.on('confirmDelete', (data) => {
            studentToDelete = data.studentId;
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        });
        
        window.addEventListener('confirmDelete', () => {
            if (studentToDelete) {
                @this.deleteMember(studentToDelete);
                studentToDelete = null;
            }
        });
    });
</script>
@endpush
