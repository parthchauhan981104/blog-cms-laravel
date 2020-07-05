<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name']; //nedd this to be able to use 'create' static method

    public function posts()
    {
        return $this->belongsTomany(Post::class);
    }

}
