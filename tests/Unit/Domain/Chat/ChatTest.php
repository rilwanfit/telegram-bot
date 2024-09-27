<?php

declare(strict_types=1);


namespace App\Tests\Unit\Domain\Chat;

use App\Domain\Chat\Chat;
use App\Domain\MessageSenderInterface;
use PHPUnit\Framework\TestCase;

final class ChatTest extends TestCase
{
    /** @test */
    public function send_message(): void
    {
        $mockMessageSender = $this->createMock(MessageSenderInterface::class);
        $mockMessageSender->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('12345'),  // chatId
                $this->equalTo('Hello, World!'), // text
                $this->equalTo([])  // keyboard
            );

        $chat = new Chat('12345', $mockMessageSender);
        $chat->sendMessage('Hello, World!');
    }
}
