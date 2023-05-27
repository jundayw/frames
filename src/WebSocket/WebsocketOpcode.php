<?php

namespace Jundayw\Frames\WebSocket;

enum WebsocketOpcode: int
{
    case WEBSOCKET_OPCODE_TEXT = 0x1;
    case WEBSOCKET_OPCODE_BINARY = 0x2;
    case WEBSOCKET_OPCODE_CLOSE = 0x8;
    case WEBSOCKET_OPCODE_PING = 0x9;
    case WEBSOCKET_OPCODE_PONG = 0xa;

    public function message(string $message = ''): string
    {
        return match ($this) {
            self::WEBSOCKET_OPCODE_TEXT => 'text',
            self::WEBSOCKET_OPCODE_BINARY => 'binary',
            self::WEBSOCKET_OPCODE_CLOSE => 'close',
            self::WEBSOCKET_OPCODE_PING => 'ping',
            self::WEBSOCKET_OPCODE_PONG => 'pong',
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
