<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use App\Models\Message;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Create labels for all months (January to July)
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

        // Generate dataset by querying messages per month
        $user = Auth::user();
$year = now()->year;

if ($user->role_id == 1) {
    // Super Admin: show all messages
    $dataset1 = collect(range(1, 7))->map(function ($month) use ($year) {
        return Message::whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                      ->count();
    });

    $dataset2 = collect(range(1, 7))->map(function ($month) use ($year) {
        return Message::whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                      ->where('from', 'like', 'admin%')
                      ->count();
    });
} elseif ($user->role_id == 2) {
    // Admin: show only their messages
    $adminFrom = 'admin' . $user->id; // adjust this if your `from` value is different

    $dataset1 = collect(range(1, 7))->map(function ($month) use ($year, $adminFrom) {
        return Message::whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                      ->where('from', $adminFrom)
                      ->count();
    });

    $dataset2 = collect(range(1, 7))->map(function ($month) {
        return 0; // optionally skip second dataset or return nulls/zeroes
    });
}
        // Get all message texts combined into a single string
        $allText = Message::pluck('text')->implode(' ');

        // Normalize: lowercase, remove punctuation, etc.
        $cleanText = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $allText));

        // Split into words
        $wordArray = explode(' ', $cleanText);
        $telegramUsers = TelegramUser::orderBy('username')->get();


        // Filter out short/common/empty words
        $filteredWords = array_filter($wordArray, function ($word) {
            return strlen($word) > 2 && !in_array($word, ['the', 'and', 'you', 'are', 'that', 'this', 'with', 'for', 'have', 'from', 'your', 'but', 'not', 'all', 'can', 'just', 'get', 'like', 'ada', 'aja', 'dan', 'yang', 'nya']);
        });

        // Count word frequencies
        $wordCounts = array_count_values($filteredWords);

        // Convert to [ [word, count], ... ] format for WordCloud
        $words = collect($wordCounts)
            ->sortDesc()
            ->take(50) // limit to top 50 words
            ->map(function ($count, $word) {
                return [$word, $count];
            })
            ->values()
            ->toArray();

            $adminUsers = Message::where('from', 'like', 'admin%')
    ->select('from')
    ->distinct()
    ->orderBy('from')
    ->get();


        $missingPreferences = !Preference::where('user_id', $user->id)->exists();
        $messages = Message::latest()->get();

        return view('dashboard', compact('labels','adminUsers', 'dataset1','telegramUsers', 'dataset2', 'words', 'missingPreferences', 'messages'));
    }
    public function getUserChartData(Request $request)
{
    $chatId = $request->query('chat_id');
    $adminFrom = $request->query('admin');

    $baseQuery = Message::query();

    if ($chatId) {
        $baseQuery->where('chat_id', $chatId);
    }

    if ($adminFrom) {
        $baseQuery->where('from', $adminFrom);
    }

    $totalMessages = (clone $baseQuery)
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

    // For admin dataset, even if admin is already filtered, keep this logic
    $adminMessages = (clone $baseQuery)
        ->where('from', 'like', 'admin%')
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

    $allDates = collect($totalMessages->keys())->merge($adminMessages->keys())->unique()->sort();

    $labels = $allDates->values()->toArray();
    $dataset1 = $allDates->map(fn($date) => $totalMessages[$date]->count ?? 0)->values();
    $dataset2 = $allDates->map(fn($date) => $adminMessages[$date]->count ?? 0)->values();

    return response()->json([
        'labels' => $labels,
        'dataset1' => $dataset1,
        'dataset2' => $dataset2,
    ]);
}



    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
