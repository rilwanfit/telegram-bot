<?php

declare(strict_types=1);


namespace App\Domain\Message;

final readonly class Message
{
    public function __construct(
        public string $chatId,
        public string $text,
        public array $keyboard,
    ) {
    }
}
