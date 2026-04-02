<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsDropdown extends Component
{
    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()->latest()->limit(10)->get();
    }

    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function refresh(): void
    {
        // This method is called by wire:poll to refresh the component state
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            if ($notification->unread()) {
                $notification->markAsRead();
            }

            $url = $notification->data['action_url'] ?? '#';

            return redirect($url);
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notifications-dropdown', [
            'notifications' => $this->notifications,
        ]);
    }
}
