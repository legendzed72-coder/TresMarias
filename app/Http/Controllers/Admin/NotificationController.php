<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Middleware is applied through routes
    }

    /**
     * Show notification center
     */
    public function index(Request $request)
    {
        $notifications = AdminNotification::where('user_id', auth()->id())
            ->orWhere('send_to_all', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = AdminNotification::forUser(auth()->id())
            ->unread()
            ->count();

        return view('admin.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Show send notification form
     */
    public function create()
    {
        $users = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.notifications.create', [
            'users' => $users,
        ]);
    }

    /**
     * Send notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,danger',
            'recipient_type' => 'required|in:all,specific,role',
            'user_ids' => 'required_if:recipient_type,specific|array',
            'users.*.id' => 'exists:users,id',
            'role' => 'required_if:recipient_type,role|in:customer,staff',
            'action_url' => 'nullable|string|max:500',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ]);

        $adminId = auth()->id();
        $sendToAll = $validated['recipient_type'] === 'all';

        if ($validated['recipient_type'] === 'all') {
            // Send to all users
            AdminNotification::create([
                'admin_id' => $adminId,
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'action_url' => $validated['action_url'] ? json_decode($validated['action_url'], true) : null,
                'send_to_all' => true,
                'scheduled_at' => $validated['scheduled_at'] ?? now(),
            ]);
        } elseif ($validated['recipient_type'] === 'specific') {
            // Send to specific users
            foreach ($validated['user_ids'] as $userId) {
                AdminNotification::create([
                    'admin_id' => $adminId,
                    'user_id' => $userId,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'action_url' => $validated['action_url'] ? json_decode($validated['action_url'], true) : null,
                    'send_to_all' => false,
                    'scheduled_at' => $validated['scheduled_at'] ?? now(),
                ]);
            }
        } elseif ($validated['recipient_type'] === 'role') {
            // Send to all users with specific role
            $users = User::where('role', $validated['role'])->pluck('id');
            foreach ($users as $userId) {
                AdminNotification::create([
                    'admin_id' => $adminId,
                    'user_id' => $userId,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'action_url' => $validated['action_url'] ? json_decode($validated['action_url'], true) : null,
                    'send_to_all' => false,
                    'scheduled_at' => $validated['scheduled_at'] ?? now(),
                ]);
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully!');
    }

    /**
     * Show notification detail
     */
    public function show(AdminNotification $notification)
    {
        if ($notification->user_id !== auth()->id() && !$notification->send_to_all) {
            abort(403);
        }

        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(AdminNotification $notification)
    {
        if ($notification->user_id !== auth()->id() && !$notification->send_to_all) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Delete notification
     */
    public function destroy(AdminNotification $notification)
    {
        if ($notification->user_id !== auth()->id() && !$notification->send_to_all && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    /**
     * Get unread notifications count (API)
     */
    public function getUnreadCount()
    {
        $count = AdminNotification::forUser(auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    /**
     * Get recent notifications (API)
     */
    public function getRecent($limit = 5)
    {
        $notifications = AdminNotification::forUser(auth()->id())
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => route('admin.notifications.show', $notification->id),
                ];
            });

        return response()->json($notifications);
    }
}
