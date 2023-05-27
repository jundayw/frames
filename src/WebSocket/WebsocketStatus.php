<?php

namespace Jundayw\Frames\WebSocket;

// WEBSOCKET_STATUS_CONNECTION	1	连接进入等待握手
// WEBSOCKET_STATUS_HANDSHAKE	2	正在握手
// WEBSOCKET_STATUS_ACTIVE	3	已握手成功等待浏览器发送数据帧
// WEBSOCKET_STATUS_CLOSING	4	连接正在进行关闭握手，即将关闭

enum WebsocketStatus: int
{
    case WEBSOCKET_STATUS_CONNECTION = 1;
    case WEBSOCKET_STATUS_HANDSHAKE = 2;
    case WEBSOCKET_STATUS_ACTIVE = 3;
    case WEBSOCKET_STATUS_CLOSING = 4;

    public function message(string $message = ''): string
    {
        return match ($this) {
            self::WEBSOCKET_STATUS_CONNECTION => 'connection',
            self::WEBSOCKET_STATUS_HANDSHAKE => 'handshake',
            self::WEBSOCKET_STATUS_ACTIVE => 'active',
            self::WEBSOCKET_STATUS_CLOSING => 'closing',
        };
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

}
