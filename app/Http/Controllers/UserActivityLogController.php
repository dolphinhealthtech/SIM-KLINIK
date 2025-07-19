<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityLogController extends Controller
{
     public function index(Request $request)
    {
        $logs = UserActivityLog::with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.logs', compact('logs'));
    }
}
