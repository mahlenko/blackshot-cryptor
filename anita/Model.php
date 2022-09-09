<?php


namespace Anita;


class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;
}
