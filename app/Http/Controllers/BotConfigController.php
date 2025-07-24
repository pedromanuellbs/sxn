<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramUser;
use App\Models\BotConfig;

class BotConfigController extends Controller
{
    public function index(){
          $botConfig = BotConfig::get();
        return view('bot_config', compact('botConfig'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tone' => 'required|string|max:50',
        ]);

        $bot = BotConfig::create([
            'name' => $validated['name'],
            'tone' => $validated['tone'],
           
        ]);

        
        return redirect()->back()->with('success', 'Bot configuration saved successfully.');
    }

    public function toggle($id)
    {
        $bot = BotConfig::findOrFail($id);
    
        $bot->status = $bot->status === 'Active' ? 'Inactive' : 'Active';
        $bot->save();
    
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
{
    
    
    BotConfig::where('id', $id)->delete(); 

    return redirect()->back()->with('success', 'Bot Config deleted successfully.');
}
    

}
