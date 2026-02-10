<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\ViewLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ViewTracker
{
    public function track(BlogPost $post): void
    {
        // Cegah duplicate views dari session yang sama dalam 30 menit
        $sessionKey = 'viewed_post_' . $post->id;
        if (Cache::has($sessionKey)) {
            return;
        }

        DB::transaction(function () use ($post) {
            // Update counters
            $post->increment('views');
            $post->increment('views_today');
            $post->increment('views_this_week');
            $post->increment('views_this_month');
            $post->last_viewed_at = now();
            
            // Calculate view velocity (views per hour)
            $hourlyViews = $post->viewLogs()
                ->where('created_at', '>', now()->subHour())
                ->count();
            $post->view_velocity = $hourlyViews;
            
            $post->save();

            // Create view log
            ViewLog::create([
                'blog_post_id' => $post->id,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referrer' => request()->header('referer'),
                'metadata' => [
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'is_ajax' => request()->ajax(),
                ],
            ]);
        });

        // Cache untuk 30 menit
        Cache::put($sessionKey, true, now()->addMinutes(30));
    }

    public function getRealtimeData(BlogPost $post): array
    {
        $now = now();
        
        return [
            'total' => $post->views,
            'today' => $post->views_today,
            'this_week' => $post->views_this_week,
            'this_month' => $post->views_this_month,
            'last_viewed' => $post->last_viewed_at?->diffForHumans(),
            'view_velocity' => $post->view_velocity,
            'recent_5min' => $post->recent_views,
            'hourly' => $post->hourly_views,
            
            // Real-time dari logs (last 1 hour)
            'hourly_breakdown' => $this->getHourlyBreakdown($post),
            
            // Live viewers sekarang (last 2 minutes)
            'live_viewers' => $post->viewLogs()
                ->where('created_at', '>', $now->subMinutes(2))
                ->distinct('session_id')
                ->count('session_id'),
        ];
    }

    private function getHourlyBreakdown(BlogPost $post): array
    {
        $data = [];
        $now = now();
        
        for ($i = 0; $i < 12; $i++) {
            $time = $now->subMinutes($i * 5);
            $count = $post->viewLogs()
                ->whereBetween('created_at', [
                    $time->copy()->subMinutes(5),
                    $time
                ])
                ->count();
            
            $data[] = [
                'time' => $time->format('H:i'),
                'views' => $count,
            ];
        }
        
        return array_reverse($data);
    }
}