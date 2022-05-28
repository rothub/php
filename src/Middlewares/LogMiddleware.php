<?php

namespace RotHub\PHP\Middlewares;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use RotHub\PHP\Client;
use RotHub\PHP\Middlewares\AbstractMiddleware;

class LogMiddleware extends AbstractMiddleware
{
    /**
     * @inheritdoc
     */
    protected function init(): void
    {
        foreach ($this->config as &$item) {
            $item = array_merge([
                'driver' => 'StreamHandler',
                'params' => [
                    'path' => '',
                    'level' => Logger::DEBUG,
                ],
                'formatter' => [
                    'format' => '[%datetime%] [%channel%] [%level_name%] [%message%] [%context%] [%extra%]' . "\n",
                    'dateFormat' => 'Y-m-d H:i:s',
                ],
            ], $item);
        }
    }

    /**
     * @inheritdoc
     */
    public function build(): callable
    {
        $logger = new Logger(Client::name(), $this->handlers());
        $formatter = new MessageFormatter(MessageFormatter::DEBUG);

        return Middleware::log($logger, $formatter);
    }

    /**
     * 处理.
     *
     * @return array
     */
    protected function handlers(): array
    {
        $handlers = [];

        foreach ($this->config as $driver) {
            $class = '\\Monolog\Handler\\' . $driver['driver'];
            $params = array_values($driver['formatter']);
            $formatter = new LineFormatter(...$params);

            $params = array_values($driver['params']);
            $handler = new $class(...$params);
            $handler->setFormatter($formatter);

            $handlers[] = $handler;
        }

        return $handlers;
    }
}
