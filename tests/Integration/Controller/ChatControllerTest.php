<?php

declare(strict_types=1);


namespace App\Tests\Integration\Controller;

use App\Application\ChatService;
use App\Controller\TelegramBotController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ChatControllerTest extends WebTestCase
{
    public function testWebhookHandlesStartCommand()
    {
        $mockChatService = $this->createMock(ChatService::class);
        $mockChatService->expects($this->once())
            ->method('handleUserMessage')
            ->with('12345', '/start');

        $controller = new TelegramBotController($mockChatService);
        $request = new Request([], [], [], [], [], [], json_encode([
            'message' => [
                'chat' => ['id' => '12345'],
                'text' => '/start'
            ]
        ]));

        $response = $controller->index($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
