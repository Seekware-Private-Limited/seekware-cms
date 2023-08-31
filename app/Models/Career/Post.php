<?php

namespace App\Models\Career;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Career\Skill;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Post extends Model
{
    use HasFactory, LogsActivity;

    /**
     * @var string
     */
    protected $table = 'career_posts';

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
        'description',
        'meta_title',
        'meta_description',
        'published_at',
        'author_id',
        'job_type',
        'exp_level',
        'responsibilities',
        'skill_desc',
        'experience',
        'url',
        'salary'
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function skills(): MorphToMany
    {
        return $this
            ->morphToMany(Skill::class, 'career', 'career_post_skills', null, 'skill_id');
    }
}
