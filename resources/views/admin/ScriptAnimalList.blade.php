<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
            animalElement.find('h4').text(animal.species);
            animalElement.find('h6').text(animal.age);
            animalElement.find('.description').text(animal.description);
        } else {
            // Append new animal element if not found
            $('#animal-list').append(`
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
                            <p class="description" data-description="${animal.description}"></p>
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