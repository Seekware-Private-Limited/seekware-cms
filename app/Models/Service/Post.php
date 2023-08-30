<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Service\Feature;
use App\Models\Service\Advantage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Post extends Model
{
    use HasFactory, LogsActivity;

    /**
     * @var string
     */
    protected $table = 'service_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'featured_image',
        'bg_image',
        'meta_title',
        'meta_description',
        'published_at',
        'service_heading',
        'service_description',
        'service_image',
        'cta_text',
        'stats_title',
        'cta_feature_text',
        'approach_feature_text',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    public function published(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

    public function draft(Builder $query)
    {
        return $query->whereNull('published_at');
    }

    /**
     * Get the services for the service post.
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'service_post_id');
    }

    /**
     * Get the advantages for the service post.
     */
    public function advantages(): HasMany
    {
        return $this->hasMany(Advantage::class, 'service_post_id');
    }
}
