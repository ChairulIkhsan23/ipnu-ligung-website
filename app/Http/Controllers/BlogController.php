<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\ViewTracker;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected ViewTracker $tracker;

    public function __construct(ViewTracker $tracker)
    {
        $this->tracker = $tracker;
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        
        // Track view
        $this->tracker->track($post);
        
        // Get realtime data
        $viewData = $this->tracker->getRealtimeData($post);
        
        return view('blog.show', compact('post', 'viewData'));
    }

    // API endpoint untuk realtime updates
    public function getViewStats($id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $this->tracker->getRealtimeData($post);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    // API untuk live tracking (WebSocket/Pusher alternative)
    public function trackView(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->tracker->track($post);
        
        return response()->json([
            'success' => true,
            'views' => $post->views,
            'recent' => $post->recent_views,
        ]);
    }
}