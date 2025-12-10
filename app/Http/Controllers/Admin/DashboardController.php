<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Article;
use App\Models\User;
use App\Models\Media;

class DashboardController extends Controller
{
    /**
     * Admin dashboard'u göster
     */
    public function index()
    {
        $stats = [
            'pages' => Page::count(),
            'articles' => Article::count(),
            'users' => User::count(),
            'media' => Media::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
