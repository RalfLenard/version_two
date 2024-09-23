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

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('abuse-reports');

    // Listen for the event
    channel.bind('AnimalAbuseReportSubmitted', function(data) {
        const report = data.report; // Extract the report data

        // Display a toast notification when new data is received
        Toastify({
            text: `New Abuse Report Submitted: ${report.description}`,
            duration: 5000,
            close: true,
            gravity: "top", // top or bottom
            position: "right", // left, center or right
            backgroundColor: "#4CAF50",
            stopOnFocus: true // Prevents dismissing of toast on hover
        }).showToast();

        let actionButtons = '';
        if (report.status === 'pending') {
            actionButtons = `
                <form action="/admin/abuses/${report.id}/verify" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-warning">Verify</button>
                </form>
            `;
        } else if (report.status === 'Verifying') {
            actionButtons = `
                <form action="/admin/abuses/${report.id}/approve" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <button type="button" class="btn btn-danger" onclick="showRejectModal(${report.id})">Reject</button>
            `;
        }

        // Append the new abuse report to the table
        $('#dataTables-example tbody').prepend(`
            <tr class="odd gradeX">
                <td>${report.description || 'N/A'}</td>
                <td>${report.photos1 ? `<img width="40px" height="40px" src="/storage/${report.photos1}" class="card-img-top">` : '<p>No image available.</p>'}</td>
                <td>${report.photos2 ? `<img width="40px" height="40px" src="/storage/${report.photos2}" class="card-img-top">` : '<p>No image available.</p>'}</td>
                <td>${report.photos3 ? `<img width="40px" height="40px" src="/storage/${report.photos3}" class="card-img-top">` : '<p>No image available.</p>'}</td>
                <td>${report.photos4 ? `<img width="40px" height="40px" src="/storage/${report.photos4}" class="card-img-top">` : '<p>No image available.</p>'}</td>
                <td>${report.photos5 ? `<img width="40px" height="40px" src="/storage/${report.photos5}" class="card-img-top">` : '<p>No image available.</p>'}</td>
                <td>${report.videos1 ? `<video width="320" height="240" controls><source src="${report.videos1}" type="video/mp4">Your browser does not support the video tag.</video>` : '<p>No video available.</p>'}</td>
                <td>${report.videos2 ? `<video width="320" height="240" controls><source src="${report.videos2}" type="video/mp4">Your browser does not support the video tag.</video>` : '<p>No video available.</p>'}</td>
                <td>${report.videos3 ? `<video width="320" height="240" controls><source src="${report.videos3}" type="video/mp4">Your browser does not support the video tag.</video>` : '<p>No video available.</p>'}</td>
                <td>${actionButtons}</td> 
            </tr>
        `);
    });
</script>
