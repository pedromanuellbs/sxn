@extends('layouts.app')

@push('styles')
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        height: 80vh;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .chat-list {
        background-color: #f1f1f1;
        border-right: 1px solid #ccc;
        overflow-y: auto;
    }

    .chat-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .chat-list li {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .chat-list li.active {
        background-color: #d1e7dd;
    }

    .chat-window {
    display: flex;
    flex-direction: column;
    height: 100%; /* take full vertical space in grid */
    overflow: hidden; /* prevent double scrollbars */
}


    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .message.user {
        text-align: right;
        background: #d1e7dd;
        padding: 8px 12px;
        margin: 5px;
        border-radius: 10px;
        max-width: 70%;
        margin-left: auto;
    }

    .message.bot {
        text-align: left;
        background: #f8d7da;
        padding: 8px 12px;
        margin: 5px;
        border-radius: 10px;
        max-width: 70%;
        margin-right: auto;
    }

    .chat-input {
    position: sticky;
    bottom: 0;
    background: white;
    border-top: 1px solid #ccc;
    padding: 10px;
    z-index: 1; /* ensure it's above chat messages */
}


    .chat-input form {
        display: flex;
        gap: 10px;
    }

    .chat-input input {
        flex: 1;
        padding: 8px;
    }

    .chat-input button {
        padding: 8px 16px;
    }
    .message-wrapper {
    position: relative;
    margin-bottom: 10px;
    padding-right: 40px; /* space for delete button */
}

.delete-button {
    position: absolute;
    top: 4px;
    right: 4px;
}
.chat-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
}

</style>
@endpush

@section('content')
<div class="chat-container">

    <!-- Left Panel: Chat List -->
    <div class="chat-list">
        <ul>
            @foreach ($chatList as $chat)
            <li class="d-flex justify-content-between align-items-center {{ $chatId == $chat->chat_id ? 'active' : '' }}">
                <a href="{{ route('chat.index', ['chat_id' => $chat->chat_id]) }}"
                   style="text-decoration: none; color: inherit; flex-grow: 1;">
                    {{ $chat->first_name }} {{ $chat->last_name }}
                </a>
        
                @if(Auth::user()?->role?->role_name === 'Super Admin')
                    <form action="{{ route('telegram.user.delete', $chat->chat_id) }}" method="POST" onsubmit="return confirm('Delete this chat?')" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endif
            </li>
        
            @endforeach
        </ul>
    </div>

    <!-- Right Panel: Chat Window -->
    <div class="chat-window">
        <div class="chat-messages" id="chatBox">
            @foreach($messages as $msg)
    <div class="message-wrapper position-relative">
        <div class="message {{ Str::startsWith($msg->from, 'admin') ? 'user' : 'bot' }}">
            <strong>{{ $msg->from }}:</strong> {{ $msg->text }}
        </div>

        @if(Auth::user()?->role?->role_name === 'Super Admin')
            <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" class="delete-button">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        @endif
    </div>
@endforeach

        </div>

       @if(Auth::user()->role->role_name!='Super Admin')
       <div class="chat-input">
        <form action="{{ url('/send-message') }}" method="POST">
            @csrf
            <input type="hidden" name="chat_id" value="{{ $chatId }}">
            <input type="text" name="message" required placeholder="Tulis pesan...">
            <button type="submit">Kirim</button>
        </form>
    </div>
       @endif
    </div>

</div>

@endsection
