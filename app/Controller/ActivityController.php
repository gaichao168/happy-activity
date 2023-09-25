<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Activity;
use App\Model\ActivityUserScore;
use App\Model\ActivityUserScoreLog;
use App\Model\User;
use App\Request\CreateActivityRequest;
use App\Request\CreateActivityUserRequest;
use App\Request\CreateScoreUserRequest;
use App\Request\CreateUserScoreRequest;
use App\Service\ActivityService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class ActivityController extends AbstractController
{
    #[Inject]
    protected ActivityService $activityService;

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $activities = Activity::orderByDesc('id')->paginate(10);
        return $this->response->success([
            'list' => $activities->items(),
            'total' => $activities->total(),
            'page' => $activities->currentPage(),
        ]);
    }

    public function create(CreateActivityRequest $request)
    {
        $companyNum = $request->getHeaderLine('company-num');
        $user = User::where('company_num', $companyNum)->first();

        $activity = $user->activities()->create([
            'name' => $request->input('name'),
            'user_num' => 0,
        ]);

        return $this->response->success($activity->toArray());
    }

    //创建活动参与人员
    public function createUsers(CreateActivityUserRequest $request)
    {
        $activity = Activity::find($request->input('activity_id'));

        $user = User::firstOrCreate([
            'company_num' => $request->input('company_num')
        ], [
            'username' => $request->input('username')
        ]);

        if ($activity->activityUsers()->where('user_id', $user->id)->exists()) {
            return $this->response->fail('请勿重复参与');
        }
        $activity->activityUsers()->attach($user->id);
        $activity->increment('user_num');
        return $this->response->success($activity->toArray());
    }

    //活动参与人员列表
    public function userList(int $id)
    {
        return $this->response->success($this->activityService->getActivityUsers($id));
    }

    //创建活动上场人员
    public function scoreUser(CreateScoreUserRequest $request)
    {
        $activity = Activity::find($request->input('activity_id'));

        $user = $activity->activityUsers()->where('company_num', $request->input('company_num'))->first();
        if (!$user) {
            return $this->response->fail('用户不存在');
        }

        $activity->activityUserScores()->create([
            'activity_user_id' => $user->pivot->id,
        ]);
        return $this->response->success($activity->toArray());
    }

    //活动上场人员列表

    /**
     * @throws \Exception
     */
    public function userScoreList(int $id)
    {
        return $this->response->success($this->activityService->getScoreUsers($id));
    }

    //给上场人员打分
    public function userScore(CreateUserScoreRequest $request)
    {
        //判断用户是否参与活动
        $companyNum = $request->getHeaderLine('company-num');
        $user = User::where('company_num', $companyNum)->first();

        $activityUserScore = ActivityUserScore::with('activity')->find($request->input('activity_user_score_id'));
        $activity = $activityUserScore->activity;

        $activityUser = $activity->activityUsers()->where('user_id', $user->id)->first();
        if (!$activityUser) {
            return $this->response->fail('你没有参与该活动');
        }
        //判断用户是否打过分
        if (ActivityUserScoreLog::where('user_id', $user->id)->where('activity_user_score_id', $activityUserScore->id)->exists()) {
            return $this->response->fail('请勿重复打分');
        }

        $activityUserScore->activityUserScoreLogs()->create([
            'user_id' => $user->id,
            'score' => $request->input('score')
        ]);
        $activityUserScore->increment('total_score', $request->input('score'));
        return $this->response->success($activityUserScore->toArray());
    }
}
