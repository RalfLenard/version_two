

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var selectedDate = null;

    // Add CSRF token to AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize FullCalendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        dateClick: function(info) {
            if (selectedDate) {
                document.querySelector(`.fc-day[data-date="${selectedDate}"]`).classList.remove('highlighted-date');
            }

            selectedDate = info.dateStr;
            info.dayEl.classList.add('highlighted-date');
            fetchAppointmentsForDate(info.dateStr);
        }
    });

    calendar.render();

    function fetchAppointmentsForDate(date) {
        $.ajax({
            url: '{{ route("admin.appointments.byDate") }}',
            method: 'GET',
            data: { date: date },
            success: function(response) {
                $('#selected-date').text(date);
                populateAppointmentsTable(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching appointments:', textStatus, errorThrown);
                alert('Failed to fetch appointments. Please try again.');
            }
        });
    }

    function populateAppointmentsTable(appointments) {
        var tbody = $('#appointments-table tbody');
        tbody.empty();

        if (appointments.length === 0) {
            tbody.append('<tr><td colspan="4">No meetings found.</td></tr>');
        } else {
            $.each(appointments, function(index, appointment) {
                tbody.append(`
                    <tr>
                        <td>${appointment.meeting_date}</td>
                        <td>${appointment.adoption_request.user ? appointment.adoption_request.user.name : 'N/A'}</td>
                        <td>${appointment.adoption_request.animal_profile ? appointment.adoption_request.animal_profile.name : 'N/A'}</td>
                        <td><button class="btn btn-warning update-schedule-btn" data-id="${appointment.id}" data-date="${appointment.meeting_date}">Update</button></td>
                    </tr>
                `);
            });
        }
    }

    $('#show-all-appointments').on('click', function() {
        $('#selected-date').text('All Dates');
        $.ajax({
            url: '{{ route("admin.appointments.all") }}',
            method: 'GET',
            success: function(response) {
                populateAppointmentsTable(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching all appointments:', textStatus, errorThrown);
                alert('Failed to fetch all appointments. Please try again.');
            }
        });
    });

    var modal = document.getElementById('updateScheduleModal');
    var span = document.getElementsByClassName('close')[0];

    $(document).on('click', '.update-schedule-btn', function() {
        var id = $(this).data('id');
        var date = $(this).data('date');
        $('#meeting_id').val(id);
        $('#new_meeting_date').val(new Date(date).toISOString().slice(0, 16));
        modal.style.display = 'block';
    });

    span.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    $('#updateScheduleForm').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route("admin.meeting.update") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                modal.style.display = 'none';
                fetchAppointmentsForDate($('#selected-date').text());
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error updating schedule:', textStatus, errorThrown);
                alert('Failed to update schedule. Please try again.');
            }
        });
    });
});

</script>