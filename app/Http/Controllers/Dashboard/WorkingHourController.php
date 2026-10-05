<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkingHoursRequest;
use App\Models\BreakTime;
use App\Models\WorkingHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WorkingHourController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        $workingHours = $tenant->workingHours()->get()->keyBy('day_of_week');
        $breaks = $tenant->breaks()->get()->groupBy('day_of_week');

        return view('dashboard.working-hours.index', compact('workingHours', 'breaks'));
    }

    public function update(UpdateWorkingHoursRequest $request): RedirectResponse
    {
        $tenant = $request->user()->tenant;
        $days = $request->validated()['days'];

        DB::transaction(function () use ($tenant, $days) {
            foreach ($days as $dayOfWeek => $day) {
                $isActive = (bool) ($day['is_active'] ?? false);

                WorkingHour::query()->updateOrCreate(
                    ['tenant_id' => $tenant->id, 'day_of_week' => $dayOfWeek],
                    [
                        'start_time' => $day['start_time'] ?? '09:00',
                        'end_time' => $day['end_time'] ?? '17:00',
                        'is_active' => $isActive,
                    ],
                );

                BreakTime::query()->where('tenant_id', $tenant->id)->where('day_of_week', $dayOfWeek)->delete();

                if ($isActive && ! empty($day['break_start']) && ! empty($day['break_end'])) {
                    BreakTime::query()->create([
                        'tenant_id' => $tenant->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => $day['break_start'],
                        'end_time' => $day['break_end'],
                    ]);
                }
            }
        });

        return redirect()->route('dashboard.working-hours.index')->with('status', 'Активноста на работното време е ажурирана.');
    }
}
