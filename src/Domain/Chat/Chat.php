<?php

declare(strict_types=1);

namespace App\Domain\Chat;
use App\Domain\Message\Message;
use App\Domain\MessageSenderInterface;

final readonly class Chat
{
    public function __construct(
        private string $id,
        private MessageSenderInterface $messageSender,
    ) {
    }

    public function getId(): string {
        return $this->id;
    }

    public function sendMessage(string $text, array $keyboard = []): void
    {
        $message = new Message($this->id, $text, $keyboard);
        $this->messageSender->send(
            chatId: $this->id,
            text: $message->text,
            keyboard: $keyboard,
        );
    }
}
