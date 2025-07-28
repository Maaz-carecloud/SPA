<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Activity Log</h3>
        <button type="button" class="btn theme-filled-btn" title="Table truncation option for admins" @click.prevent="$dispatch('reset-table')">- RESET</button>
    </div>
    @php
        $columns = ['#', 'Description', 'User', 'Method', 'Route', 'IP Address', 'User Agent', 'Time'];
        $rows = [];
        if($activities instanceof \Illuminate\Support\Collection || $activities instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            if($activities->count()) {
                foreach($activities as $index => $activity) {
                    // User
                    if ($activity->user) {
                        $badgeClass = 'bg-success';
                        $userName = $activity->user->name;
                    } else {
                        $badgeClass = 'bg-warning';
                        $userName = 'Guest';
                    }
                    $userCell = '<span class="badge ' . $badgeClass . '">' . e($userName) . '</span>';
                    // Method
                    $method = $activity->methodType ?? 'GET';
                    switch (strtolower($method)) {
                        case 'get': $methodClass = 'bg-info'; break;
                        case 'post': $methodClass = 'bg-warning'; break;
                        case 'put': case 'patch': $methodClass = 'bg-primary'; break;
                        case 'delete': $methodClass = 'bg-danger'; break;
                        default: $methodClass = 'bg-secondary'; break;
                    }
                    $methodCell = '<span class="badge ' . $methodClass . '">' . strtoupper($method) . '</span>';
                    // User Agent
                    $userAgentDetails = $activity->userAgentDetails ?? [];
                    $platform = $userAgentDetails['platform'] ?? 'Unknown';
                    $browser = $userAgentDetails['browser'] ?? 'Unknown';
                    switch ($platform) {
                        case 'Windows': $platformIcon = 'fab fa-windows'; break;
                        case 'Macintosh': $platformIcon = 'fab fa-apple'; break;
                        case 'Android': $platformIcon = 'fab fa-android'; break;
                        case 'Linux': $platformIcon = 'fab fa-linux'; break;
                        default: $platformIcon = 'fas fa-desktop'; break;
                    }
                    switch ($browser) {
                        case 'Chrome': $browserIcon = 'fab fa-chrome'; break;
                        case 'Firefox': $browserIcon = 'fab fa-firefox'; break;
                        case 'Safari': $browserIcon = 'fab fa-safari'; break;
                        case 'Edge': $browserIcon = 'fab fa-edge'; break;
                        default: $browserIcon = 'fas fa-globe'; break;
                    }
                    $userAgentCell = '<div class="d-flex align-items-center">'
                        . '<i class="' . $platformIcon . ' me-1"></i>'
                        . '<i class="' . $browserIcon . ' me-1"></i>'
                        . '<span class="small text-muted">' . e($browser) . '</span>'
                        . '</div>';
                    // Time
                    $timeCell = '<div>'
                        . '<span class="fw-bold">' . ($activity->created_at ? $activity->created_at->format('M d, Y') : 'N/A') . '</span><br>'
                        . '<small class="text-muted">' . ($activity->created_at ? $activity->created_at->format('h:i A') : '') . '</small>'
                        . '</div>';
                    $rows[] = [
                        $index + 1,
                        e($activity->description),
                        $userCell,
                        $methodCell,
                        '<div class="text-truncate" style="max-width: 200px;" title="' . e($activity->route ?? 'N/A') . '">' . e($activity->route ?? 'N/A') . '</div>',
                        '<span class="badge bg-light text-dark">' . e($activity->ipAddress ?? 'N/A') . '</span>',
                        $userAgentCell,
                        $timeCell,
                    ];
                }
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="activityTable" :key="microtime(true)" />
</x-sections.default>
