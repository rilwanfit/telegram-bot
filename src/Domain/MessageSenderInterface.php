<?php

namespace App\Domain;

interface MessageSenderInterface
{
    public function send(string $chatId, string $text, array $keyboard = []): void;
}
