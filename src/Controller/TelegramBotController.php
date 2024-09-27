<?php

namespace App\Controller;

use App\Application\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TelegramBotController extends AbstractController
{
    public function __construct(
        private ChatService $chatService,
    ) {
    }

    #[Route('/webhook', name: 'app_telegram_webhook', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        // Check for incoming messages or callback queries and delegate to the ChatService
        if (isset($data['message'])) {
            $chatId = $data['message']['chat']['id'];
            $userMessage = $data['message']['text'];

            // Delegate handling the user message
            $this->chatService->handleUserMessage($chatId, $userMessage);
        }
        // Check if the request contains a callback query
        if (isset($data['callback_query'])) {
            $callbackQuery = $data['callback_query'];
            // Get the callback data and chat ID
            $callbackData = $callbackQuery['data']; // e.g., 'option_1', 'option_2'
            $chatId = $callbackQuery['message']['chat']['id'];

            // Handle the user's choice based on the callback data
            switch ($callbackData) {
                case 'option_1':
                    $responseMessage = "Great! Please choose your preferred session type:\n\n";
                    $responseMessage .= "1️⃣ Symfony Beginner (1 hour)\n" .
                        "2️⃣ Real-World Symfony Projects (2 hours)\n\n" .
                        "I found these available slots: [Dates/Times]. Please select one to confirm your booking.\n";
                    break;
                case 'option_2':
                    $responseMessage = "I offer different packages depending on your needs. Here’s a quick overview:\n\n";
                    $responseMessage .= "💡 1-Hour Introductory Lesson: €50\n" .
                        "💼 Career Coaching Package: €200 (4 sessions)\n\n";
                    break;
                default:
                    $responseMessage = "Unknown option!";
            }
            $this->chatService->handleUserMessage($chatId, $responseMessage);
        }

        return new Response('OK');
    }

    public function offerConsultation(mixed $chatId): void
    {
        $message = "🤝 **Free 15-Minute Consultation!**\n\n" .
            "Hello! 🎉 I’m excited to help you with your Symfony learning journey. " .
            "If you’re interested in discussing your learning goals and how I can assist you, " .
            "I invite you to schedule a free 15-minute consultation.\n\n" .
            "Please feel free to reach out to me directly on my personal Telegram account: " .
            "[Your Telegram Username](tg://resolve?domain=yourusername).\n\n" .
            "Looking forward to connecting with you! 😊";
    }
}
