<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer', 'subject')->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $activities = $query->paginate(50);

        return view('logs.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');
        return view('logs.show', compact('activity'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'dias' => 'required|integer|min:1|max:365'
        ]);

        $dataLimite = now()->subDays($request->dias);
        $deletedCount = Activity::where('created_at', '<', $dataLimite)->delete();

        return redirect()->route('logs.index')
            ->with('success', "Logs anteriores a {$request->dias} dias foram removidos. Total: {$deletedCount}");
    }
}
