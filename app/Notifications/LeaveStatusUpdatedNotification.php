<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public LeaveRequest $leaveRequest) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->leaveRequest->status);
        $emoji = $this->leaveRequest->status === 'approved' ? '✅' : '❌';

        return [
            'type' => 'leave_status',
            'title' => "Leave Request {$status} {$emoji}",
            'message' => "Your request for {$this->leaveRequest->leaveType->name} ({$this->leaveRequest->start_date->format('M d')} - {$this->leaveRequest->end_date->format('M d, Y')}) has been {$this->leaveRequest->status}.",
            'leave_request_id' => $this->leaveRequest->id,
            'action_url' => route('leaves.show', $this->leaveRequest->id),
            'status' => $this->leaveRequest->status,
            'icon' => $this->leaveRequest->status === 'approved' ? 'check' : 'x',
        ];
    }
}
