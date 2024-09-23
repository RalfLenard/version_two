<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell"></i> Notifications
        <span class="badge badge-danger" id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown">
        <div id="notificationList" class="p-3">
            @forelse(auth()->user()->notifications as $notification)
                <a class="dropdown-item" href="#">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="notification-message">
                            {{ $notification->data['message'] ?? 'No Message' }}
                        </div>
                        <div class="notification-time">
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <a class="dropdown-item" href="#">No Notifications Found</a>
            @endforelse
        </div>
    </div>
</li>

<style>
    .notification-dropdown {
        width: 90vh; /* Width of the dropdown */
        max-height: 60vh; /* Max height of the dropdown */
        overflow-y: auto; /* Adds vertical scrollbar if content exceeds max height */
    }

    .notification-message {
        white-space: normal; /* Allows text to wrap */
        overflow-wrap: break-word; /* Breaks long words or URLs */
        word-wrap: break-word; /* Older support for word breaking */
        margin-right: 15px; /* Space between message and time */
    }

    .notification-time {
        margin-top: 5px; /* Space above time */
    }

    #notificationDropdown {
        position: relative;
    }
</style>
