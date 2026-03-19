<?php
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $isOpen = false;
    public $activeConversationId = null;
    public $newMessageBody = '';
    public $messageMedia;
    public $isTyping = false;
    public $typingUser = null;
    public $authId;
    public $messageLimit = 20;
    
    public $activeTab = 'chats'; // 'chats' or 'friends'
    public $search = '';

    public function mount()
    {
        $this->authId = Auth::id();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->search = '';
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen && $this->activeConversationId) {
            $this->markAsRead();
            $this->dispatch('scroll-chat-bottom');
        }
    }

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
        $this->messageLimit = 20;
        $this->markAsRead();
        $this->dispatch('scroll-to-bottom');
        $this->dispatch('chat-switched');
    }

    public function loadMore()
    {
        $this->messageLimit += 20;
    }

    public function startConversationWith($userId)
    {
        $otherUser = User::findOrFail($userId);
        $conversation = Auth::user()->getConversationWith($otherUser);
        $this->activeConversationId = $conversation->id;
        $this->activeTab = 'chats';
        $this->markAsRead();
        $this->dispatch('scroll-chat-bottom');
    }

    public function closeConversation()
    {
        $this->activeConversationId = null;
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
        if (!$this->newMessageBody && !$this->messageMedia) return;
        if (!$this->activeConversationId) return;

        $this->validate([
            'newMessageBody' => 'nullable|string|max:5000',
            'messageMedia' => 'nullable|file|image|max:20480',
        ], [
            'messageMedia.max' => 'File too large (max 20MB).',
            'messageMedia.image' => 'The upload must be an image.',
        ]);

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

            Conversation::where('id', $this->activeConversationId)->update(['last_message_at' => now()]);
            $this->reset('newMessageBody');

            broadcast(new MessageSent($message->load('media')))->toOthers();
            $this->dispatch('scroll-chat-bottom');
        } catch (\Throwable $e) {
            Log::error('Floating Chat Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        // Conversations
        $conversationsQuery = $user->conversations()
            ->with(['userOne', 'userTwo', 'messages' => fn($q) => $q->latest()->limit(1)]);
            
        if ($this->search) {
            $conversationsQuery->where(function($q) {
                $q->whereHas('userOne', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                  ->orWhereHas('userTwo', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
            });
        }
        $conversations = $conversationsQuery->get();

        // Friends
        $friends = collect();
        if ($this->activeTab === 'friends') {
            $friends = $user->friends();
            if ($this->search) {
                $friends = $friends->filter(fn($f) => stripos($f->name, $this->search) !== false);
            }
        }

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

        return view('livewire.chat-floating', [
            'conversations' => $conversations,
            'friends' => $friends,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
        ]);
    }
}; ?>

<div class="fixed bottom-6 right-6 z-[100] flex flex-col items-end pointer-events-none"
     x-data="{ 
        open: @entangle('isOpen'),
        active: @entangle('activeConversationId'),
        typing: false,
        typingUser: '',
        showModal: false,
        modalImage: '',
        modalTitle: '',
        openModal(url, title) {
            this.modalImage = url;
            this.modalTitle = title;
            this.showModal = true;
        },
        scrollBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });
        }
     }"
     x-init="
        $watch('active', value => { if(value) scrollBottom() });
        Livewire.on('scroll-to-bottom', () => scrollBottom());
        
        // Echo Typing Indicators
        $watch('active', (newVal, oldVal) => {
            if (oldVal) Echo.private(`chat.${oldVal}`).stopListeningForWhisper('typing');
            if (newVal) {
                Echo.private(`chat.${newVal}`)
                    .listenForWhisper('typing', (e) => {
                        typing = true;
                        typingUser = e.name;
                        setTimeout(() => { typing = false }, 3000);
                    });
            }
        });
     ">
    <!-- Image Modal -->
    <div x-show="showModal" 
         x-transition.opacity
         class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm pointer-events-auto"
         x-cloak
         @keydown.escape.window="showModal = false">
        <div class="relative max-w-lg w-full bg-white jojo-border jojo-shadow-lg" @click.away="showModal = false">
            <div class="p-3 border-b-2 border-slate-900 flex justify-between items-center bg-purple-900 text-yellow-400">
                <h3 class="text-lg bangers uppercase tracking-widest truncate mr-4" x-text="modalTitle"></h3>
                <div class="flex gap-2">
                    <a :href="modalImage" :download="modalTitle" class="bg-yellow-400 text-purple-900 bangers text-sm px-3 py-0.5 jojo-border hover:bg-yellow-300 transition-colors">
                        DL
                    </a>
                    <button @click="showModal = false" class="text-white hover:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div class="p-1 bg-slate-200">
                <img :src="modalImage" class="max-w-full max-h-[60vh] mx-auto object-contain">
            </div>
        </div>
    </div>

    <!-- Chat Window -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="mb-4 w-80 md:w-96 h-[500px] bg-white jojo-border jojo-shadow-lg flex flex-col pointer-events-auto overflow-hidden">
        
        <!-- Header -->
        <div class="bg-purple-900 p-4 border-b-4 border-slate-900 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <template x-if="active">
                    <button @click="active = null" class="text-yellow-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                </template>
                <h3 class="text-2xl bangers text-yellow-400 uppercase tracking-widest truncate" x-text="active ? 'Chat' : 'Messaging'"></h3>
            </div>
            <button @click="open = false" class="text-white hover:text-red-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-hidden relative flex flex-col bg-slate-50">
            @if(!$activeConversationId)
                <!-- Tabs -->
                <div class="flex border-b-2 border-slate-900 shrink-0">
                    <button wire:click="setTab('chats')" class="flex-1 p-2 bangers text-lg tracking-widest transition-colors {{ $activeTab === 'chats' ? 'bg-yellow-400 text-purple-900' : 'bg-purple-800 text-white hover:bg-purple-700' }}">CHATS</button>
                    <button wire:click="setTab('friends')" class="flex-1 p-2 bangers text-lg tracking-widest transition-colors {{ $activeTab === 'friends' ? 'bg-yellow-400 text-purple-900' : 'bg-purple-800 text-white hover:bg-purple-700' }}">FRIENDS</button>
                </div>

                <!-- Search -->
                <div class="p-2 border-b-2 border-slate-900 bg-white shrink-0">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="w-full bg-slate-100 border-2 border-slate-900 p-1.5 text-xs font-bold text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors uppercase tracking-widest">
                </div>

                <!-- Lists -->
                <div class="flex-1 overflow-y-auto p-2 space-y-2">
                    @if($activeTab === 'chats')
                        @forelse($conversations as $conv)
                            @php $otherUser = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne; @endphp
                            <button wire:click="selectConversation({{ $conv->id }})" 
                                    class="w-full flex items-center gap-3 p-3 bg-white jojo-border hover:bg-yellow-50 transition-colors text-left group">
                                <div class="w-12 h-12 rounded-full border-2 border-slate-900 overflow-hidden shrink-0 shadow-[2px_2px_0px_#111]">
                                    @if($otherUser->avatar_url)
                                        <img src="{{ asset('storage/'.$otherUser->avatar_url) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-yellow-400 flex items-center justify-center text-xl bangers text-purple-900">{{ substr($otherUser->name,0,1) }}</div>
                                    @endif
                                </div>
                                <div class="flex-1 truncate">
                                    <div class="flex justify-between items-center mb-0.5">
                                        <span class="font-black text-purple-900 uppercase tracking-widest text-sm">{{ $otherUser->name }}</span>
                                        @php $unread = $conv->messages()->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count(); @endphp
                                        @if($unread > 0)
                                            <span class="bg-fuchsia-600 text-white text-[10px] px-1.5 py-0.5 rounded-full font-black">{{ $unread }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-500 truncate uppercase font-bold">
                                        {{ $conv->messages->first()?->body ?? 'Start chatting...' }}
                                    </p>
                                </div>
                            </button>
                        @empty
                            <div class="p-8 text-center">
                                <p class="bangers text-purple-900/50 uppercase">No conversations found.</p>
                            </div>
                        @endforelse
                    @else
                        @forelse($friends as $friend)
                            <button wire:click="startConversationWith({{ $friend->id }})" 
                                    class="w-full flex items-center gap-3 p-3 bg-white jojo-border hover:bg-yellow-50 transition-colors text-left group">
                                <div class="w-12 h-12 rounded-full border-2 border-slate-900 overflow-hidden shrink-0 shadow-[2px_2px_0px_#111]">
                                    @if($friend->avatar_url)
                                        <img src="{{ asset('storage/'.$friend->avatar_url) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-yellow-400 flex items-center justify-center text-xl bangers text-purple-900">{{ substr($friend->name,0,1) }}</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <span class="font-black text-purple-900 uppercase tracking-widest text-sm">{{ $friend->name }}</span>
                                    <p class="text-[10px] text-fuchsia-600 uppercase font-black tracking-widest">FRIEND</p>
                                </div>
                                <div class="w-8 h-8 bg-indigo-600 text-white flex items-center justify-center rounded-lg jojo-border shadow-[2px_2px_0px_#111]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                            </button>
                        @empty
                            <div class="p-8 text-center">
                                <p class="bangers text-purple-900/50 uppercase">No friends found.</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            @else
                <!-- Active Chat Messages -->
                <div x-ref="messages" class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col scroll-smooth no-scrollbar">
                    @if($messages->count() >= $messageLimit)
                        <div class="flex justify-center pb-2">
                            <button wire:click="loadMore" class="bg-purple-900 text-yellow-400 bangers text-sm px-4 py-1 jojo-border shadow-[2px_2px_0px_#111] hover:bg-purple-800 transition-all uppercase tracking-widest">
                                LOAD OLDER
                            </button>
                        </div>
                    @endif

                    @foreach($messages as $msg)
                        <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} animate-in fade-in slide-in-from-bottom-2 duration-300">
                            <div class="max-w-[85%] {{ $msg->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-white text-purple-900' }} p-3 jojo-border shadow-[2px_2px_0px_#111] relative">
                                @if($msg->type === 'media' && $msg->media->count() > 0)
                                    <div class="mb-2 space-y-1">
                                        @foreach($msg->media as $media)
                                            <div class="rounded border-2 border-slate-900 overflow-hidden bg-black/5 flex flex-col group/media relative">
                                                @if(str_starts_with($media->mime_type, 'image/'))
                                                    <div class="relative">
                                                        <button @click="openModal('{{ asset('storage/'.$media->path) }}', '{{ $media->original_name ?? 'IMAGE' }}')" class="block w-full">
                                                            <img src="{{ asset('storage/'.$media->path) }}" class="max-w-full h-auto">
                                                        </button>
                                                        <a href="{{ asset('storage/' . $media->path) }}" target="_blank" download="{{ $media->original_name ?? 'IMAGE' }}" 
                                                           class="absolute top-1 right-1 bg-yellow-400 text-purple-900 p-1 jojo-border shadow-[1px_1px_0px_#111] opacity-0 group-hover/media:opacity-100 transition-opacity hover:bg-yellow-300 scale-75 origin-top-right">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    </div>
                                                    <div class="px-1.5 py-0.5 bg-black/10 flex justify-between items-center gap-2">
                                                        <span class="text-[8px] font-black uppercase truncate tracking-tighter">{{ $media->original_name ?? 'IMAGE' }}</span>
                                                        <span class="text-[8px] font-bold opacity-70 whitespace-nowrap">{{ round($media->size / 1024) }} KB</span>
                                                    </div>
                                                @else
                                                    <div class="p-2 flex items-center gap-2 bg-white/50">
                                                        <div class="w-6 h-6 bg-purple-900 text-yellow-400 flex items-center justify-center text-[10px] rounded">📄</div>
                                                        <div class="flex flex-col min-w-0">
                                                            <a href="{{ asset('storage/'.$media->path) }}" target="_blank" download="{{ $media->original_name ?? basename($media->path) }}" class="text-[9px] font-black underline truncate">
                                                                {{ $media->original_name ?? 'Download' }}
                                                            </a>
                                                            <span class="text-[7px] font-bold opacity-70">{{ round($media->size / 1024) }} KB</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <p class="text-xs font-bold leading-relaxed break-words whitespace-pre-wrap">{{ $msg->body }}</p>
                                <span class="block text-[8px] mt-1 opacity-70 font-black uppercase tracking-tighter text-right">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($msg->sender_id === auth()->id() && $msg->read_at) • SEEN @endif
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Typing Indicator -->
                    <div x-show="typing" class="flex justify-start">
                        <div class="bg-white p-2 jojo-border shadow-[2px_2px_0px_#111] flex items-center gap-2">
                            <div class="flex gap-1">
                                <div class="w-1.5 h-1.5 bg-purple-900 rounded-full animate-bounce"></div>
                                <div class="w-1.5 h-1.5 bg-purple-900 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                                <div class="w-1.5 h-1.5 bg-purple-900 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="p-3 bg-white border-t-4 border-slate-900 shrink-0">
                    @if($messageMedia)
                        <div class="mb-2 p-2 bg-purple-100 border-2 border-slate-900 flex items-center justify-between shadow-[2px_2px_0px_#111]">
                            <div class="flex items-center gap-2">
                                @if(str_starts_with($messageMedia->getMimeType(), 'image/'))
                                    <img src="{{ $messageMedia->temporaryUrl() }}" class="w-10 h-10 object-cover border border-slate-900 shadow-[1px_1px_0px_#111]">
                                @else
                                    <div class="w-10 h-10 bg-purple-900 flex items-center justify-center text-yellow-400 bangers text-xl border border-slate-900 shadow-[1px_1px_0px_#111]">FILE</div>
                                @endif
                                <div class="truncate max-w-[120px]">
                                    <p class="text-[8px] font-black text-purple-900 uppercase tracking-widest truncate">{{ $messageMedia->getClientOriginalName() }}</p>
                                    <p class="text-[6px] font-bold text-slate-500 uppercase tracking-widest">{{ round($messageMedia->getSize() / 1024) }} KB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="$reset('messageMedia')" class="text-red-600 hover:text-red-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif

                    <form wire:submit="sendMessage" class="flex gap-2 items-end">
                        <div class="shrink-0">
                            <label class="cursor-pointer group">
                                <input type="file" wire:model="messageMedia" class="hidden">
                                <div class="bg-yellow-400 text-purple-900 p-2 jojo-border shadow-[2px_2px_0px_#111] group-hover:bg-yellow-300 transition-all group-active:translate-y-0.5 group-active:shadow-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </label>
                        </div>

                        <div class="flex-1 relative">
                            <input type="text" 
                                   wire:model="newMessageBody"
                                   @input="Echo.private(`chat.${active}`).whisper('typing', { name: @js(auth()->user()->name) })"
                                   placeholder="Type message..."
                                   class="w-full bg-slate-100 border-2 border-slate-900 p-2 text-xs font-bold text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors uppercase tracking-widest @error('messageMedia') border-red-600 @enderror @error('newMessageBody') border-red-600 @enderror">
                            @error('messageMedia')
                                <span class="text-[8px] text-red-600 font-black uppercase mt-1 block">{{ $message }}</span>
                            @enderror
                            @error('newMessageBody')
                                <span class="text-[8px] text-red-600 font-black uppercase mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white bangers text-lg px-4 py-2 jojo-border shadow-[2px_2px_0px_#111] hover:bg-indigo-500 transition-all active:translate-y-1 active:shadow-none uppercase">
                            <span wire:loading.remove wire:target="sendMessage, messageMedia">SEND</span>
                            <span wire:loading wire:target="sendMessage, messageMedia">...</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="open = !open" 
            class="pointer-events-auto bg-yellow-400 text-purple-900 p-4 rounded-full border-4 border-slate-900 jojo-shadow hover:scale-110 active:scale-95 transition-all group relative">
        <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
        
        <!-- Badge -->
        <livewire:chat-badge />
    </button>
</div>
