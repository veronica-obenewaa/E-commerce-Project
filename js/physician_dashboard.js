(function(){
    function renderBookingRow(b) {
        var dt = b.appointment_datetime ? new Date(b.appointment_datetime) : null;
        var dateStr = dt ? dt.toLocaleString() : 'Not set';
        var patient = b.patient_name ? (b.patient_name + (b.patient_contact ? (' - ' + b.patient_contact) : '')) : 'Unknown patient';
        var reason = b.reason_text ? b.reason_text : '';
        var statusBadge = '<span class="badge bg-secondary">' + (b.status || 'scheduled') + '</span>';
        
        // Zoom link section
        var zoomSection = '';
        if (b.zoom_join_url && (b.zoom_status || 'pending') === 'created') {
            var zoomHtml = '<div style="background: linear-gradient(135deg, #e6f7ff 0%, #f0f8ff 100%); border-left: 4px solid #0066cc; padding: 0.75rem; border-radius: 6px; margin-top: 0.75rem;">'
                + '<i class="fas fa-video"></i> <strong>Zoom Meeting Ready</strong>';
            if (b.zoom_password) {
                zoomHtml += '<br><small style="color: #666;">Password: <code>' + escapeHtml(b.zoom_password) + '</code></small>';
            }
            zoomHtml += '<br><a href="' + escapeHtml(b.zoom_join_url) + '" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #0066cc 0%, #004499 100%); border: none; color: white; padding: 0.4rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 600; margin-top: 0.5rem; font-size: 0.9rem;">'
                + '<i class="fas fa-video"></i> Start Meeting</a>'
                + '</div>';
            zoomSection = zoomHtml;
        } else if ((b.zoom_status || 'pending') === 'pending') {
            zoomSection = '<div style="background: linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%); border-left: 4px solid #ffb300; padding: 0.75rem; border-radius: 6px; margin-top: 0.75rem;">'
                + '<i class="fas fa-hourglass-half"></i> <strong>Zoom Meeting Link</strong> - Pending creation'
                + '</div>';
        }
        
        var actions = '';
        if ((b.status || 'scheduled') !== 'completed') {
            actions += '<button class="btn btn-sm btn-success me-1 mark-complete" data-id="' + b.booking_id + '">Complete</button>';
        }
        actions += '<button class="btn btn-sm btn-danger me-1 mark-cancel" data-id="' + b.booking_id + '">Cancel</button>';

        var html = '<div class="list-group-item">'
            + '<div class="d-flex w-100 justify-content-between">'
            + '<h5 class="mb-1">' + escapeHtml(patient) + '</h5>'
            + '<small>' + dateStr + '</small>'
            + '</div>'
            + '<p class="mb-1">' + escapeHtml(reason) + '</p>'
            + zoomSection
            + '<div class="d-flex justify-content-between align-items-center" style="margin-top: 0.75rem;">'
            + '<small>' + statusBadge + '</small>'
            + '<div>' + actions + '</div>'
            + '</div>'
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

    // fetch appointment count for stat card
    function fetchAppointmentCount() {
        // reuse bookings endpoint and count
        fetch('../actions/fetch_physician_bookings.php')
            .then(function(res){ if (!res.ok) throw new Error('Network response was not ok'); return res.json(); })
            .then(function(data){
                if (data.status === 'success' && Array.isArray(data.data)) {
                    $('#appt-count').text(data.data.length);
                } else {
                    $('#appt-count').text('0');
                }
            })
            .catch(function(err){
                console.error(err);
                $('#appt-count').text('0');
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
                // populate dashboard summary fields
                if (typeof $('#summary_name') !== 'undefined') {
                    $('#summary_name').text(p.customer_name || '<?php echo htmlspecialchars($physician_name ?? ""); ?>');
                }
                if (typeof $('#summary_hospital') !== 'undefined') {
                    $('#summary_hospital').text(p.hospital_name || '-');
                }
                if (typeof $('#summary_hospital_reg') !== 'undefined') {
                    $('#summary_hospital_reg').text(p.hospital_registration_number || '-');
                }
                if (typeof $('#summary_contact') !== 'undefined') {
                    $('#summary_contact').text(p.customer_contact || '-');
                }
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
        // initial load
        fetchBookings();
        fetchAppointmentCount();
        fetchProfile();

        // sidebar navigation helper
        window.showSection = function(sectionId) {
            // hide all sections
            document.querySelectorAll('.section').forEach(function(el){ el.style.display = 'none'; });
            var el = document.getElementById(sectionId);
            if (el) el.style.display = 'block';
            // update summary if dashboard shown
            if (sectionId === 'dashboard') {
                fetchProfile();
                fetchAppointmentCount();
            }
            if (sectionId === 'bookings') {
                fetchBookings();
            }
            if (sectionId === 'profile') {
                fetchProfile();
            }
            return false;
        };

        // handle profile form submit
        $(document).on('submit', '#physProfileForm', submitProfileForm);

        // booking action handlers (delegate)
        $(document).on('click', '.mark-complete', function(){
            var id = $(this).data('id');
            updateBookingStatus(id, 'completed');
        });
        $(document).on('click', '.mark-cancel', function(){
            var id = $(this).data('id');
            if (!confirm('Mark booking as cancelled?')) return;
            updateBookingStatus(id, 'cancelled');
        });
    });

    function updateBookingStatus(booking_id, status) {
        fetch('../actions/update_booking_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'booking_id=' + encodeURIComponent(booking_id) + '&status=' + encodeURIComponent(status)
        })
        .then(function(res){ return res.json(); })
        .then(function(data){
            if (data.status === 'success') {
                fetchBookings();
                fetchAppointmentCount();
            } else {
                alert(data.message || 'Failed to update booking');
            }
        })
        .catch(function(err){ console.error(err); alert('Error updating booking'); });
    }
})();
