<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\GlobalAnnouncement;
use App\Notifications\SpecificUserNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'in:global,specific',
        ]);

        $user = Auth::user();

        if ($validated['type'] === 'global') {
            $user->notify(new GlobalAnnouncement($validated['title'], $validated['message']));
        } else {
            $user->notify(new SpecificUserNotification($validated['title'], $validated['message']));
        }

        return back()->with('success', 'Notificación enviada');
    }

    public function unread()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['notifications' => []]);
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                $notifications = $user->unreadNotifications ?? collect();
                if (is_object($notifications) && method_exists($notifications, 'markAsRead')) {
                    $notifications->markAsRead();
                }
                return response()->json(['notifications' => $notifications]);
            }

            return response()->json(['notifications' => []]);
        } catch (\Throwable $e) {
            return response()->json(['notifications' => []]);
        }
    }
}
