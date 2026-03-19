<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Chat extends Component
{
    use WithFileUploads;

    public $activeConversationId;

    public $newMessageBody = '';

    public $messageMedia;

    public $authId;

    public $messageLimit = 20;

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

        if (! $this->activeConversationId) {
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
        $this->messageLimit = 20;
        $this->markAsRead();
        $this->dispatch('scroll-to-bottom');
    }

    public function loadMore()
    {
        $this->messageLimit += 20;
    }

    protected function markAsRead()
    {
        if ($this->activeConversationId) {
            Message::where('conversation_id', $this->activeConversationId)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $this->dispatch('refreshCount')->to('chat-badge');
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

    public function sendMessage(ImageService $imageService)
    {
        ini_set('memory_limit', '512M');
        Log::info('sendMessage called. Body: '.$this->newMessageBody.' Conv: '.$this->activeConversationId.' Media: '.($this->messageMedia ? 'yes' : 'no'));

        $this->validate([
            'newMessageBody' => 'nullable|string|max:5000',
            'messageMedia' => 'nullable|file|image|max:20480', // 20MB max
        ], [
            'messageMedia.max' => 'The image is too large (max 20MB).',
            'messageMedia.image' => 'The upload must be an image.',
        ]);

        if (! $this->newMessageBody && ! $this->messageMedia) {
            return;
        }

        if (! $this->activeConversationId) {
            Log::warning('sendMessage failed: No active conversation.');
            session()->flash('error', 'No active conversation selected.');

            return;
        }

        try {
            $message = Message::create([
                'conversation_id' => $this->activeConversationId,
                'sender_id' => Auth::id(),
                'body' => $this->newMessageBody,
                'type' => $this->messageMedia ? 'media' : 'text',
            ]);

            if ($this->messageMedia) {
                $mediaData = $imageService->compressAndStore($this->messageMedia, 'chat_media');
                $message->media()->create($mediaData);

                $this->reset('messageMedia');
            }

            Log::info('Message created: '.$message->id);

            Conversation::where('id', $this->activeConversationId)->update(['last_message_at' => now()]);

            $this->reset('newMessageBody');

            // Broadcasting is optional — don't let it break sending
            try {
                broadcast(new MessageSent($message->load('media')))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Broadcast failed (non-critical): '.$e->getMessage());
            }

            $this->dispatch('scroll-to-bottom');
        } catch (\Throwable $e) {
            Log::error('Chat Error: '.$e->getMessage());
            session()->flash('error', 'Failed to send message: '.$e->getMessage());
        }
    }

    public function clearMedia()
    {
        $this->reset('messageMedia');
    }

    public function render()
    {
        // Always fetch fresh data (no computed property caching)
        $conversations = Auth::user()->conversations()->with(['userOne', 'userTwo', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->get();

        $activeConversation = null;
        $messages = collect();

        if ($this->activeConversationId) {
            $activeConversation = Conversation::with(['userOne', 'userTwo'])
                ->find($this->activeConversationId);

            $messages = $activeConversation->messages()
                ->with(['sender', 'media'])
                ->latest()
                ->limit($this->messageLimit)
                ->get()
                ->reverse();
        }

        return view('livewire.chat', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
        ]);
    }
}
