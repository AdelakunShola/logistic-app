<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Show the chat page with conversation list and optional active conversation
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // Get all internal staff (admin, manager, staff, driver - not customers)
        $staffUsers = User::where('id', '!=', $currentUser->id)
            ->whereIn('role', ['admin', 'super_admin', 'manager', 'staff', 'driver'])
            ->where('status', 'active')
            ->orderBy('user_name')
            ->get();

        // Get conversations: users this person has chatted with, ordered by latest message
        $conversations = $this->getConversations($currentUser->id);

        // Get unread counts per user
        $unreadCounts = ChatMessage::where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->select('sender_id', DB::raw('count(*) as count'))
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        // Active chat partner (if selected)
        $activeChat = null;
        $messages = collect();
        $chatUserId = $request->query('user');

        if ($chatUserId) {
            $activeChat = User::find($chatUserId);
            if ($activeChat) {
                $messages = ChatMessage::betweenUsers($currentUser->id, $activeChat->id)
                    ->with(['sender', 'receiver'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Mark messages from this user as read
                ChatMessage::where('sender_id', $activeChat->id)
                    ->where('receiver_id', $currentUser->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

        return view('backend.chat.index', compact(
            'staffUsers', 'conversations', 'unreadCounts',
            'activeChat', 'messages'
        ));
    }

    /**
     * Send a message
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->route('admin.chat.index', ['user' => $request->receiver_id]);
    }

    /**
     * Get messages for a conversation (AJAX)
     */
    public function messages(Request $request, $userId)
    {
        $currentUser = Auth::user();

        $messages = ChatMessage::betweenUsers($currentUser->id, $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Get total unread count (for sidebar badge)
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Show the chat page for drivers
     */
    public function driverIndex(Request $request)
    {
        $currentUser = Auth::user();

        $staffUsers = User::where('id', '!=', $currentUser->id)
            ->whereIn('role', ['admin', 'super_admin', 'manager', 'staff', 'driver'])
            ->where('status', 'active')
            ->orderBy('user_name')
            ->get();

        $conversations = $this->getConversations($currentUser->id);

        $unreadCounts = ChatMessage::where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->select('sender_id', DB::raw('count(*) as count'))
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        $activeChat = null;
        $messages = collect();
        $chatUserId = $request->query('user');

        if ($chatUserId) {
            $activeChat = User::find($chatUserId);
            if ($activeChat) {
                $messages = ChatMessage::betweenUsers($currentUser->id, $activeChat->id)
                    ->with(['sender', 'receiver'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                ChatMessage::where('sender_id', $activeChat->id)
                    ->where('receiver_id', $currentUser->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

        return view('driver.chat.index', compact(
            'staffUsers', 'conversations', 'unreadCounts',
            'activeChat', 'messages'
        ));
    }

    /**
     * Send a message (driver)
     */
    public function driverSend(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->route('driver.chat.index', ['user' => $request->receiver_id]);
    }

    /**
     * Get conversation list: users with latest messages
     */
    private function getConversations(int $userId)
    {
        // Get the latest message ID for each conversation partner
        $latestMessages = DB::select("
            SELECT partner_id, MAX(id) as latest_message_id
            FROM (
                SELECT receiver_id as partner_id, id FROM chat_messages WHERE sender_id = ?
                UNION ALL
                SELECT sender_id as partner_id, id FROM chat_messages WHERE receiver_id = ?
            ) as conversations
            GROUP BY partner_id
            ORDER BY latest_message_id DESC
        ", [$userId, $userId]);

        if (empty($latestMessages)) {
            return collect();
        }

        $messageIds = array_column($latestMessages, 'latest_message_id');
        $partnerOrder = array_column($latestMessages, 'partner_id');

        $messages = ChatMessage::whereIn('id', $messageIds)
            ->with(['sender', 'receiver'])
            ->get()
            ->keyBy(function ($msg) use ($userId) {
                return $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
            });

        // Return in order of latest message
        return collect($partnerOrder)->map(fn($partnerId) => [
            'user' => User::find($partnerId),
            'lastMessage' => $messages[$partnerId] ?? null,
        ])->filter(fn($c) => $c['user'] !== null);
    }
}
