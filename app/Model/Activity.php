<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $user_id 创建人
 * @property string $name 活动名称
 * @property int $status 活动状态 0:未开始 1:进行中 2:已结束
 * @property int $user_num 参与人数
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Activity extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'activity';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['user_id', 'name', 'status', 'user_num'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'status' => 'integer', 'user_num' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    //活动创建人
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //活动用户
    public function activityUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user', 'activity_id', 'user_id')->withPivot('id')->withTimestamps();
    }
    //活动上场用户
    public function activityScoreUsers(): BelongsToMany
    {
        return $this->belongsToMany(ActivityUser::class, 'activity_user_score', 'activity_id', 'activity_user_id')
            ->withPivot('total_score','id')->withTimestamps();
    }

    //活动上场分数记录
    public function activityUserScores(): HasMany
    {
        return $this->hasMany(ActivityUserScore::class, 'activity_id', 'id');
    }

}
