<?php

declare(strict_types=1);

namespace App\Infrastructure\Telegram;
use App\Domain\MessageSenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TelegramMessageSender implements MessageSenderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $token,
        private LoggerInterface $logger,
    )
    {
    }

    public function send(string $chatId, string $text, array $keyboard = []): void
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ];

        if (!empty($keyboard)) {
            $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
        }
        $this->logger->debug("PAYLOAD", $data);
        try {
            $this->httpClient->request('POST', "https://api.telegram.org/bot{$this->token}/sendMessage", [
                'json' => $data
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException("Error sending message: " . $e->getMessage());
        } catch (\Throwable $e) {
            $this->logger->debug($e->getMessage(), $data);
        }
    }
}
