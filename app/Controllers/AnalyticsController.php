<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Click;

final class AnalyticsController extends Controller
{
    public function index(): void
    {
        $uid = (int) Auth::id();

        $this->view('analytics/index', [
            'title'       => 'Analytics',
            'totalClicks' => Click::totalClicks($uid),
            'totalViews'  => Click::totalViews($uid),
            'perDay'      => Click::clicksPerDay($uid, 14),
            'topLinks'    => Click::topLinks($uid, 5),
        ]);
    }
}
