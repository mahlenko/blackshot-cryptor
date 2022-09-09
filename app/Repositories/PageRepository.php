<?php

namespace App\Repositories;


use App\Helpers\Nested;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Meta;
use App\Models\Page\Page;
use DomainException;

class PageRepository
{

    /**
     * @param string $uuid
     * @param string $locale
     * @param array $pageData
     * @param array $nested
     * @param array $meta
     * @param array $videos
     * @return Page
     */
    public function store(
        string $uuid,
        string $locale,
        array $pageData,
        array $nested,
        array $meta,
        array $videos
    ): Page {
        $page = Page::find($uuid);
        if (!$page) {
            $page = new Page();
            $page->uuid = $uuid;
        }

        $meta['object_type'] = Page::class;
        $meta['is_active'] = !empty($meta['is_active']);
        $meta['show_nested'] = !empty($meta['show_nested']);
        $meta['title'] = $meta['title'] ?? $pageData['name'];

        $page->setDefaultLocale($locale)->fill($pageData);
        $page->meta->setDefaultLocale($locale)->fill($meta);

        if ($nested['position'] != 'append' && $nested['position'] != 'root') {
            $home = Page::where('_lft', 1)->first();
            if ($home->uuid == $nested['parent_id']) {
                $nested['position'] = 'append';
            }
        }

        /* Nested sets */
        Nested::model(Page::class)
            ->tree($page, $nested['position'], $nested['parent_id']);

        $page->push();

        if ($videos) {
            foreach ($videos as $video_uuid => $data) {
                $video_item = (new VideoRepository())
                    ->store($video_uuid, $locale, $data, $page->uuid);
            }
        }

        return $page;
    }

}
