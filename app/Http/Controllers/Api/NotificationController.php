<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        try {
            $query = Notification::forUser(Auth::id())
                ->with(['fromUser:id,name,profile_picture', 'recipe:id,title,thumbnail'])
                ->orderBy('created_at', 'desc');

            // Filter by read status
            if ($request->has('unread_only') && $request->unread_only == 'true') {
                $query->unread();
            }

            $notifications = $query->paginate(20);

            // Transform notifications for better UI display
            $transformedNotifications = $notifications->through(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'from_user' => [
                        'id' => $notification->fromUser->id,
                        'name' => $notification->fromUser->name,
                        'profile_picture' => $notification->fromUser->profile_picture_url
                    ],
                    'recipe' => $notification->recipe ? [
                        'id' => $notification->recipe->id,
                        'title' => $notification->recipe->title,
                        'thumbnail' => $notification->recipe->thumbnail ?
                            asset('images/recipes/' . $notification->recipe->thumbnail) :
                            asset('images/recipes/default-recipe.jpg')
                    ] : null,
                    'formatted_message' => $notification->fromUser->name . ' menambahkan resep anda ke favorit',
                    'time_ago' => $notification->created_at->diffForHumans()
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully',
                'data' => $transformedNotifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::forUser(Auth::id())->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notification count
     */
    public function unreadCount()
    {
        $count = Notification::forUser(Auth::id())->unread()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::forUser(Auth::id())->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
}
