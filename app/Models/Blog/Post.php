<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Blog\Tag;

class Post extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'featured_image',
        'content',
        'meta_title',
        'meta_description',
        'published_at',
        'author_id',
        'category_id'
    ];

    public function published(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

    public function draft(Builder $query)
    {
        return $query->whereNull('published_at');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(Tag::class, 'blog', 'blog_post_tags', null, 'tag_id');
    }
}
