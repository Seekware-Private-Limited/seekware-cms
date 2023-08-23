<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostSkill extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'career_post_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'career_id',
        'skill_id'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'career_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'career_id');
    }
}
