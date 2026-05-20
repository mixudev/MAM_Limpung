<?php

namespace App\Services\Announcement;

use App\Models\AnnounceAd;
use App\Models\AnnounceAlert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AnnouncementService
{
    /**
     * Get active items for the frontend with cache enabled.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function getActiveItems(): Collection
    {
        /** @var array<int, array<string, mixed>> $cached */
        $cached = Cache::remember('active_announcements_popups', 300, function () {
            $alerts = AnnounceAlert::active()->orderByDesc('id')->get()->map(function ($item) {
                $images = [];
                if (is_array($item->image)) {
                    foreach ($item->image as $path) {
                        $images[] = asset('storage/'.$path);
                    }
                }

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->content,
                    'type' => 'popup_alert',
                    'images' => $images,
                    'action_url' => $item->action_url,
                    'action_text' => $item->action_text,
                    'popup_size' => $item->popup_size,
                    'display_frequency' => $item->display_frequency,
                    'target_page' => $item->target_page,
                ];
            });

            $ads = AnnounceAd::active()->get()->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'type' => 'banner_ads',
                'image' => $item->image ? asset('storage/'.$item->image) : null,
                'action_url' => $item->action_url,
                'action_text' => $item->action_text,
            ]);

            return $alerts->concat($ads)->all();
        });

        return collect($cached);
    }
}
