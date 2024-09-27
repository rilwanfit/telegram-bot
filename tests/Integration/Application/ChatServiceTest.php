<?php

declare(strict_types=1);


namespace App\Tests\Integration\Application;

use App\Application\ChatService;
use App\Domain\MessageSenderInterface;
use PHPUnit\Framework\TestCase;

final class ChatServiceTest extends TestCase
{
    /** @test  */
    public function handle_user_message_sends_correct_message()
    {
        // Create a mock for the message sender
        $mockMessageSender = $this->createMock(MessageSenderInterface::class);
        $mockMessageSender->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('12345'), // chatId
                $this->stringStartsWith('Welcome to The Symfony Mentor!'), // text
                $this->anything() // keyboard (if you don't care about the specific structure here)
            );

        $chatService = new ChatService($mockMessageSender);
        $chatService->handleUserMessage('12345', '/start');

    }
}
