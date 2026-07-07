<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $authTables = $this->getTables('auth');
        $contentTables = $this->getTables('content');

        return view('admin.dashboard', [
            'admin' => Auth::guard('admin')->user(),
            'stats' => [
                [
                    'label' => 'Admin Accounts',
                    'value' => Admin::query()->count(),
                    'meta' => 'Secured access profiles',
                ],
                [
                    'label' => 'Auth Database Tables',
                    'value' => count($authTables),
                    'meta' => config('database.connections.auth.database'),
                ],
                [
                    'label' => 'Content Database Tables',
                    'value' => count($contentTables),
                    'meta' => config('database.connections.content.database'),
                ],
                [
                    'label' => 'System Status',
                    'value' => $contentTables !== [] || $authTables !== [] ? 'Live' : 'Pending',
                    'meta' => 'Databases connected and dashboard ready',
                ],
            ],
            'authTables' => $authTables,
            'contentTables' => $contentTables,
            'recentAdmins' => Admin::query()
                ->latest('updated_at')
                ->limit(4)
                ->get(),
        ]);
    }

    /**
     * @return list<string>
     */
    private function getTables(string $connection): array
    {
        try {
            $rows = DB::connection($connection)->select('SHOW TABLES');

            return collect($rows)
                ->map(static fn (object $row): string => (string) array_values((array) $row)[0])
                ->values()
                ->all();
        } catch (Throwable) {
            return [];
        }
    }
}
