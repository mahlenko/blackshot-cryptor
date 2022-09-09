<?php


namespace Anita;


abstract class Resources
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    public $resource;

    /**
     * @var string
     */
    public static $title = 'name';

    /**
     * @var array
     */
    public static $with = [];

    /**
     * Create a new resource instance
     * @param \Illuminate\Database\Eloquent\Model $resource
     */
    public function __construct(\Illuminate\Database\Eloquent\Model $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource group name
     * @return string
     */
    protected static function getGroup(): string
    {
        return 'resources';
    }
}
