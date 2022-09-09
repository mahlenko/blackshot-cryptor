<?php


namespace Anita\Traits;


use Illuminate\Support\Facades\URL;

trait Meta
{
    function meta()
    {
        return $this->morphOne(\App\Models\Meta::class, 'object')
            ->withDefault();
    }

    /**
     * @return string
     */
    function heading_h1(): string
    {
        if ($this->meta && $this->meta->translateOrDefault(app()->getLocale())->heading_h1) {
            return $this->meta->translateOrDefault(app()->getLocale())->heading_h1;
        }

        return $this->translateOrDefault(app()->getLocale())->name ?? '';
    }

    /**
     * @return void
     */
    function url()
    {
        return $this->meta->redirect ?: URL::route('view', $this->meta->url);
    }
}
