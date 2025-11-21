(function(){
    function renderBookingRow(b) {
        var dt = b.appointment_datetime ? new Date(b.appointment_datetime) : null;
        var dateStr = dt ? dt.toLocaleString() : 'Not set';
        var patient = b.patient_name ? (b.patient_name + (b.patient_contact ? (' - ' + b.patient_contact) : '')) : 'Unknown patient';
        var reason = b.reason_text ? b.reason_text : '';
        var statusBadge = '<span class="badge bg-secondary">' + (b.status || 'scheduled') + '</span>';

        var html = '<div class="list-group-item">'
            + '<div class="d-flex w-100 justify-content-between">'
            + '<h5 class="mb-1">' + escapeHtml(patient) + '</h5>'
            + '<small>' + dateStr + '</small>'
            + '</div>'
            + '<p class="mb-1">' + escapeHtml(reason) + '</p>'
            + '<small>' + statusBadge + '</small>'
            + '</div>';
        return html;
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function fetchBookings(){
        $('#bookingsMsg').html('Loading...');
        fetch('../actions/fetch_physician_bookings.php')
            .then(function(res){
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(function(data){
                if (data.status !== 'success') {
                    $('#bookingsMsg').html('<div class="alert alert-warning">' + (data.message || 'No bookings') + '</div>');
                    return;
                }
                var list = data.data || [];
                if (!list.length) {
                    $('#bookingsMsg').html('<div class="alert alert-info">No bookings found</div>');
                    $('#bookingsList').html('');
                    return;
                }
                $('#bookingsMsg').html('');
                var html = '';
                list.forEach(function(b){
                    html += renderBookingRow(b);
                });
                $('#bookingsList').html(html);
            })
            .catch(function(err){
                console.error(err);
                $('#bookingsMsg').html('<div class="alert alert-danger">Error fetching bookings</div>');
            });
    }

    // Profile fetch and update
    function fetchProfile() {
        $('#profileMsg').html('Loading...');
        fetch('../actions/fetch_physician_profile.php')
            .then(function(res){
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(function(data){
                if (data.status !== 'success') {
                    $('#profileMsg').html('<div class="alert alert-warning">' + (data.message || 'Could not load profile') + '</div>');
                    return;
                }
                var p = data.data || {};
                $('#customer_name').val(p.customer_name || '');
                $('#hospital_name').val(p.hospital_name || '');
                $('#hospital_registration_number').val(p.hospital_registration_number || '');
                $('#customer_country').val(p.customer_country || '');
                $('#customer_city').val(p.customer_city || '');
                $('#customer_contact').val(p.customer_contact || '');
                $('#profileMsg').html('');
            })
            .catch(function(err){
                console.error(err);
                $('#profileMsg').html('<div class="alert alert-danger">Error loading profile</div>');
            });
    }

    function submitProfileForm(e) {
        e.preventDefault();
        var btn = $('#saveProfileBtn');
        btn.prop('disabled', true).text('Saving...');
        var formData = new FormData(document.getElementById('physProfileForm'));
        fetch('../actions/update_physician_profile_action.php', {
            method: 'POST',
            body: formData
        })
        .then(function(res){
            return res.json();
        })
        .then(function(data){
            if (data.status === 'success') {
                $('#profileMsg').html('<div class="alert alert-success">' + data.message + '</div>');
            } else {
                $('#profileMsg').html('<div class="alert alert-danger">' + (data.message || 'Failed to save') + '</div>');
            }
        })
        .catch(function(err){
            console.error(err);
            $('#profileMsg').html('<div class="alert alert-danger">Error saving profile</div>');
        })
        .finally(function(){
            btn.prop('disabled', false).text('Save Profile');
        });
    }

    $(document).ready(function(){
        fetchBookings();
        // when profile tab shown, fetch profile
        $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (e) {
            var target = $(e.target).attr('data-bs-target');
            if (target === '#profile') fetchProfile();
        });

        // handle profile form submit
        $(document).on('submit', '#physProfileForm', submitProfileForm);
    });
})();
