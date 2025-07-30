<?php

namespace App\Livewire\Activity;

use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    // Remove activities property, DataTable will fetch via AJAX

    public function mount(){
        // No eager loading, DataTable will fetch via AJAX
    }

    #[On('reset-table')]
    public function truncate_table(){
        ActivityLog::truncate();
        $this->dispatch('success', message: 'Table is cleared successfully!');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Activity Logs')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.activity.index');
    }

    // Remove loadActivities, DataTable will fetch via AJAX

    // Server-side DataTable AJAX handler
    public function getDataTableRows()
    {
        $request = request();
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        if ($length == -1) {
            $length = 1000; // Safe upper limit for 'All'
        }
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        if ($search) {
            $query->where('description', 'like', "%$search%")
                  ->orWhere('route', 'like', "%$search%")
                  ->orWhere('ipAddress', 'like', "%$search%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%") ;
                  });
        }

        $total = $query->count();
        $activities = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($activities as $index => $activity) {
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
            $data[] = [
                $start + $index + 1,
                e($activity->description),
                $userCell,
                $methodCell,
                '<div class="text-truncate" style="max-width: 200px;" title="' . e($activity->route ?? 'N/A') . '">' . e($activity->route ?? 'N/A') . '</div>',
                '<span class="badge bg-light text-dark">' . e($activity->ipAddress ?? 'N/A') . '</span>',
                $userAgentCell,
                $timeCell,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
}
