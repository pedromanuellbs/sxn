<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    public function index(Request $request)
    {
        $chatId = $request->query('chat_id'); // from URL ?chat_id=...

        $chatList = TelegramUser::orderBy('first_name')->get();

        // Default to first user if not selected
        if (!$chatId && $chatList->isNotEmpty()) {
            $chatId = $chatList->first()->chat_id;
        }

        $messages = Message::where('chat_id', $chatId)->orderBy('created_at')->get();

        return view('chat', compact('messages', 'chatList', 'chatId'));
    }

    public function sendMessage(Request $request)
    {
        $text = $request->input('message');
        $chatId = $request->input('chat_id');

    
        if (!$text) {
            return back()->withErrors('Message cannot be empty.');
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $response = Http::withOptions([
            'verify' => storage_path('certs/cacert.pem'),
        ])->post($url, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        if (!$response->successful()) {
            Log::error('Telegram sendMessage failed', [
                'response' => $response->body(),
            ]);
        } else {
            Log::info('Berhasil kirim');
        }

        // ✅ Ensure 'text' is included in the Message creation
        Message::create([
            'chat_id' => $chatId,
            'from' => 'admin ' . Auth::user()->name,
            'to' => 'telegram',
            'text' => $text, // <<< THIS MUST EXIST
        ]);

        return redirect()->route('chat.index', ['chat_id' => $chatId]);
    }

    public function getUpdates()
    {
        $messages = Message::orderBy('created_at')->get();

        return response()->json(['messages' => $messages]);
    }
    public function destroy($id)
{
    $message = Message::findOrFail($id);

    // Optional: Only allow deletion by Super Admin (server-side check)
    if (auth()->user()->role?->role_name !== 'Super Admin') {
        abort(403, 'Unauthorized action.');
    }

    $message->delete();

    return redirect()->back()->with('success', 'Message deleted successfully.');
}

public function deleteUser($chat_id)
{
    
    TelegramUser::where('chat_id', $chat_id)->delete();
    Message::where('chat_id', $chat_id)->delete(); // also delete their messages if needed

    return redirect()->back()->with('success', 'User deleted successfully.');
}

}
