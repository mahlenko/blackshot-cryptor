<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaTranslation extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['title', 'description', 'keywords', 'heading_h1'];

    /**
     * @param string|null $value
     */
    public function setTitleAttribute(string $value = null)
    {
        $this->attributes['title'] = $value;

        if (empty(trim($value))) {
            $meta = Meta::find($this->meta_uuid);
            if ($meta && $meta->object) {
                $this->attributes['title'] = $meta->object->name;
            }
        }
    }
}
