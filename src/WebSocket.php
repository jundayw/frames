<?php

namespace Jundayw\Frames;

class WebSocket
{
    private int $fin = 1;
    private int $opcode = 0x1;
    private int $mask = 0;
    private int $payloadLength = 0;
    private array $maskingKey = [];
    private string $payloadData = '';
    private string $payload = '';

    /**
     * @return int
     */
    public function getFin(): int
    {
        return $this->fin;
    }

    /**
     * @return int
     */
    public function getOpcode(): int
    {
        return $this->opcode;
    }

    /**
     * @return int
     */
    public function getMask(): int
    {
        return $this->mask;
    }

    /**
     * @return int
     */
    public function getPayloadLength(): int
    {
        return $this->payloadLength;
    }

    /**
     * @return array
     */
    public function getMaskingKey(): array
    {
        return $this->maskingKey;
    }

    /**
     * 打包
     */
    public function pack($encode, int $opcode = 0x1, bool $finish = true, bool $mask = false): static
    {
        $this->fin           = $finish;
        $this->opcode        = $opcode;
        $this->mask          = $mask;
        $this->payloadData   = $encode;
        $this->payloadLength = strlen($this->payloadData);
        $headers             = [
            ($this->fin ? 0x80 : 0x00) | ($this->opcode & 0x0F),
        ];
        $this->maskingKey    = $this->generateMaskingKey();
        $isMasked            = $this->mask == 0x01;

        if ($this->payloadLength > 65535) {
            $headers[1] = $isMasked ? 255 : 127;
            $headers[2] = ($this->payloadLength >> 56) & 0xFF;
            $headers[3] = ($this->payloadLength >> 48) & 0xFF;
            $headers[4] = ($this->payloadLength >> 40) & 0xFF;
            $headers[5] = ($this->payloadLength >> 32) & 0xFF;
            $headers[6] = ($this->payloadLength >> 24) & 0xFF;
            $headers[7] = ($this->payloadLength >> 16) & 0xFF;
            $headers[8] = ($this->payloadLength >> 8) & 0xFF;
            $headers[9] = $this->payloadLength & 0xFF;
        } elseif ($this->payloadLength > 125) {
            $headers[1] = $isMasked ? 254 : 126;
            $headers[2] = ($this->payloadLength >> 8) & 0xFF;
            $headers[3] = $this->payloadLength & 0xFF;
        } else {
            $headers[1] = $isMasked ? $this->payloadLength + 128 : $this->payloadLength;
        }

        if ($isMasked) {
            $maskedBytes = '';
            for ($i = 0; $i < $this->payloadLength; $i++) {
                $byte        = ord($this->payloadData[$i]);
                $maskedByte  = $byte ^ $this->maskingKey[$i % 4];
                $maskedBytes .= chr($maskedByte);
            }
            $this->payloadData = $maskedBytes;
            $headers           = array_merge($headers, $this->maskingKey);
        }

        $this->payload = pack('C*', ...$headers) . $this->payloadData;

        return $this;
    }

    /**
     * 解包
     */
    public function unpack($decode): static
    {
        $this->payload       = $decode;
        $this->fin           = (ord($this->payload[0]) >> 7) & 0x01;
        $this->opcode        = ord($this->payload[0]) & 0x0F;
        $this->mask          = ((ord($this->payload[1]) >> 7) & 0x01);
        $this->payloadLength = ord($this->payload[1]) & 0x7F;
        $this->maskingKey    = [];
        $isMasked            = $this->mask == 0x01;

        if ($this->payloadLength === 126) {
            $this->payloadLength = unpack('n', substr($this->payload, 2, 2))[1];
            $payloadOffset       = 4;
        } elseif ($this->payloadLength === 127) {
            $this->payloadLength = unpack('J', substr($this->payload, 2, 8))[1];
            $payloadOffset       = 10;
        } else {
            $payloadOffset = 2;
        }

        if ($isMasked) {
            $this->maskingKey = [
                ord($this->payload[$payloadOffset]),
                ord($this->payload[$payloadOffset + 1]),
                ord($this->payload[$payloadOffset + 2]),
                ord($this->payload[$payloadOffset + 3]),
            ];
            $payloadOffset    += 4;
            for ($i = $payloadOffset; $i < $payloadOffset + $this->payloadLength; $i++) {
                $byte              = ord($this->payload[$i]);
                $unmaskedByte      = $byte ^ $this->maskingKey[($i - $payloadOffset) % 4];
                $this->payloadData .= chr($unmaskedByte);
            }
        } else {
            $this->payloadData = substr($this->payload, $payloadOffset, $this->payloadLength);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPayloadData(): string
    {
        return $this->payloadData;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    protected function generateMaskingKey(): array
    {
        $maskingKey = [];
        for ($i = 0; $i < 4; $i++) {
            $maskingKey[] = mt_rand(0, 255);
        }
        return $maskingKey;
    }

}
