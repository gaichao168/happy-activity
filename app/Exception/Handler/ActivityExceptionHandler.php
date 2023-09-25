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

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Kernel\Http\Response;
use Hyperf\Di\Exception\CircularDependencyException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Validation\ValidationException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ActivityExceptionHandler extends ExceptionHandler
{
    protected Response $response;

    protected LoggerInterface $logger;

    public function __construct(protected ContainerInterface $container)
    {
        $this->response = $container->get(Response::class);
        $this->logger = $container->get(LoggerFactory::class)->get('default');
    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        switch (true) {
            case $throwable instanceof HttpException:
                return $this->response->handleException($throwable);
            case $throwable instanceof CircularDependencyException:
                $this->logger->error($throwable->getMessage());
                return $this->response->fail($throwable->getMessage(), ErrorCode::SERVER_ERROR);
            case $throwable instanceof ValidationException:
                return $this->response->fail($throwable->validator->errors()->first(), ErrorCode::UNPROCESSABL_ENTITY);
        }
        $this->logger->error(format_throwable($throwable));

        return $this->response->fail('Server Error' . $throwable->getMessage(), ErrorCode::SERVER_ERROR);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
