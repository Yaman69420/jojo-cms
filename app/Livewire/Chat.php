<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public $activeConversationId;
    public $newMessageBody = '';
    public $authId;

    public function mount($userId = null)
    {
        $this->authId = Auth::id();

        if ($userId) {
            $otherUser = User::find($userId);
            if ($otherUser && $otherUser->id !== Auth::id()) {
                $conversation = Auth::user()->getConversationWith($otherUser);
                $this->activeConversationId = $conversation->id;
            }
        }
        
        if (!$this->activeConversationId) {
            $firstConv = Auth::user()->conversations()->first();
            if ($firstConv) {
                $this->activeConversationId = $firstConv->id;
            }
        }

        if ($this->activeConversationId) {
            $this->markAsRead();
        }
    }

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
        $this->markAsRead();
        $this->dispatch('scroll-to-bottom');
    }

    protected function markAsRead()
    {
        if ($this->activeConversationId) {
            Message::where('conversation_id', $this->activeConversationId)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    #[On('echo-private:App.Models.User.{authId},MessageSent')]
    public function onMessageReceived($event)
    {
        if ($this->activeConversationId === $event['message']['conversation_id']) {
            $this->markAsRead();
            $this->dispatch('scroll-to-bottom');
        }
    }

    public function sendMessage()
    {
        Log::info('sendMessage called. Body: ' . $this->newMessageBody . ' Conv: ' . $this->activeConversationId);

        $this->validate([
            'newMessageBody' => 'required|string|max:5000',
        ]);

        if (!$this->activeConversationId) {
            Log::warning('sendMessage failed: No active conversation.');
            session()->flash('error', 'No active conversation selected.');
            return;
        }

        try {
            $message = Message::create([
                'conversation_id' => $this->activeConversationId,
                'sender_id' => Auth::id(),
                'body' => $this->newMessageBody,
                'type' => 'text',
            ]);

            Log::info('Message created: ' . $message->id);

            Conversation::where('id', $this->activeConversationId)->update(['last_message_at' => now()]);

            $this->reset('newMessageBody');

            // Broadcasting is optional — don't let it break sending
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Broadcast failed (non-critical): ' . $e->getMessage());
            }

            $this->dispatch('scroll-to-bottom');
        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Always fetch fresh data (no computed property caching)
        $conversations = Auth::user()->conversations()->with(['userOne', 'userTwo'])->get();

        $activeConversation = null;
        if ($this->activeConversationId) {
            $activeConversation = Conversation::with(['messages.sender', 'userOne', 'userTwo'])
                ->find($this->activeConversationId);
        }

        return view('livewire.chat', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
        ]);
    }
}

