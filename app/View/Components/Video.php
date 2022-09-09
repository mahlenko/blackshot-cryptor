<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Video extends Component
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $video = \App\Models\Video::find($this->uuid);
        if (!$video) return '';

        return view('web.components.video', ['video' => $video]);
    }
}
