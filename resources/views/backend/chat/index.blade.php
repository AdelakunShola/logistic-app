@extends('admin.admin_dashboard')
@section('admin')

<style>
@media (max-width: 767px) {
    .chat-hide-mobile { display: none !important; }
}
#chat-wrapper {
    display: flex;
    flex-direction: row;
    height: calc(100vh - 64px);
    background: #f9fafb;
    overflow: hidden;
}
#chat-sidebar {
    width: 320px;
    min-width: 320px;
    border-right: 1px solid #e5e7eb;
    background: #fff;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
}
#chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}
@media (max-width: 767px) {
    #chat-wrapper {
        flex-direction: column;
        height: 100vh;
    }
    #chat-sidebar {
        width: 100%;
        min-width: 100%;
        height: 100%;
    }
    #chat-area {
        height: 100%;
    }
}
</style>

<div id="chat-wrapper">
    <!-- Conversations Sidebar -->
    <div id="chat-sidebar" class="{{ $activeChat ? 'chat-hide-mobile' : '' }}">
        <!-- Search & New Chat -->
        <div class="p-4 border-b space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Messages</h2>
                <button onclick="document.getElementById('new-chat-modal').classList.remove('hidden')"
                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="New conversation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/>
                    </svg>
                </button>
            </div>
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="text" id="search-conversations" placeholder="Search conversations..."
                    class="w-full pl-10 pr-4 py-2 text-sm border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    onkeyup="filterConversations(this.value)">
            </div>
        </div>

        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto" id="conversation-list">
            @if($conversations->count() > 0)
                @foreach($conversations as $conv)
                    @php
                        $partner = $conv['user'];
                        $lastMsg = $conv['lastMessage'];
                        $unread = $unreadCounts[$partner->id] ?? 0;
                        $isActive = $activeChat && $activeChat->id === $partner->id;
                    @endphp
                    <a href="{{ route('admin.chat.index', ['user' => $partner->id]) }}"
                        class="conversation-item flex items-start gap-3 p-4 border-b hover:bg-gray-50 transition-colors {{ $isActive ? 'bg-blue-50 border-l-2 border-l-blue-500' : '' }}"
                        data-name="{{ strtolower($partner->user_name) }}">
                        <div class="relative flex-shrink-0">
                            @if($partner->photo)
                                <img src="{{ asset('upload/admin_images/' . $partner->photo) }}" alt="{{ $partner->user_name }}"
                                    class="w-11 h-11 rounded-full object-cover">
                            @else
                                <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm">
                                    {{ strtoupper(substr($partner->user_name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h3 class="text-sm font-medium text-gray-900 truncate {{ $unread > 0 ? 'font-semibold' : '' }}">{{ $partner->user_name }}</h3>
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    {{ $lastMsg ? $lastMsg->created_at->diffForHumans(null, true, true) : '' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $partner->role) }}</p>
                            @if($lastMsg)
                                <p class="text-sm text-gray-500 truncate mt-0.5 {{ $unread > 0 ? 'text-gray-800 font-medium' : '' }}">
                                    @if($lastMsg->sender_id === auth()->id()) <span class="text-gray-400">You:</span> @endif
                                    {{ Str::limit($lastMsg->message, 50) }}
                                </p>
                            @endif
                        </div>
                        @if($unread > 0)
                            <span class="flex-shrink-0 bg-blue-600 text-white text-xs font-bold rounded-full h-5 min-w-[20px] flex items-center justify-center px-1.5 mt-1">
                                {{ $unread }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @else
                <div class="p-8 text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-3">
                        <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>
                    </svg>
                    <p class="text-sm font-medium">No conversations yet</p>
                    <p class="text-xs mt-1">Start a new conversation with a team member</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Chat Area -->
    <div id="chat-area" class="{{ !$activeChat ? 'chat-hide-mobile' : '' }}">
        @if($activeChat)
            <!-- Chat Header -->
            <div class="px-4 md:px-6 py-3 border-b bg-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="backToList()" class="md:hidden p-1 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>
                    @if($activeChat->photo)
                        <img src="{{ asset('upload/admin_images/' . $activeChat->photo) }}" alt="{{ $activeChat->user_name }}"
                            class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm">
                            {{ strtoupper(substr($activeChat->user_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $activeChat->user_name }}</h3>
                        <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $activeChat->role) }}
                            @if($activeChat->email) &middot; {{ $activeChat->email }} @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div style="flex:1;overflow-y:auto;" class="p-4 md:p-6 space-y-4" id="messages-container">
                @php $lastDate = null; @endphp
                @foreach($messages as $msg)
                    @php
                        $msgDate = $msg->created_at->format('Y-m-d');
                        $isMine = $msg->sender_id === auth()->id();
                    @endphp

                    @if($msgDate !== $lastDate)
                        <div class="flex items-center gap-3 my-4">
                            <div class="flex-1 border-t"></div>
                            <span class="text-xs text-gray-400 font-medium">
                                {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M d, Y')) }}
                            </span>
                            <div class="flex-1 border-t"></div>
                        </div>
                        @php $lastDate = $msgDate; @endphp
                    @endif

                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] md:max-w-md lg:max-w-lg">
                            <div class="px-4 py-2.5 rounded-2xl {{ $isMine ? 'bg-blue-600 text-white rounded-br-md' : 'bg-white border text-gray-800 rounded-bl-md' }}">
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $msg->message }}</p>
                            </div>
                            <div class="flex items-center gap-1 mt-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <span class="text-xs text-gray-400">{{ $msg->created_at->format('g:i A') }}</span>
                                @if($isMine && $msg->read_at)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500">
                                        <path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($messages->isEmpty())
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3">
                                <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>
                            </svg>
                            <p class="text-sm">Start your conversation with {{ $activeChat->user_name }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Message Input -->
            <div class="px-4 md:px-6 py-4 border-t bg-white">
                <form action="{{ route('admin.chat.send') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeChat->id }}">
                    <div class="flex-1">
                        <textarea name="message" rows="1" placeholder="Type a message..."
                            class="w-full resize-none border rounded-xl px-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                            onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();this.form.submit()}"
                            oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px'"
                            required></textarea>
                    </div>
                    <button type="submit"
                        class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                        </svg>
                        Send
                    </button>
                </form>
            </div>
        @else
            <!-- No Chat Selected -->
            <div style="flex:1;display:flex;align-items:center;justify-content:center;background:#f9fafb;">
                <div class="text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4">
                        <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-500 mb-1">Internal Team Chat</h3>
                    <p class="text-sm">Select a conversation or start a new one</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- New Chat Modal -->
<div id="new-chat-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('new-chat-modal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold">New Conversation</h3>
                <button onclick="document.getElementById('new-chat-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <input type="text" id="search-staff" placeholder="Search team members..."
                    class="w-full px-4 py-2 text-sm border rounded-lg mb-3 focus:ring-blue-500 focus:border-blue-500"
                    onkeyup="filterStaff(this.value)">
                <div class="max-h-80 overflow-y-auto space-y-1" id="staff-list">
                    @foreach($staffUsers as $user)
                        <a href="{{ route('admin.chat.index', ['user' => $user->id]) }}"
                            class="staff-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
                            data-name="{{ strtolower($user->user_name) }}" data-role="{{ strtolower($user->role) }}">
                            @if($user->photo)
                                <img src="{{ asset('upload/admin_images/' . $user->photo) }}" alt="{{ $user->user_name }}"
                                    class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm">
                                    {{ strtoupper(substr($user->user_name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->user_name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>setTimeout(() => document.getElementById('success-toast')?.remove(), 3000);</script>
@endif

<script>
// Auto-scroll to bottom of messages
const container = document.getElementById('messages-container');
if (container) {
    container.scrollTop = container.scrollHeight;
}

// Back to conversation list (mobile)
function backToList() {
    document.getElementById('chat-sidebar').classList.remove('chat-hide-mobile');
    document.getElementById('chat-area').classList.add('chat-hide-mobile');
}

// Filter conversations
function filterConversations(query) {
    const items = document.querySelectorAll('.conversation-item');
    query = query.toLowerCase();
    items.forEach(item => {
        item.style.display = item.dataset.name.includes(query) ? '' : 'none';
    });
}

// Filter staff in new chat modal
function filterStaff(query) {
    const items = document.querySelectorAll('.staff-item');
    query = query.toLowerCase();
    items.forEach(item => {
        const match = item.dataset.name.includes(query) || item.dataset.role.includes(query);
        item.style.display = match ? '' : 'none';
    });
}
</script>

@endsection
