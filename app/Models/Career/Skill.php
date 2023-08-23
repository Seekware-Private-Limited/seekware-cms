<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Skill extends Model
{
    use HasFactory, HasSlug;

    /**
     * @var string
     */
    protected $table = 'career_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return $this->slug && $this->slug === '' ? $this->slug : SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'career', 'career_post_skills')->count();
    }

    public function totalPost()
    {
        return $this->morphedByMany(Post::class, 'career', 'career_post_skills', null, 'career_id')->count();
    }
}
