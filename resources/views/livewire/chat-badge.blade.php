<?php
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $unreadCount = 0;
    public $authId;

    public function mount()
    {
        $this->authId = Auth::id();
        $this->refreshCount();
    }

    #[On('echo-private:App.Models.User.{authId},MessageSent')]
    #[On('refreshCount')]
    public function refreshCount()
    {
        $this->unreadCount = Message::whereHas('conversation', function ($query) {
            $query->where('user_one_id', Auth::id())
                  ->orWhere('user_two_id', Auth::id());
        })
        ->where('sender_id', '!=', Auth::id())
        ->whereNull('read_at')
        ->count();
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="w-5 h-5 bg-slate-800 rounded-full animate-pulse"></div>
        HTML;
    }
}; ?>

<div wire:init="refreshCount">
    @if($unreadCount > 0)
        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-fuchsia-600 text-[10px] font-black text-white jojo-border shadow-[2px_2px_0px_#111] animate-bounce">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</div>
