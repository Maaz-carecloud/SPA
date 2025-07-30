<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Activity Log</h3>
        <button type="button" class="btn theme-filled-btn" title="Table truncation option for admins" @click.prevent="$dispatch('reset-table')">- RESET</button>
    </div>
    @php
        $columns = ['#', 'Description', 'User', 'Method', 'Route', 'IP Address', 'User Agent', 'Time'];
        $ajaxUrl = route('datatable.activities');
    @endphp
    <livewire:data-table :columns="$columns" :ajax-url="$ajaxUrl" table-id="activityTable" :key="microtime(true)" />
</x-sections.default>
