<?php

declare(strict_types=1);

namespace App\Application;
use App\Domain\Chat\Chat;
use App\Domain\MessageSenderInterface;

final class ChatService
{
    public function __construct(
        private MessageSenderInterface $messageSender,
    ) {
    }

    public function handleUserMessage(string $chatId, string $userMessage): void
    {
        // Create the Chat aggregate
        $chat = new Chat($chatId, $this->messageSender);

        // Determine the response based on the user message
        if ($userMessage === '/start') {
            $welcomeMessage = "Welcome to The Symfony Mentor! I help developers master Symfony through personalized tutoring and hands-on projects. What would you like to know more about?\n\n";
            $welcomeMessage .= "1️⃣ *Learn Symfony Basics*\n" .
                "2️⃣ *Advanced Symfony Features*\n" .
                "4️⃣ *Career Coaching for Developers*";
            $keyboard = [
                [
                    ['text' => '1️⃣ I want to book a session.', 'callback_data' => 'option_1'],
                    ['text' => '2️⃣ How much do your lessons cost?', 'callback_data' => 'option_2'],
                ]
            ];
            $chat->sendMessage($welcomeMessage, $keyboard);
        } else {
            // Handle other messages
            $chat->sendMessage($userMessage);
        }
    }
}
