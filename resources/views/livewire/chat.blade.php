<div class="h-[calc(100vh-160px)] flex bg-white jojo-border jojo-shadow overflow-hidden" 
     x-data="{ 
        scrollToBottom() { 
            const container = this.$refs.messageContainer;
            container.scrollTop = container.scrollHeight;
        } 
     }"
     x-init="scrollToBottom()"
     @scroll-to-bottom.window="scrollToBottom()">
    
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
                        @if($conv->messages->last())
                            <p class="text-xs text-purple-300 truncate font-bold uppercase tracking-widest">
                                {{ $conv->messages->last()->sender_id === auth()->id() ? 'YOU: ' : '' }}{{ $conv->messages->last()->body }}
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
    <div class="flex-1 flex flex-col bg-slate-100 relative overflow-hidden">
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
            <div x-ref="messageContainer" wire:poll.1s class="flex-1 overflow-y-auto p-6 space-y-4 relative z-10 no-scrollbar">
                @foreach($activeConversation->messages as $msg)
                    <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" wire:key="msg-{{ $msg->id }}">
                        <div class="max-w-[80%] flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <div class="px-4 py-2 jojo-border shadow-[4px_4px_0px_#111] 
                                {{ $msg->sender_id === auth()->id() ? 'bg-fuchsia-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-indigo-600 text-yellow-400 rounded-r-xl rounded-tl-xl' }}">
                                <p class="font-bold text-lg tracking-wide leading-snug">{{ $msg->body }}</p>
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
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t-4 border-slate-900 relative z-10">
                @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-red-100 border-2 border-red-600 text-red-600 font-bold uppercase text-xs">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit="sendMessage" class="flex gap-4">
                    <div class="flex-1 relative">
                        <input type="text" wire:model="newMessageBody" 
                               placeholder="Type a message... (ORA ORA ORA!)"
                               class="w-full bg-slate-100 border-4 border-slate-900 p-3 font-bold text-purple-900 focus:outline-none focus:bg-yellow-50 transition-colors uppercase tracking-widest @error('newMessageBody') border-red-600 @enderror">
                        @error('newMessageBody')
                            <span class="text-[10px] text-red-600 font-black uppercase mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="bg-indigo-600 text-white bangers text-2xl px-8 py-2 jojo-border shadow-[4px_4px_0px_#111] hover:bg-indigo-500 transition-all active:translate-y-1 active:shadow-none uppercase disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendMessage">SEND</span>
                        <span wire:loading wire:target="sendMessage">...</span>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center p-12 text-center relative z-10">
                <div class="w-32 h-32 bg-yellow-400 rounded-full flex items-center justify-center border-4 border-slate-900 mb-6 jojo-shadow">
                    <span class="text-6xl text-purple-900">★</span>
                </div>
                <h3 class="text-4xl bangers text-purple-900 mb-2 uppercase tracking-widest transform -skew-x-6">MUDA MUDA MUDA!</h3>
                <p class="text-slate-500 font-bold uppercase tracking-widest">Select a conversation to start chatting.</p>
            </div>
        @endif
    </div>
</div>
