<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advantage extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'service_advantages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'service_post_id',
        'description',
        'image',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
