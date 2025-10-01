<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HistoryLog::with('user')->orderBy('created_at','desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by table
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        // Data tambahan untuk filter dropdown
        $users  = User::orderBy('name')->get();
        $tables = HistoryLog::select('table_name')->distinct()->pluck('table_name');
        $actions= HistoryLog::select('action')->distinct()->pluck('action');

        return view('admin.history_logs.index', compact('logs','users','tables','actions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoryLog $historyLog)
    {
        return view('admin.history_logs.show', compact('historyLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoryLog $historyLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoryLog $historyLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoryLog $historyLog)
    {
        //
    }
}
