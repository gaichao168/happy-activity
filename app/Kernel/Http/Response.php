<?php

declare(strict_types=1);
/**
 * This file is part of house-post.
 *
 * @link     https://code.addcn.com/tw591ms/house-post
 * @document https://code.addcn.com/tw591ms/house-post
 * @contact  hexiangyu@addcn.com
 * @contact  greezen@addcn.com
 */
namespace App\Kernel\Http;

use App\Constants\ErrorCode;
use Hyperf\Context\Context;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response
{
    protected ResponseInterface $response;

    public function __construct(protected ContainerInterface $container)
    {
        $this->response = $container->get(ResponseInterface::class);
    }

    public function success(array $data = [], string $msg = ''): PsrResponseInterface
    {
        return $this->response->json([
            'status' => ErrorCode::SUCCESS,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    public function fail(string $message, int $code = ErrorCode::ERROR): PsrResponseInterface
    {
        return $this->response->json([
            'status' => $code,
            'msg' => $message,
            'data' => [],
        ]);
    }

    public function redirect(string $url, int $status = 302): PsrResponseInterface
    {
        return $this->response()
            ->withAddedHeader('Location', $url)
            ->withStatus($status);
    }

    public function handleException(HttpException $throwable): PsrResponseInterface
    {
        return $this->response()
            ->withAddedHeader('Server', 'Hyperf')
            ->withStatus($throwable->getStatusCode())
            ->withBody(new SwooleStream($throwable->getMessage()));
    }

    public function response(): PsrResponseInterface
    {
        return Context::get(PsrResponseInterface::class);
    }
}
