<?php

namespace App\Http\Controllers\Dashboard\Announcement;

use App\Http\Controllers\Controller;
use App\Models\AnnounceAd;
use App\Models\AnnounceAlert;
use App\Models\AnnounceText;
use Illuminate\View\View;

class AnnouncementMainController extends Controller
{
    /**
     * Display the announcements aggregator manager dashboard.
     */
    public function index(): View
    {
        $runningTexts = AnnounceText::orderBy('created_at', 'desc')->get();
        $popupAlerts = AnnounceAlert::orderBy('created_at', 'desc')->get();
        $bannerAds = AnnounceAd::orderBy('created_at', 'desc')->get();

        return view('dashboard.admin.announcement.index', compact('runningTexts', 'popupAlerts', 'bannerAds'));
    }
}
