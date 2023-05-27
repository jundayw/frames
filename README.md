# 环境要求

- `PHP` 8.1 或更高版本

# 使用方法

- 命令行下, 执行 `composer` 命令安装:

```shell
composer require jundayw/frames
```

# 接口

## WebSocketInterface

```php
public function getFin(): int;
public function getOpcode(): int;
public function getMask(): int;
public function getPayloadLength(): int;
public function getMaskingKey(): array;
public function getPayloadData(): string;
public function getPayload(): string;
public function unpack($decode): static;
public function pack($encode, int $opcode = 0x1, bool $finish = true, bool $mask = false): static;
```

## 客户端打包解包

- 客户端打包数据发送给服务端（Client-pack）：

```php
$client = new WebSocket();
// Client pack Server mask true
$client->pack('data', 0x1, true, true);
```

- 客户端解包服务端发送的数据（Client-unpack）：

```php
$client = new WebSocket();
$client->unpack('817e00d8e4bb8ee69c8de58aa1e599a8e58f91e5be80e5aea2e688b7e7abafe79a84e695b0e68daee4b99fe698afe5908ce6a0b7e79a84e695b0e68daee5b8a7efbc8ce4bd86e698afe4bb8ee69c8de58aa1e599a8e58f91e98081e588b0e5aea2e688b7e7abafe79a84e695b0e68daee5b8a7e4b88de99c80e8a681e68ea9e7a081e79a84e38082e68891e4bbace887aae5b7b1e99c80e8a681e58ebbe7949fe68890e695b0e68daee5b8a7efbc8ce8a7a3e69e90e695b0e68daee5b8a7e79a84e697b6e58099e68891e4bbace99c80e8a681e58886e78987e38082');
```

## 服务端打包解包

- 服务端打包数据发送给客户端（Server-pack）：

```php
$client = new WebSocket();
// Server pack Client mask false
$client->pack('data', 0x1, true, false);
```

- 服务端解包客户端发送的数据（Server-unpack）：

```php
$client = new WebSocket();
$client->unpack('81fe00d818230807fc9886e184aeed8db9c691affdac99e2a6a3eda9bac580b0ff88a7e082a7ee92a8c585a9fc9a97e1808ced9794c5a8b0ffb98ce18d93ee8ab6c6b0a0f79f84e3a5a5ee9fb7c7b389febf85e29282ed9eb0c68796f1a389e29093eda9bac580b0ff88a7e082a7ee92a8c585a9fd9bafe3a0aee19b98cbae86feada1e0b8a2ef9d9cc08885feab99e3a38fe080b2c6bfb6f1bf88efbea2ed89a3c49c98feab98e18d93ee8ab6c6b0a0f79f84efbf80ee9988c59db7feaea6e2a084ef9d9cc59fb1fda391e190b2ecbcb4ca9487f08589e290a5ef8e9fc08885');
```

## 数据帧类型

| 常量    |  对应值   | 说明    |
|-----|-----|-----|
| WEBSOCKET_OPCODE_TEXT    | 0x1    |  UTF-8 文本字符数据   |
| WEBSOCKET_OPCODE_BINARY   | 0x2    | 二进制数据    |
| WEBSOCKET_OPCODE_CLOSE    | 0x8    |  关闭帧类型数据   |
| WEBSOCKET_OPCODE_PING    | 0x9    |  ping 类型数据   |
| WEBSOCKET_OPCODE_PONG    | 0xa    | pong 类型数据    |


		
		
		
		
		
		