<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $user_id 用户id
 * @property int $activity_user_score_id 活动用户分数id
 * @property int $score 分数
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class ActivityUserScoreLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'activity_user_score_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['user_id', 'activity_user_score_id', 'score'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'activity_user_score_id' => 'integer', 'score' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function activityUserScore(): BelongsTo
    {
        return $this->belongsTo(ActivityUserScore::class, 'activity_user_score_id', 'id');
    }

    //打分人
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
