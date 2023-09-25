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
use Carbon\Carbon;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Str;

if (! function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di(?string $id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }

        return $container;
    }
}

if (! function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (! function_exists('is_debug')) {
    /**
     * is debug environment.
     */
    function is_debug(): bool
    {
        return ! in_array(env('APP_ENV'), ['production', 'master']);
    }
}

if (! function_exists('data_only')) {
    /**
     * @param mixed $target
     * @example data_only(User::first(), ['user_id', 'userData.money as money' => 0])
     */
    function data_only($target, array $keys): array
    {
        $result = [];

        foreach ($keys as $key => $default) {
            if (is_int($key)) {
                [$key, $default] = [$default, null];
            }

            if (str_contains($key, ' as ')) {
                [$key, $alias] = explode(' as ', $key, 2);
            } else {
                $alias = $key;
            }

            $result[$alias] = data_get($target, $key, $default);
        }

        return $result;
    }
}

if (! function_exists('get_tw_year')) {
    /**
     * get tw year.
     */
    function get_tw_year(int $year = null): int
    {
        if (is_null($year)) {
            $year = date('Y');
        }

        return $year - 1911;
    }
}

if (! function_exists('tw_to_gmt_year')) {
    /**
     * tw year to gmt year.
     */
    function tw_to_gmt_year(int $year = null): int
    {
        if (is_null($year)) {
            return (int) date('Y');
        }

        return $year + 1911;
    }
}

if (! function_exists('get_tw_date')) {
    /**
     * get tw date.
     * @throws \Carbon\Exceptions\InvalidFormatException
     */
    function get_tw_date(string $date = null): string
    {
        if (is_null($date)) {
            $date = now()->format('Y-m-d');
        }

        return Str::replaceFirst('0', '', Carbon::parse($date)->subYears(1911)->format('Y-m-d'));
    }
}

if (! function_exists('get_user_id')) {
    /**
     * get user id from request header.
     * @throws \TypeError
     */
    function get_user_id(): int
    {
        return (int) (request()->getHeaderLine('ms-user-id') ?? 0);
    }
}

if (! function_exists('get_cub_id')) {
    /**
     * get cub user id from request header.
     * @throws \TypeError
     */
    function get_cub_id(): int
    {
        return (int) (request()->getHeaderLine('ms-cub-id') ?? 0);
    }
}

if (! function_exists('get_admin_id')) {
    /**
     * get admin id from request header.
     * @throws \TypeError
     */
    function get_admin_id(): int
    {
        return (int) (request()->getHeaderLine('ms-admin-id') ?? 0);
    }
}

if (! function_exists('is_admin')) {
    /**
     * is admin user.
     * @throws \TypeError
     */
    function is_admin(): bool
    {
        return get_admin_id() > 0;
    }
}

if (! function_exists('get_device')) {
    /**
     * get device from request header.
     * @throws \TypeError
     */
    function get_device(): string
    {
        return (string) (request()->getHeaderLine('device') ?? 'pc');
    }
}

if (! function_exists('ip')) {
    /**
     * 获取用户访问的ip.
     */
    function ip(): string
    {
        return (string) (request()->getHeaderLine('ms-ip') ?? '');
    }
}

if (! function_exists('kebab_to_snake')) {
    /**
     * convert value from kebab to snake.
     */
    function kebab_to_snake(string $value): string
    {
        return Str::snake(Str::camel($value));
    }
}

if (! function_exists('get_ms_config')) {
    /**
     * get microservice config from config center.
     */
    function get_ms_config(string $microserviceName = null): array
    {
        if (is_null($microserviceName)) {
            $microserviceName = env('APP_NAME');
        }
        return (array) config(kebab_to_snake($microserviceName));
    }
}

if (! function_exists('trim_ubb_tags')) {
    /**
     * 移除 ubb 标签.
     *
     * @param string $Text
     * @return string
     */
    function trim_ubb_tags($Text)
    {
        // UBB代码转换
        $Text = preg_replace('/\r?\n/', '<br />', $Text);

        $Text = preg_replace('/\[br\]/is', '', $Text);
        $Text = stripslashes($Text);
        $Text = preg_replace('/\\t/is', ' ', $Text);
        $Text = preg_replace('/\[url\](http:\/\/.+?)\[\/url\]/is', '\\1', $Text);
        $Text = preg_replace('/\[url\](.+?)\[\/url\]/is', '\\1', $Text);
        $Text = preg_replace('/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is', '\\2', $Text);
        $Text = preg_replace('/\[url=(.+?)\](.+?)\[\/url\]/is', '\\2', $Text);
        $Text = preg_replace('/\[email\](.+?)\[\/email\]/is', '\\1', $Text);
        $Text = preg_replace('/\[i\](.+?)\[\/i\]/is', '\\1', $Text);
        $Text = preg_replace('/\[u\](.+?)\[\/u\]/is', '\\1', $Text);
        $Text = preg_replace('/\[b\](.+?)\[\/b\]/is', '\\1', $Text);
        $Text = preg_replace('/\[fly\](.+?)\[\/fly\]/is', '\\1', $Text);
        $Text = preg_replace('/\[move\](.+?)\[\/move\]/is', '\\1', $Text);

        // $Text=preg_replace("/\[(.+?)=(.+?)\]|\[\/(.+?)\]/is","",$Text);
        return preg_replace('/(\[\w+=[\w#]+\])|(\[\/?\w+\])/', '', $Text); // 修复去掉有用字符的情况
    }
}

if (! function_exists('explode_filter')) {
    function explode_filter(string $separator, string $string, ?callable $callback = null): array
    {
        return array_filter(explode($separator, $string), $callback);
    }
}
