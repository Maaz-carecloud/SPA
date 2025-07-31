@props(['id','title','action','is_edit','is_not_crud'=>false])
    <!-- Modal -->
    <div class="modal fade modal-right-bottom" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" role="document" wire:ignore.self>
        <div class="modal-dialog-right modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    <button @click="$dispatch('{{ $action }}-close')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    @if ($is_edit)
                        <button @click="$dispatch('{{ $action }}-close')" type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal">Close</button>
                        <button @click="$dispatch('{{ $action }}')" type="button" class="btn theme-filled-btn">UPDATE</button>
                    @elseif ($is_not_crud)
                        <button type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal">Close</button>
                    @else
                        <button @click="$dispatch('{{ $action }}-close')" type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal">Close</button>
                        <button @click="$dispatch('{{ $action }}')" type="button" class="btn theme-filled-btn">CREATE</button>
                    @endif
                </div>
            </div>
        </div>
    </div>