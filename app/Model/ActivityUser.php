<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property-read Activity|null $activity 
 * @property-read User|null $user 
 * @property-read \Hyperf\Database\Model\Collection|ActivityUserScore[]|null $activityUserScores 
 */
class ActivityUser extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'activity_user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['activity_id', 'user_id'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'activity_id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

   //活动上场分数记录
    public function activityUserScores(): HasMany
    {
        return $this->hasMany(ActivityUserScore::class, 'activity_user_id', 'id');
    }

}
