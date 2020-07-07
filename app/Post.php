<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Tag;
use App\User;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = [
        'published_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at', 'category_id', 'user_id'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * check if post has a certain tag
     *
     * @return bool
     */
    public function hasTag($tagId)
    {
        return in_array($tagId, $this->tags->pluck('id')->toArray());
    }

    public function scopePublished($query)
    {
      return $query->where('published_at', '<=', now());
    }

    public function scopeSearched($query) //a chainable method to the queryBuilder
    {
      $search = request()->query('search');

      if (!$search) {
        return $query->published();
      }

      return $query->published()->where('title', 'LIKE', "%{$search}%");
    }

}
