<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @return void
     */
    
    public function deleteImage()
    {
    	Storage::delete($this->image);
    }
}
