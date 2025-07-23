<x-data-table 
    title="Students"
    :create-route="route('add-student')"
    create-button-text="CREATE NEW STUDENT"
    :headers="['#', 'Student ID', 'Student Name', 'Parent Name', 'Class', 'Section', 'Address', 'Siblings', 'Status', 'Actions']"
    :sortable-headers="['id', null, 'name', null, null, null, 'address', null, 'is_active', 'created_at']"
    :items="$students"
    table-id="students-table"
    search-placeholder="Search students..."
    :show-export="true"
>
    @forelse($students as $student)
        <tr>
            <td>{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
            <td>
                <span class="badge bg-primary">{{ $student->student?->roll_no ?? 'N/A' }}</span>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title bg-light text-primary rounded-circle">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('view-student', ['id' => $student->id]) }}" class="text-primary text-decoration-none">
                            <h6 class="mb-0">{{ $student->name }}</h6>
                        </a>
                        <small class="text-muted">{{ $student->email ?? '' }}</small>
                    </div>
                </div>
            </td>
            <td>
                @if($student->student?->parent?->user)
                    <a href="{{ route('view-parent', ['id' => $student->student->parent->id]) }}" class="text-primary text-decoration-none">
                        <span class="badge bg-info">{{ $student->student->parent->user->name }}</span>
                    </a>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($student->student?->class)
                    <span class="badge bg-secondary">{{ $student->student->class->name }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($student->student?->section)
                    <span class="badge bg-secondary">{{ $student->student->section->name }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <small>{{ $student->address ?? '-' }}</small>
            </td>
            <td>
                @if($student->student?->parent?->students)
                    @php $siblings = $student->student->parent->students->where('user_id', '!=', $student->id); @endphp
                    @if($siblings->count())
                        @foreach($siblings as $sibling)
                            <a href="{{ route('view-student', ['id' => $sibling->user->id]) }}" class="badge bg-warning text-dark text-decoration-none me-1">{{ $sibling->user->name ?? '-' }}</a>
                        @endforeach
                    @else
                        <span class="text-muted">None</span>
                    @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($student->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a wire:navigate href="{{ route('edit-student', ['id' => $student->id]) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button wire:confirm="Are you sure you want to delete this student?" wire:click="deleteStudent({{ $student->id }})" class="btn btn-sm btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-user-graduate fa-3x mb-3"></i>
                    <h5>No students found</h5>
                    <p>Try adjusting your search criteria or create a new student.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>
