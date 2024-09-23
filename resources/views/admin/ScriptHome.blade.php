<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.css">

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Enable Pusher logging - don't include this in production
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher('c27cf1cca7e151dffc12', {
        cluster: 'ap1'
    });

    // Subscribe to the channel and bind to the event
    var channel = pusher.subscribe('adoption-requests');
    channel.bind('adoption-request.submitted', function(data) {
        console.log('Received data:', data); // Log data for debugging

        var countElement = document.getElementById('notificationCount');
        if (countElement) {
            // Retrieve the current notification count or initialize it to 0
            let currentCount = parseInt(countElement.innerText) || 0;
            // Increment the count
            countElement.innerText = currentCount + 1;

            var adoptionRequest = data.adoptionRequest;

            if (adoptionRequest) {
                // Use Toastify to display the notification
                Toastify({
                    text: `New Adoption Request Submitted by ${adoptionRequest.first_name || 'Unknown'} ${adoptionRequest.last_name || 'Unknown'}`,
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#4fbe87"
                    },
                    stopOnFocus: true
                }).showToast();

                // Generate the action buttons based on the adoption request status
               
               
            } 
        }
    });

</script>

<script>
$(document).ready(function() {
    // Check if the canvas element is available
    if (document.getElementById('adoption-rate-chart')) {
        var ctx = document.getElementById('adoption-rate-chart').getContext('2d');
        var adoptionRateChart = new Chart(ctx, {
            type: 'line', // Change this to 'bar', etc. if needed
            data: {
                labels: @json($months), // Replace with your months data
                datasets: [{
                    label: 'Adoption Rate',
                    data: @json($adoptionRate), // Replace with your adoption counts
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        console.error("Canvas element not found.");
    }
});
</script>

<script>
$(document).ready(function() {
    // Check if the canvas element is available
    if (document.getElementById('intake-vs-adoption-chart')) {
        var ctx = document.getElementById('intake-vs-adoption-chart').getContext('2d');
        var intakeVsAdoptionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months), // Months or relevant labels
                datasets: [
                    {
                        label: 'Animal Intake',
                        data: @json($intakeData), // Replace with your intake data
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Adoptions',
                        data: @json($adoptionData), // Replace with your adoption data
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        console.error("Canvas element not found.");
    }
});
</script>

<script>
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
</script>
