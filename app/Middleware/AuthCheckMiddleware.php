<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Kernel\Http\Response;
use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthCheckMiddleware implements MiddlewareInterface
{

    #[Inject]
    protected Response $response;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $companyNum = $request->getHeaderLine('company-num');
        if (!$companyNum || ! User::where('company_num', $companyNum)->exists()) {
            return $this->response->fail('工号不正确',ErrorCode::INVALID_AUTHORIZATION);
        }
        return $handler->handle($request);
    }
}
