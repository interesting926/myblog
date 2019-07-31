<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    //
    protected $data = ['published_at'];

    public function setTitleAtttribute($value)
    {
        $this->attributes['title'] = $value;

        if(!$this->exists){
            $this ->attributes['slug'] =str_slug($value);
        }
    }
}
