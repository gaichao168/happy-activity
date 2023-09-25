<?php

namespace App\Service;

use App\Model\Activity;
use App\Model\ActivityUser;
use App\Model\ActivityUserScoreLog;
use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use function Swoole\Coroutine\Http\request;

class ActivityService
{
    #[Inject]
    protected RequestInterface $request;
    /**
     * @throws \Exception
     */
    public function getActivityUsers(int $activityId)
    {
        $data = [];
        $activity = Activity::find($activityId);
        if (!$activity) {
            throw new \Exception('活动不存在');
        }
        $users = $activity->activityUsers()->get();

        foreach ($users as $user) {
            $data[] = $this->buildUser($user);
        }
        return $data;
    }

    public function buildUser(User $user): array
    {
        return [
            'id' => $user->id,
            'company_num' => $user->company_num,
            'username' => $user->username,
            'created_at' => $user->pivot->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @throws \Exception
     */
    public function getScoreUsers(int $activityId): array
    {
        $activity = Activity::find($activityId);
        if (!$activity) {
            throw new \Exception('活动不存在');
        }
        $users = $activity->activityScoreUsers()->orderBy('total_score', 'desc')->with('user')->get();
        $data = [];
        foreach ($users as $user) {
            $data[] = $this->buildActivityUser($user);
        }
        return $data;
    }

    public function buildActivityUser(ActivityUser $activityUser)
    {
        //判断用户是否参与活动
        $companyNum = $this->request->getHeaderLine('company-num');

        $user = User::where('company_num', $companyNum)->first();
        return [
            'id' => $activityUser->pivot->id,
            'company_num' => $activityUser->user->company_num,
            'username' => $activityUser->user->username,
            'total_score' => $activityUser->pivot->total_score,
            'created_at' => $activityUser->pivot->created_at->format('Y-m-d H:i:s'),
            'status'=> (int) ActivityUserScoreLog::where('user_id', $user->id)->where('activity_user_score_id', $activityUser->pivot->id)->exists(),
        ];
    }
}