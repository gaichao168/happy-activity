<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use App\Request\CreateUserRequest;
use Hyperf\HttpServer\Contract\RequestInterface;

class UserController extends AbstractController
{
    public function index(RequestInterface $request)
    {
        $users = User::paginate(10);
        return $this->response->success([
            'list' => $users->items(),
            'total' => $users->total(),
            'page' => $users->currentPage(),
        ]);
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return $this->response->success($user->toArray());
    }

    public function create(CreateUserRequest $request)
    {
        $user = User::firstOrCreate($request->inputs(['company_num', 'username']));
        return $this->response->success($user->toArray());
    }
}
