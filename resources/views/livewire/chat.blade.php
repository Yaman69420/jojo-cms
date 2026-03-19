<div class="h-[calc(100vh-160px)] flex bg-white jojo-border jojo-shadow overflow-hidden">
    
    <!-- Sidebar: Conversations List -->
    <div class="w-1/3 border-r-4 border-slate-900 flex flex-col bg-purple-900">
        <div class="p-4 border-b-4 border-slate-900 bg-purple-950">
            <h2 class="text-2xl text-yellow-400 bangers transform -skew-x-6 tracking-widest">CONVERSATIONS</h2>
        </div>
        
        <div class="flex-1 overflow-y-auto no-scrollbar">
            @forelse($conversations as $conv)
                @php $otherUser = $conv->otherUser(auth()->user()); @endphp
                <button wire:click="selectConversation('{{ $conv->id }}')" 
                        class="w-full p-4 flex items-center gap-4 border-b-2 border-purple-800 hover:bg-purple-800 transition-colors {{ $activeConversationId === $conv->id ? 'bg-purple-700' : '' }}">
                    <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center border-2 border-slate-900 shrink-0 overflow-hidden">
                        @if($otherUser->avatar_url)
                            <img src="{{ asset('storage/' . $otherUser->avatar_url) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl bangers text-purple-900">{{ substr($otherUser->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="text-left flex-1 min-w-0">
                        <p class="text-lg bangers text-white truncate leading-none mb-1">{{ strtoupper($otherUser->name) }}</p>
                        @if($conv->messages->first())
                            <p class="text-xs text-purple-300 truncate font-bold uppercase tracking-widest">
                                {{ $conv->messages->first()->sender_id === auth()->id() ? 'YOU: ' : '' }}
                                @if($conv->messages->first()->type === 'media')
                                    ★ SENT MEDIA ★
                                @else
                                    {{ $conv->messages->first()->body }}
                                @endif
                            </p>
                        @endif
                    </div>
                </button>
            @empty
                <div class="p-8 text-center">
                    <p class="text-purple-400 font-black uppercase tracking-widest text-sm">No chats yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Chat Window -->
    <div class="flex-1 flex flex-col bg-slate-100 relative overflow-hidden"
         x-data="{ 
            typing: false, 
            typingUser: '',
            activeConversationId: @entangle('activeConversationId'),
            showModal: false,
            modalImage: '',
            modalTitle: '',
            openModal(url, title) {
                this.modalImage = url;
                this.modalTitle = title;
                this.showModal = true;
            },
            initEcho() {
                @if($activeConversationId)
                    Echo.private(`chat.${@js($activeConversationId)}`)
                        .listenForWhisper('typing', (e) => {
                            this.typing = true;
                            this.typingUser = e.name;
                            setTimeout(() => { this.typing = false }, 3000);
                        });
                @endif
            }
         }"
         x-init="initEcho(); $watch('activeConversationId', () => initEcho())">

        <!-- Image Modal -->
        <div x-show="showModal" 
             x-transition.opacity
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
             x-cloak
             @keydown.escape.window="showModal = false">
            <div class="relative max-w-5xl w-full bg-white jojo-border jojo-shadow-lg" @click.away="showModal = false">
                <div class="p-4 border-b-4 border-slate-900 flex justify-between items-center bg-purple-900 text-yellow-400">
                    <h3 class="text-2xl bangers uppercase tracking-widest truncate mr-8" x-text="modalTitle"></h3>
                    <div class="flex gap-4">
                        <a :href="modalImage" :download="modalTitle" class="bg-yellow-400 text-purple-900 bangers text-xl px-4 py-1 jojo-border hover:bg-yellow-300 transition-colors">
                            DOWNLOAD
                        </a>
                        <button @click="showModal = false" class="text-white hover:text-red-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="p-2 bg-slate-200">
                    <img :src="modalImage" class="max-w-full max-h-[70vh] mx-auto object-contain">
                </div>
            </div>
        </div>

        <div class="absolute inset-0 opacity-5 pointer-events-none select-none overflow-hidden flex flex-wrap gap-20 p-10">
            @for($i = 0; $i < 20; $i++)
                <span class="text-4xl font-black transform rotate-12">ゴゴゴ</span>
                <span class="text-4xl font-black transform -rotate-12">ドドド</span>
            @endfor
        </div>

        @if($activeConversation)
            <!-- Chat Header -->
            @php $activeOtherUser = $activeConversation->otherUser(auth()->user()); @endphp
            <div class="p-4 border-b-4 border-slate-900 bg-white flex items-center justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center border-2 border-slate-900 overflow-hidden">
                        @if($activeOtherUser->avatar_url)
                            <img src="{{ asset('storage/' . $activeOtherUser->avatar_url) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl bangers text-purple-900">{{ substr($activeOtherUser->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <h3 class="text-2xl bangers text-purple-900 tracking-widest uppercase">{{ $activeOtherUser->name }}</h3>
                </div>
                <a href="{{ route('profile.show', $activeOtherUser) }}" class="text-xs font-black text-fuchsia-600 hover:text-fuchsia-500 uppercase tracking-[0.2em] underline decoration-2 underline-offset-4">View Profile</a>
            </div>

            <!-- Messages Area -->
            <div x-ref="messageContainer" 
                 x-data="{ 
                    scrollToBottom() { 
                        this.$nextTick(() => {
                            this.$el.scrollTop = this.$el.scrollHeight;
                        });
                    } 
                 }"
                 x-init="scrollToBottom()"
                 @scroll-to-bottom.window="scrollToBottom()"
                 class="flex-1 overflow-y-auto p-6 space-y-4 relative z-10 no-scrollbar scroll-smooth">
                
                @if($messages->count() >= $messageLimit)
                    <div class="flex justify-center pb-4">
                        <button wire:click="loadMore" class="bg-purple-900 text-yellow-400 bangers text-xl px-6 py-2 jojo-border shadow-[4px_4px_0px_#111] hover:bg-purple-800 transition-all uppercase tracking-widest">
                            LOAD OLDER MESSAGES
                        </button>
                    </div>
                @endif

                @foreach($messages as $msg)
                    <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" wire:key="msg-{{ $msg->id }}">
                        <div class="max-w-[80%] flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <div class="px-4 py-2 jojo-border shadow-[4px_4px_0px_#111] overflow-hidden
                                {{ $msg->sender_id === auth()->id() ? 'bg-fuchsia-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-indigo-600 text-yellow-400 rounded-r-xl rounded-tl-xl' }}">
                                
                                @if($msg->media->count() > 0)
                                    <div class="mb-2 space-y-2">
                                        @foreach($msg->media as $media)
                                            <div class="rounded-lg overflow-hidden border-2 border-black/20 bg-black/10 flex flex-col group/media relative">
                                                @if(str_starts_with($media->mime_type, 'image/'))
                                                    <div class="relative">
                                                        <button @click="openModal('{{ asset('storage/' . $media->path) }}', '{{ $media->original_name ?? 'IMAGE' }}')" class="block w-full">
                                                            <img src="{{ asset('storage/' . $media->path) }}" class="max-w-full h-auto max-h-64 object-contain mx-auto">
                                                        </button>
                                                        <a href="{{ asset('storage/' . $media->path) }}" target="_blank" download="{{ $media->original_name ?? 'IMAGE' }}" 
                                                           class="absolute top-2 right-2 bg-yellow-400 text-purple-900 p-1.5 jojo-border shadow-[2px_2px_0px_#111] opacity-0 group-hover/media:opacity-100 transition-opacity hover:bg-yellow-300">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    </div>
                                                    <div class="px-2 py-1 bg-black/20 flex justify-between items-center gap-4">
                                                        <span class="text-[10px] font-black uppercase truncate tracking-tighter">{{ $media->original_name ?? 'IMAGE' }}</span>
                                                        <span class="text-[10px] font-bold opacity-70 whitespace-nowrap">{{ round($media->size / 1024) }} KB</span>
                                                    </div>
                                                @elseif(str_starts_with($media->mime_type, 'video/'))
                                                    <video controls class="max-w-full h-auto max-h-64">
                                                        <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <div class="px-2 py-1 bg-black/20 flex justify-between items-center gap-4">
                                                        <span class="text-[10px] font-black uppercase truncate tracking-tighter">{{ $media->original_name ?? 'VIDEO' }}</span>
                                                        <span class="text-[10px] font-bold opacity-70 whitespace-nowrap">{{ round($media->size / 1024) }} KB</span>
                                                    </div>
                                                @else
                                                    <div class="p-4 flex items-center gap-3">
                                                        <div class="w-10 h-10 bg-white/20 rounded flex items-center justify-center text-xl">📄</div>
                                                        <div class="flex flex-col min-w-0">
                                                            <a href="{{ asset('storage/' . $media->path) }}" target="_blank" download="{{ $media->original_name ?? basename($media->path) }}" class="underline font-black text-sm uppercase truncate">
                                                                {{ $media->original_name ?? 'Download File' }}
                                                            </a>
                                                            <span class="text-[10px] font-bold opacity-70">{{ round($media->size / 1024) }} KB</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($msg->body)
                                    <p class="font-bold text-lg tracking-wide leading-snug">{{ $msg->body }}</p>
                                @endif
                            </div>
                            <span class="text-[10px] font-black text-slate-400 mt-2 uppercase tracking-widest">
                                {{ $msg->created_at->format('H:i') }}
                                @if($msg->sender_id === auth()->id() && $msg->read_at)
                                    • SEEN
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach

                <!-- Typing Indicator UI -->
                <div x-show="typing" class="flex justify-start animate-pulse" x-transition>
                    <div class="bg-indigo-600 text-yellow-400 px-4 py-2 jojo-border shadow-[4px_4px_0px_#111] rounded-r-xl rounded-tl-xl">
                        <div class="flex gap-1.5 items-center">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                            <span class="ml-2 text-xs font-black uppercase tracking-widest" x-text="typingUser + ' is typing...'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t-4 border-slate-900 relative z-10">
                @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-red-100 border-2 border-red-600 text-red-600 font-bold uppercase text-xs">
                        {{ session('error') }}
                    </div>
                @endif

                @if($messageMedia)
                    <div class="mb-4 p-4 bg-purple-100 border-4 border-slate-900 flex items-center justify-between shadow-[4px_4px_0px_#111]">
                        <div class="flex items-center gap-4">
                            @if(str_starts_with($messageMedia->getMimeType(), 'image/'))
                                <img src="{{ $messageMedia->temporaryUrl() }}" class="w-16 h-16 object-cover border-2 border-slate-900 shadow-[2px_2px_0px_#111]">
                            @else
                                <div class="w-16 h-16 bg-purple-900 flex items-center justify-center text-yellow-400 bangers text-3xl border-2 border-slate-900 shadow-[2px_2px_0px_#111]">FILE</div>
                            @endif
                            <div>
                                <p class="text-xs font-black text-purple-900 uppercase tracking-widest truncate max-w-[200px]">{{ $messageMedia->getClientOriginalName() }}</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ round($messageMedia->getSize() / 1024) }} KB</p>
                            </div>
                        </div>
                        <button type="button" wire:click="clearMedia" class="text-red-600 hover:text-red-800 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                <form wire:submit="sendMessage" class="flex gap-4 items-end">
                    <!-- File Upload Button -->
                    <div class="shrink-0">
                        <label class="cursor-pointer group">
                            <input type="file" wire:model="messageMedia" class="hidden">
                            <div class="bg-yellow-400 text-purple-900 p-3 jojo-border shadow-[4px_4px_0px_#111] group-hover:bg-yellow-300 transition-all group-active:translate-y-1 group-active:shadow-none">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                        </label>
                    </div>

                    <div class="flex-1 relative">
                        <input type="text" wire:model="newMessageBody"
                               @input="Echo.private(`chat.${@js($activeConversationId)}`).whisper('typing', { name: @js(auth()->user()->name) })"
                               placeholder="Type a message..."
                               class="w-full bg-slate-100 border-4 border-slate-900 p-3 font-bold text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors tracking-widest @error('newMessageBody') border-red-600 @enderror @error('messageMedia') border-red-600 @enderror">                        @error('newMessageBody')
                            <span class="text-[10px] text-red-600 font-black uppercase mt-1">{{ $message }}</span>
                        @enderror
                        @error('messageMedia')
                            <span class="text-[10px] text-red-600 font-black uppercase mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="bg-indigo-600 text-white bangers text-2xl px-8 py-3 jojo-border shadow-[4px_4px_0px_#111] hover:bg-indigo-500 transition-all active:translate-y-1 active:shadow-none uppercase disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendMessage, messageMedia">SEND</span>
                        <span wire:loading wire:target="sendMessage, messageMedia">...</span>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center p-12 text-center relative z-10">
                <div class="w-32 h-32 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-slate-900 mb-6 jojo-shadow">
                    <span class="text-6xl text-purple-900">★</span>
                </div>
                <h3 class="text-4xl bangers text-purple-900 mb-2 uppercase tracking-widest transform -skew-x-6">SELECT A CONVERSATION</h3>
                <p class="text-slate-500 font-bold uppercase tracking-widest">Select a conversation to start chatting.</p>
            </div>
        @endif
    </div>
</div>
