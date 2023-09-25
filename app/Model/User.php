<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $company_num
 * @property string $username
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['company_num', 'username'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 我创建的活动
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'user_id', 'id');
    }

    /**
     * 我参与的活动
     * @return BelongsToMany
     */
    public function activityUsers(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'fwh_activity_user', 'user_id', 'activity_id');
    }

    /**
     * 我打分的记录
     *
     */
    public function activityUserScoreLogs(): HasMany
    {
        return $this->hasMany(ActivityUserScoreLog::class, 'user_id', 'id');
    }


}
