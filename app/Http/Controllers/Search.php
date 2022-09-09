<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stem\LinguaStemRu;

class Search extends Controller
{
    public function index(Request $request)
    {
        $placeholder = new \App\Models\Page\Page();
        $placeholder->name = 'Поиск по сайту';
        $placeholder->meta->title = 'Поиск по сайту';

        if ($request->has('q') && !empty($request->get('q'))) {
            $stemmer = new LinguaStemRu();

            $query_array = explode(' ', $request->get('q'));
            foreach ($query_array as $index => $word) {
                if (!preg_match('/[a-zа-яё0-9]/ui', $word) || mb_strlen($word) < 3) {
                    unset($query_array[$index]);
                } else {
                    $query_array[$index] = $stemmer->stem_word($word);
                }
            }

            $items = $this->search($query_array);
        }

        return view('web.pages.search', [
            'page' => $placeholder,
            'items' => $items ?? collect(),
            'q' => $request->get('q')
        ]);
    }

    /**
     * @param array $words
     * @return null
     */
    private function search(array $words)
    {
        //
        $pages_uuid = \App\Models\Page\PageTranslation::where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('name', 'LIKE', '%'. $word .'%');
                $q->orWhere('description', 'LIKE', '%'. $word .'%');
                $q->orWhere('body', 'LIKE', '%'. $word .'%');
            }
        })->pluck('page_uuid');

        //
        return $pages_uuid
            ? \App\Models\Page\Page::whereIn('uuid', $pages_uuid)
                ->with(['meta', 'translations'])
                ->paginate()
            : null;
    }
}
