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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @method static string getMessage(int $code)
 */
#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Success")
     */
    public const SUCCESS = 1;

    /**
     * @Message("Unknow Error")
     */
    public const ERROR = 0;

    /**
     * @Message("Server Error")
     */
    public const SERVER_ERROR = 500;

    /**
     * @Message("Unprocessable Entity")
     */
    public const UNPROCESSABL_ENTITY = 422;

    /**
     * @Message("Invalid authorization")
     */
    public const INVALID_AUTHORIZATION = 401;
}
