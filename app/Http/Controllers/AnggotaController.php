<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsItem;
use App\Models\PublicDocument;
use App\Models\ActivityLog;
use App\Models\OrgChart;

class AnggotaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'news' => NewsItem::count(),
            'documents' => PublicDocument::count(),
            'total_views' => NewsItem::sum('views'),
            'my_activities' => ActivityLog::where('user_id', $user->id)->count(),
        ];

        $latestNews = NewsItem::orderBy('created_at', 'desc')->take(5)->get();
        $latestDocs = PublicDocument::orderBy('created_at', 'desc')->take(5)->get();
        $recentActivities = ActivityLog::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();
        $orgNodes = OrgChart::whereNull('parent_id')->with('children.children')->orderBy('order_no', 'asc')->get();

        return view('anggota.dashboard', compact('stats', 'latestNews', 'latestDocs', 'recentActivities', 'orgNodes'));
    }
}
