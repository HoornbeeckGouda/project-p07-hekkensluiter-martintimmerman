<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserLog::with('user');

        // Filter op gebruiker als er een user_id is
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter op actie type
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter op datum (van)
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('log_date', '>=', $request->date_from);
        }

        // Filter op datum (tot)
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('log_date', '<=', $request->date_to);
        }

        // Sorteer op datum (nieuwste eerst)
        $query->orderBy('log_date', 'desc');

        $logs = $query->paginate(20);
        $users = User::all();
        
        // Unieke acties ophalen voor filter
        $actions = UserLog::select('action')->distinct()->pluck('action');

        return view('user-logs.index', compact('logs', 'users', 'actions'));
    }

    public function show(UserLog $log)
    {
        return view('user-logs.show', compact('log'));
    }

    /**
     * Helper methode om logs aan te maken vanuit andere controllers
     */
    public static function createLog($userId, $action, $description, $relatedModel = null, $relatedId = null)
    {
        return UserLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'log_date' => now(),
            'related_model' => $relatedModel,
            'related_id' => $relatedId,
        ]);
    }
}