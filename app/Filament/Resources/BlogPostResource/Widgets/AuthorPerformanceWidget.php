<?php

namespace App\Filament\Resources\BlogPostResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuthorPerformanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil hanya user dengan role 'editor' atau yang memiliki artikel
        $editors = User::whereHas('posts') // Hanya user yang punya artikel
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(4)
            ->get();

        if ($editors->isEmpty()) {
            return [
                Stat::make('Belum Ada Editor', '-')
                    ->description('Tidak ada penulis dengan artikel')
                    ->color('gray')
                    ->icon('heroicon-o-user-group'),
            ];
        }

        $stats = [];
        foreach ($editors as $editor) {
            $stats[] = Stat::make(
                $this->getEditorName($editor),
                $editor->posts_count
            )
                ->description($this->getDescription($editor->posts_count))
                ->color($this->getAuthorColor($editor->posts_count))
                ->icon('heroicon-o-user-circle')
                ->chart($this->getPerformanceChart($editor->posts_count));
        }

        return $stats;
    }

    protected function getColumns(): int
    {
        return 4;
    }

    // Helper methods
    private function getEditorName(User $editor): string
    {
        // Ambil nama pertama atau username
        $name = $editor->name ?? $editor->username ?? 'Editor';
        
        // Jika nama panjang, singkatkan
        if (strlen($name) > 12) {
            $parts = explode(' ', $name);
            return $parts[0] . (isset($parts[1]) ? ' ' . substr($parts[1], 0, 1) . '.' : '');
        }
        
        return $name;
    }

    private function getAuthorColor(int $count): string
    {
        return match(true) {
            $count > 20 => 'danger',    // Sangat aktif
            $count > 10 => 'warning',   // Aktif
            $count > 5  => 'success',   // Cukup aktif
            $count > 0  => 'primary',   // Baru mulai
            default => 'gray',          // Tidak aktif
        };
    }

    private function getPerformanceChart(int $postCount): array
    {
        // Generate dummy chart data based on performance
        return match(true) {
            $postCount > 15 => [5, 10, 15, 20, 25, 30, $postCount], // Rising star
            $postCount > 10 => [3, 6, 9, 12, 15, 18, $postCount],   // Consistent
            $postCount > 5  => [1, 2, 3, 4, 5, 6, $postCount],      // Growing
            $postCount > 0  => [0, 1, 2, 3, 4, 5, $postCount],      // Starting
            default => [0, 0, 0, 0, 0, 0, 0],                      // No activity
        };
    }
}