<?php

namespace App\Models\Widget;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     *
     */
    public function getParams()
    {
        return (new $this->type)->options();
    }

    /**
     * @return mixed
     */
    public function result()
    {
        return (new $this->type)->result((array) $this->parameters, $this->uuid);
    }

    /**
     * Раскодировать параметры при обращении к ним
     * @return mixed
     */
    public function getParametersAttribute()
    {
        return !empty($this->attributes['parameters'])
            ? json_decode($this->attributes['parameters'])
            : null;
    }

    /**
     * Закодирует параметры в json
     * @param array $parameters
     */
    public function setParametersAttribute(array $parameters = [])
    {
        $this->attributes['parameters'] = json_encode($parameters);
    }

    /**
     * Путь к файлу шаблона
     * @return string
     */
    public function templatePath(): string
    {
        return 'web.components.widget.'. $this->type::templateFolder() .'.'. $this->template ;
    }
}
