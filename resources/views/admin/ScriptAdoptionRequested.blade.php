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

    // Subscribe to the channel and bind to the event
    var channel = pusher.subscribe('adoption-requests');
    channel.bind('adoption-request.submitted', function(data) {
        console.log('Received data:', data); // Log data for debugging

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
            let actionButtons = '';
            if (adoptionRequest.status === 'Pending') {
                actionButtons = `
                    <form action="/admin/adoption/${adoptionRequest.id}/verify" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-warning">Verify</button>
                    </form>
                `;
            } else if (adoptionRequest.status === 'Verifying') {
                actionButtons = `
                    <form action="/admin/adoption/${adoptionRequest.id}/approve" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <button type="button" class="btn btn-danger" onclick="showRejectModal(${adoptionRequest.id})">Reject</button>
                `;
            }

            // Append new adoption request to the table
            $('#dataTables-example tbody').prepend(`
                <tr class="odd gradeX">
                    <td>${adoptionRequest.first_name || 'N/A'}</td>
                    <td>${adoptionRequest.last_name || 'N/A'}</td>
                    <td>${adoptionRequest.gender || 'N/A'}</td>
                    <td>${adoptionRequest.phone_number || 'N/A'}</td>
                    <td>${adoptionRequest.address || 'N/A'}</td>
                    <td>${adoptionRequest.salary || 'N/A'}</td>
                    <td>${adoptionRequest.question1 || 'N/A'}</td>
                    <td>${adoptionRequest.question2 || 'N/A'}</td>
                    <td>${adoptionRequest.question3 || 'N/A'}</td>
                    <td>
                        <img width="40px" height="40px" src="${adoptionRequest.valid_id ? '/storage/' + adoptionRequest.valid_id : '/images/placeholder.png'}" class="card-img-top" alt="Valid ID">
                    </td>
                    <td>
                        <img width="40px" height="40px" src="${adoptionRequest.valid_id_with_owner ? '/storage/' + adoptionRequest.valid_id_with_owner : '/images/placeholder.png'}" class="card-img-top" alt="ID with User">
                    </td>
                    <td>${actionButtons}</td> 
                </tr>
            `);
        } else {
            console.error('adoptionRequest is undefined or not in the expected structure', data);
        }
    });

    // Function to show the reject modal
    function showRejectModal(adoptionId) {
        $(`#rejectAdoptionModal${adoptionId}`).modal('show');
    }
</script>
