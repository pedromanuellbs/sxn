<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Message;
use App\Models\TelegramUser;

class SyncTelegramMessages extends Command
{
    protected $signature = 'telegram:sync';
    protected $description = 'Sync messages from Telegram bot to local database';

    public function handle()
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/getUpdates";

        $response = Http::withOptions([
            'verify' => storage_path('certs/cacert.pem'),
        ])->get('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/getUpdates');

        $data = $response->json();
        $new = 0;

        foreach ($data['result'] ?? [] as $update) {
            if (!isset($update['message'])) {
                continue;
            }

            $msg = $update['message'];
            $chatId = $msg['chat']['id'];
            $firstName = $msg['from']['first_name'] ?? '';
            $lastName = $msg['from']['last_name'] ?? '';
            $username = $msg['from']['username'] ?? null;
            $text = $msg['text'] ?? '';

         
            TelegramUser::updateOrCreate(
                ['chat_id' => $chatId],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                ],
            );

           
            $exists = Message::where('chat_id', $chatId)
                ->where('text', $text)
                ->where('from', 'telegram ' . $firstName)
                ->exists();

            if (!$exists) {
                Message::create([
                    'chat_id' => $chatId,
                    'from' => 'telegram ' . $firstName,
                    'to' => 'web', 
                    'text' => $text,
                ]);
                $new++;
            }
        }

        $this->info("Synced {$new} new message(s).");
    }
}
