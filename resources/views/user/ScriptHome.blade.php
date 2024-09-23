<script>
    // Enable Pusher logging - don't include this in production
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher('c27cf1cca7e151dffc12', {
        cluster: 'ap1'
    });

    // Initialize channels and events
    var channels = {
        'animal-upload': 'animal-profile',
        'animal-update': 'animal-updated',
        'animal-updates': 'animal-profile-updated',
        'animal-delete': 'animal-profile-deleted'
    };

    // Function to display notifications
    function displayNotification(message, backgroundColor = "#4caf50") {
        Toastify({
            text: message,
            duration: 5000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: backgroundColor,
            stopOnFocus: true
        }).showToast();
    }

    // Function to update or add an animal profile to the list
    function updateAnimalList(animal) {
        var animalElement = $(`#animal-${animal.id}`);
        if (animalElement.length) {
            // Update existing animal element
            animalElement.find('img').attr('src', `/storage/${animal.profile_picture}?t=${new Date().getTime()}`);
            animalElement.find('h4').text(animal.name);
            animalElement.find('h6').text(animal.age);
            animalElement.find('.description').text(animal.description);
        } else {
            // Append new animal element if not found
            $('.row').append(`
            <div class="col-md-4" id="animal-${animal.id}">
                <div class="product-item">
                    <a href="#">
                        <img src="/storage/${animal.profile_picture}" class="card-img-top" alt="${animal.name}">
                    </a>
                    <div class="down-content">
                        <a href="#">
                            <h4>${animal.name}</h4>
                        </a>
                        <h6>${animal.age}</h6>
                        <p class="description" data-description="${animal.description}">${animal.description}</p>
                        <span><a href="/animals/${animal.id}">View Profile</a></span>
                    </div>
                </div>
            </div>
            `);
        }
    }

  // Function to remove an animal profile from the list
    function removeAnimalFromList(data) {
        // Ensure `data` has an `id` property
        var id = data.id || data.animal; // Handle both formats

        if (id) {
            var animalElement = $(`#animal-${id}`);
            if (animalElement.length) {
                console.log("Removing animal profile: ", id); // Log removal for debugging
                animalElement.remove(); // Use remove() instead of delete()
                displayNotification(`Animal Profile Deleted: ${id}`, "#f44336"); // Red background for deletion
            } else {
                console.error("Animal element not found for ID:", id);
            }
        } else {
            console.error("Cannot remove animal profile: Invalid data", data); // Log error for invalid data
        }
    }

    // Subscribe to channels and bind to events
   var subscribedChannels = {};

    Object.keys(channels).forEach(function(channelName) {
        var channel = pusher.subscribe(channelName);
        subscribedChannels[channelName] = channel; // Keep track of subscribed channels

        channel.bind(channels[channelName], function(data) {
            console.log("Event received: ", channels[channelName], data); // Log event for debugging
            if (channels[channelName] === 'animal-profile-deleted') {
                removeAnimalFromList(data); // Remove animal profile
            } else {
                displayNotification(`Animal Profile Updated: ${data.animal.name}`);
                updateAnimalList(data.animal); // Update or add animal profile
            }
        });
    });

    // Cleanup function to unsubscribe from all channels
    function cleanup() {
        Object.keys(subscribedChannels).forEach(function(channelName) {
            pusher.unsubscribe(channelName);
        });
    }

    // Ensure cleanup on page unload
    window.addEventListener('beforeunload', cleanup);
    </script>


<script>
    // Enable Pusher logging - don't include this in production
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher('c27cf1cca7e151dffc12', {
        cluster: 'ap1'
    });

    // Ensure this is the ID of the logged-in user
    var userId = @json(auth()->user()->id);
    var channel = pusher.subscribe('user.' + userId);

    // Function to update the notification dropdown
    function updateNotificationList(notification) {
        var notificationList = document.getElementById('notificationList');
        var notificationItem = document.createElement('a');
        notificationItem.className = 'dropdown-item';
        notificationItem.href = '#'; // Modify the href as needed

        // Add content to the notification element
        notificationItem.innerHTML = `
            Message: ${notification.message}
        `;

        // Append the new notification to the list
        notificationList.appendChild(notificationItem);

        // Update the notification count
        var countElement = document.getElementById('notificationCount');
        if (countElement) {
            // Retrieve the current notification count or initialize it to 0
            let currentCount = parseInt(countElement.innerText) || 0;
            // Increment the count
            countElement.innerText = currentCount + 1;
        }
    }

    // Listen for new notifications
    channel.bind('App\\Events\\AdoptionRequestVerify', function(data) {
        updateNotificationList(data);
    });

    channel.bind('App\\Events\\AdoptionRequestRejected', function(data) {
        updateNotificationList(data);
    });

    channel.bind('App\\Events\\AdoptionRequestApproved', function(data) {
        updateNotificationList(data);
    });

     channel.bind('App\\Events\\MeetingScheduled', function(data) {
        updateNotificationList(data);
    });

      channel.bind('App\\Events\\UpdateMeetingScheduled', function(data) {
        updateNotificationList(data);
    });

    channel.bind('App\\Events\\AbusesVerify', function(data) {
        updateNotificationList(data);
    });

    channel.bind('App\\Events\\AbusesApprove', function(data) {
        updateNotificationList(data);
    });

    channel.bind('App\\Events\\AbusesReject', function(data) {
        updateNotificationList(data);
    });

// Event listener for clicking the Notifications link
document.addEventListener('DOMContentLoaded', function () {
    var notificationDropdown = document.getElementById('notificationDropdown');
    if (notificationDropdown) {
        notificationDropdown.addEventListener('click', function () {
            // Reset notification count to 0
            var countElement = document.getElementById('notificationCount');
            if (countElement) {
                countElement.innerText = 0;
            }

            // Send a request to mark all notifications as read
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Ensure to include CSRF token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notifications marked as read.');
                } else {
                    console.error('Failed to mark notifications as read.');
                }
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
        });
    }

    // Function to update the notification list
    function updateNotificationList(notification) {
        var notificationList = document.getElementById('notificationList');
        if (notificationList) {
            // Create a new notification item
            var notificationItem = document.createElement('li');
            notificationItem.textContent = notification.message; // Adjust this based on the notification structure

            // Prepend the new notification item to the list
            notificationList.prepend(notificationItem);
        }
    }

    // Fetch existing notifications from the server
    fetch('/notifications')
        .then(response => response.json())
        .then(data => {
            if (data.notifications) {
                // Clear the existing notification list
                var notificationList = document.getElementById('notificationList');
                notificationList.innerHTML = '';

                // Prepend new notifications to the list
                data.notifications.forEach(notification => {
                    updateNotificationList(notification);
                });

                // Set initial notification count
                document.getElementById('notificationCount').innerText = data.notifications.length;
            } else {
                console.error('Invalid data format:', data);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
});

</script>



