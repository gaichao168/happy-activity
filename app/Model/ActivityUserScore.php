<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $activity_id 活动id
 * @property int $activity_user_id 活动用户
 * @property int $total_score 分数
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class ActivityUserScore extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'activity_user_score';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['activity_id', 'activity_user_id', 'total_score'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'activity_id' => 'integer', 'activity_user_id' => 'integer', 'total_score' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function activityUser(): BelongsTo
    {
        return $this->belongsTo(ActivityUser::class, 'activity_user_id', 'id');
    }

    //打分记录
    public function activityUserScoreLogs(): HasMany
    {
        return $this->hasMany(ActivityUserScoreLog::class, 'activity_user_score_id', 'id');
    }
}
