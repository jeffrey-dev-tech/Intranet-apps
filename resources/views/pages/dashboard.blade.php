@if (auth()->check())
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
.two-columns {
  display: grid;
  grid-template-columns: 1fr 1fr; /* Two equal columns */
  gap: 20px; /* Space between columns */
 
}
 .quote-title {
    color: white;
    font-weight: bold;
    font-size: 2rem;        /* Bigger text */
    text-align: left;     /* Center the heading */
    font-family: "Poppins", sans-serif;
    text-shadow: 2px 2px 5px rgba(136, 151, 137, 0.73); /* Subtle shadow for contrast */
    margin: 20px 0;
  }

.quote-container {
  border-radius: 20px;
  max-width: 850px;
  border-radius: 15px;
}

.carousel-item img {
    width: 100%;
    height: 600px; /* Set desired carousel height */
    object-fit: contain; /* Scale the image to fit without cropping */
}
.carousel-bg1 {
    background-size: contain; /* Scales to fit inside without cropping */
    /* background: linear-gradient(0deg, #39b1ff, transparent);    Fills empty space */
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;/* Full screen height */
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
}

.quote-text {
  color: #f1f1f1;
  font-size: 1rem;
  font-style: italic;
  line-height: 1.6;
  font-family: "Georgia", serif;
}
.announcement{
display:flex;
justify-content: center;
align-items: center;

}

.logo-sanden{
display:flex;
justify-content: center;
align-items: center;
margin-top: 10px;
}

.announcement img{
width:98%;

}

.logo-sanden img{
width:700px;

}
.eight h1 {
  text-align:center;
 
  text-transform:uppercase;
  font-size:26px; letter-spacing:1px;
  
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  grid-template-rows: 16px 0;
  grid-gap: 22px;
}

.eight h1:after,.eight h1:before {
  content: " ";
  display: block;
  border-bottom: 2px solid #ccc;
  background-color:white;
}




@media (max-width: 480px) {
        .logo-sanden{
display:flex;
justify-content: center;
align-items: center;
margin-top: 10px;
}
.logo-sanden img{
width:350px;

}
.announcement{
display:flex;
justify-content: center;
align-items: center;
margin-top: 100px;
}
.announcement img{
width:100%;


}

}

</style>


<style>

.ranking-heading {
  display: flex;                 /* arrange items side by side */
  align-items: center;           /* vertically center image + text */
  gap: 10px;                     /* space between logo and text */
  background:rgb(86 139 255);
  padding: 12px;
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
  font-family: "Atlanta College", sans-serif;

}
.ranking-heading-2 {
  display: flex;                 /* arrange items side by side */
  align-items: center;           /* vertically center image + text */
  gap: 10px;                     /* space between logo and text */
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
  font-family: "Atlanta College", sans-serif;

}
.ranking-heading img {
  height: 100px;                  /* make logo smaller */
  width: auto;                   /* keep aspect ratio */
}

.ranking-heading h3 {
  text-align: center;                 /* keep aspect ratio */
}


</style>
<style>
  #teamRanking {
    font-size: 13px;       /* smaller font */
    width: 100%;           /* shrink to fit content */
  }

  #teamRanking th, 
  #teamRanking td {
    padding: 4px 8px;      /* reduce padding */
    white-space: nowrap;   /* prevent wrapping */
  }

  #teamRanking th {
    font-weight: 600;      /* still keep headers readable */
  }
</style>

<div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Welcome {{ Auth::user()->name }}!</h4>
</div>
        </div>
        <div class="row">
<div class="col-4 grid-margin stretch-card">
    <div class="card p-3">
        <h2 class="cyber-heading">SANDEN CLIP & ANNOUNCEMENT</h2>

        @php
            $files = Storage::disk('public')->files('processed');
            sort($files);

            $media = [];
            foreach($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $type = 'unknown';

                if(in_array($ext, ['mp4','mov','avi','mkv','webm'])) {
                    $type = 'video';
                } elseif(in_array($ext, ['jpg','jpeg','png'])) {
                    $type = 'image';
                } elseif($ext === 'gif') {
                    $type = 'gif';
                }

                if($type !== 'unknown') {
                    $media[] = [
                        'url' => asset('storage/processed/' . basename($file)),
                        'type' => $type
                    ];
                }
            }
        @endphp

        @if(empty($media))
            <p>No media found in the processed folder.</p>
        @else
            <div id="media-container">
                <div class="media-wrapper"></div>

                <div class="video-controls text-center mt-2">
                    <button id="prevBtn" class="btn btn-primary">Prev</button>
                    <button id="nextBtn" class="btn btn-primary">Next</button>
                </div>
            </div>

            <script>
                const media = @json($media);
                let current = 0;
                const container = document.getElementById('media-container');
                const wrapper = container.querySelector('.media-wrapper');

                function playMedia(index) {
                    // Loop index automatically
                    if(index < 0) index = media.length - 1;
                    if(index >= media.length) index = 0;
                    current = index;

                    wrapper.innerHTML = ''; // clear previous media
                    const item = media[current];

                    if(item.type === 'video') {
                        const video = document.createElement('video');
                        video.src = item.url;
                        video.id = 'player';
                        video.controls = true;
                        video.autoplay = true;
                        video.classList.add('media-item');
                        wrapper.appendChild(video);

                        video.addEventListener('ended', () => {
                            playMedia(current + 1); // loop to next
                        });

                    } else { // image or gif
                        const img = document.createElement('img');
                        img.src = item.url;
                        img.alt = 'Media';
                        img.id = 'player';
                        img.classList.add('media-item');
                        wrapper.appendChild(img);

                        // Show images/GIFs for 5 seconds then loop
                        setTimeout(() => {
                            playMedia(current + 1);
                        }, 5000);
                    }
                }

                document.getElementById('nextBtn').addEventListener('click', () => {
                    playMedia(current + 1);
                });

                document.getElementById('prevBtn').addEventListener('click', () => {
                    playMedia(current - 1);
                });

                // Start first media
                playMedia(0);
            </script>
        @endif
    </div>
</div>

<style>
.cyber-heading {
    text-align: center;
    color: #1E40AF;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 20px;
     margin-top: 40px;
}

.media-wrapper {
    position: relative;
    width: 100%;
    max-width: 640px;
    margin: 0 auto;
    padding-top: 56.25%; /* 16:9 ratio */
}

.media-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain; /* full fit for images/videos/GIFs */
    background: #000;   /* black bars if aspect ratio differs */
    border-radius: 8px;
}

.video-controls .btn {
    margin: 0 5px;
}
</style>




            <div class="col-8 col-xl-8 grid-margin stretch-card box" >
  <div class="card overflow-hidden">
    
    <!-- Orange bar at top of card -->
   <img class="ranking-heading-2" src="{{asset('img/Q1 - Lets Win Together1.jpg')}}" alt="">
    
    <div class="card-body">
      <div class="table-responsive">
        <table id="teamRanking" class="table-hover" >
          <thead>
            <tr>
              <th>Rank</th>
              <th>Team</th>
              <th>Activity</th>
              <th>Unit</th>
              <th>Level</th>
              <th>Total Progress</th>
              <th>Progress %</th>
              <th>Date Accomplished</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    
  </div>
</div>




        </div>

{{-- 
        <div class="row">
          <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0">Events</h6>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="mb-2 mt-4">1. Anniversary</h3>
                        <div class="d-flex align-items-baseline">
                          <p class="">
                            <span>August 22</span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                      <h6 class="card-title mb-0">Anniversary</h6>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="mb-2 mt-4">1. Jeffrey Salagubang</h3>
                        <div class="d-flex align-items-baseline">
                          <p class="">
                            <span>1 year</span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div> --}}
<div class="row">

<div class="col-md-6">
   <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#eventModal">Add Agenda</button>
   
                 <div id="calendar"></div>  
               </div>
<div class="col-md-6">

      <div class="carousel-bg">
    <div id="anniversarycarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#anniversarycarousel" data-slide-to="0" class="active"></li>
            <li data-target="#anniversarycarousel" data-slide-to="1"></li>
      
        </ol>
   <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @php
            // Get all images from the directory (GIF, PNG, JPG, JPEG)
            $images = glob(public_path('img/Carousel/*.{gif,png,jpg,jpeg,JPG,JPEG,PNG,GIF}'), GLOB_BRACE);
        @endphp

        @foreach ($images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('img/Carousel/' . basename($image)) }}" class="d-block w-100 carousel-image" alt="Image {{ $index + 1 }}">
            </div>
        @endforeach
    </div>
        <a class="carousel-control-prev" href="#anniversarycarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#anniversarycarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
    
</div>
</div>
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="eventForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Add New Agenda</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label class="col-form-label">Title:</label>
              <input type="text" name="title" class="form-control" required>
          </div>
          <div class="form-group">
              <label class="col-form-label">Agenda Type:</label>
              <select name="agenda" id="agenda">
                <option selected disabled>Choose agenda</option>
                <option value="Events">Events</option>
                <option value="Visitors">Visitors</option>
                 <option value="Room">Room</option>
          @php
    $allowedEmails = [
        'jeffrey.salagubang.js@sanden-rs.com',
        'neil.olivera.no@sanden-rs.com',
        'gerryca.joves.gj@sanden-rs.com',
        'merrie.pundano.mp@sanden-rs.com',
        'euvy.marable.df@sanden-rs.com'
    ];
@endphp

@if(Auth::check() && in_array(Auth::user()->email, $allowedEmails))
    <option value="Holiday">Holiday</option>
@endif
                <option value="Training & Seminars">Training & Seminars</option>
              </select>
          </div>
          <div class="form-group" id="meeting-room-group" style="display: none;">
  <label class="col-form-label">Select Meeting Room:</label>
  <select name="meeting_room" id="meeting_room">
    <option selected disabled>Choose room</option>
  <option value="conference_room_1f">Conference Room (1st Floor)</option>
  <option value="meeting_room_1_2f_right">Meeting Room 1 (2nd Floor Right side)</option>
  <option value="meeting_room_2_3f_right">Meeting Room 2 (3rd Floor Right side)</option>
  <!-- <option value="audit_room_3f_left">Audit Room (3rd Floor Left side)</option> -->
  </select>
</div>
          <div class="form-group">
              <label class="col-form-label">Start Date:</label>
              <input type="datetime-local" name="start" class="form-control" required>
          </div>
          <div class="form-group">
              <label class="col-form-label">End Date:</label>
              <input type="datetime-local" name="end" class="form-control">
          </div>
          <div class="form-group">
              <label class="col-form-label">Description:</label>
              <textarea name="description" class="form-control"></textarea>
          </div>
              
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Agenda</button>
      </div>
    </form>
  </div>
</div>
<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Agenda Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- ✅ Responsive Table Wrapper -->
  <div class="table-responsive">
  <table class="table table-bordered w-100" style="table-layout: auto;">
    <tbody>
      <tr>
        <th scope="row">Title</th>
        <td><span id="eventTitle"></span></td>
      </tr>
      <tr>
        <th scope="row">Agenda Type</th>
        <td><span id="eventAgenda"></span></td>
      </tr>
      <tr id="meetingRoomRow" style="display: none;">
        <th scope="row">Meeting Room</th>
        <td><span id="eventMeetingRoom"></span></td>
      </tr>
      <tr>
        <th scope="row">Start</th>
        <td><span id="eventStart"></span></td>
      </tr>
      <tr>
        <th scope="row">End</th>
        <td><span id="eventEnd"></span></td>
      </tr>
      <tr>
        <th scope="row">Description</th>
       <td>
  <span id="eventDescription" style="white-space: normal; word-wrap: break-word; overflow-wrap: anywhere;">
    
  </span>
</td>
      </tr>
    </tbody>
  </table>
</div>
        <!-- /Responsive Table Wrapper -->
      </div>
      <div class="modal-footer">
       @if(in_array(auth()->id(), [100, 63, 101]))
          <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete</button>
        @endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
  #eventDescription {
    white-space: normal;    /* allows text to wrap */
    word-wrap: break-word;  /* breaks long words if needed */
    overflow-wrap: anywhere; /* modern alternative for breaking long strings */
  }

  td {
    white-space: normal; /* ensure table cell allows wrapping */
  }
</style>
<hr>
<style>
    .fc-event-title {
        font-size: 9px;
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: unset !important;
        display: block !important;
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {


    // Initialize DataTable
const table = $('#holidayTable').DataTable({
    processing: true,
    serverSide: false, // fetching all data via API
    paging: true,      // enable pagination
    pageLength: 5,     // show 5 rows per page
    lengthChange: false, // disable changing the page length dropdown
    searching: true,
    ordering: true,
    info: false,        // hides "Showing X to Y of Z entries"
    columns: [
        { data: 'title', title: 'Holidays' },
        { data: 'formatted_date', title: 'Date' }
    ]
});

    // Fetch events from API
    fetch('{{ route('api.holidays') }}')
        .then(response => response.json())
        .then(events => {
            console.log(events);

            if (!Array.isArray(events) || events.length === 0) {
                table.clear().draw();
                table.row.add({ title: 'No events found', formatted_date: '' }).draw();
                return;
            }

            // Sort events by start date ascending
            events.sort((a, b) => new Date(a.start) - new Date(b.start));

            // Format dates for display
            const formattedEvents = events.map(event => {
                const date = new Date(event.start);
                const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: '2-digit' })
                    .replace(',', '')
                    .replace(' ', '-');

                return {
                    title: event.title,
                    formatted_date: formattedDate
                };
            });

            // Load data into DataTable
            table.clear();
            table.rows.add(formattedEvents).draw();
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            table.clear();
            table.row.add({ title: 'Error loading events', formatted_date: '' }).draw();
        });





});



</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    events: '{{ route("calendar.events") }}', // Fetch events from backend
    dayMaxEventRows: true,
    dayMaxEvents: true,

    eventClick: function(info) {
        info.jsEvent.preventDefault();

        fetch('{{ route("calendar.show", ":id") }}'.replace(':id', info.event.id))
            .then(response => response.json())
          .then(data => {
    // Format function for date/time with AM/PM
    function formatDateTime(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    // Basic info
    document.getElementById('eventTitle').textContent = data.title;
    document.getElementById('eventAgenda').textContent = data.agenda;
    document.getElementById('eventStart').textContent = formatDateTime(data.start);
    document.getElementById('eventEnd').textContent = data.end ? formatDateTime(data.end) : 'N/A';
    document.getElementById('eventDescription').textContent = data.description ? data.description : 'No description';

    // Meeting Room handling
 if (data.agenda === 'Room' && data.meeting_room) {
    let meetingRoomLabel = '';

    // Manually map each meeting room value
    if (data.meeting_room === 'conference_room_1f') {
        meetingRoomLabel = 'Conference Room (1st Floor)';
    } else if (data.meeting_room === 'meeting_room_1_2f_right') {
        meetingRoomLabel = 'Meeting Room 1 (2nd Floor Right side)';
    } else if (data.meeting_room === 'meeting_room_2_3f_right') {
        meetingRoomLabel = 'Meeting Room 2 (3rd Floor Right side)';
    } else {
        meetingRoomLabel = data.meeting_room; // fallback in case value doesn’t match
    }

    document.getElementById('eventMeetingRoom').textContent = meetingRoomLabel;
    document.getElementById('meetingRoomRow').style.display = '';
} else {
    document.getElementById('meetingRoomRow').style.display = 'none';
}

    // Set delete button ID
const deleteBtn = document.getElementById('deleteEventBtn');
if (deleteBtn) {
    deleteBtn.setAttribute('data-id', info.event.id);
}

    // Show modal
    $('#viewEventModal').modal('show');
})

            .catch(error => {
                console.error('Error fetching event:', error);
                Swal.fire('Error!', 'Failed to load event details.', 'error');
            });
    }
});

calendar.render();

    // Handle form submit with fetch

    const agendaSelect = document.getElementById('agenda');
  const meetingRoomGroup = document.getElementById('meeting-room-group');

  agendaSelect.addEventListener('change', function() {
    if (this.value === 'Room') {
      meetingRoomGroup.style.display = 'block';
    } else {
      meetingRoomGroup.style.display = 'none';
    }
  });
document.getElementById('eventForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to create this event?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, create it!'
    }).then((result) => {
        if (result.isConfirmed) {

            // Show loading spinner
            Swal.fire({
                title: 'Creating Event...',
                text: 'Please wait while we save your event.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route("calendar.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => null);

                Swal.close(); // Always close loading alert

                if (!response.ok) {
                    // Laravel validation error or custom response
                    if (response.status === 422 && data) {
                        // Validation errors (like missing fields or room conflict)
                        if (data.errors) {
                            const messages = Object.values(data.errors).flat().join('<br>');
                            Swal.fire({
                                title: 'Validation Error',
                                html: messages,
                                icon: 'warning'
                            });
                        } else if (data.message) {
                            Swal.fire('Warning', data.message, 'warning');
                        } else {
                            Swal.fire('Error!', 'Validation failed.', 'error');
                        }
                    } else {
                        Swal.fire('Error!', 'Something went wrong on the server.', 'error');
                    }
                    return; // stop here
                }

                // Success
                if (data.status === 'success') {
                    $('#eventModal').modal('hide');
                    document.getElementById('eventForm').reset();
                    calendar.refetchEvents();

                    Swal.fire({
                        title: 'Created!',
                        text: 'Your event has been added successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire('Error!', 'Unable to create event.', 'error');
            });
        }
    });
});

document.getElementById('deleteEventBtn').addEventListener('click', function () {
    let eventId = this.getAttribute('data-id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("calendar.destroy", ":id") }}'.replace(':id', eventId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    $('#viewEventModal').modal('hide');
                    calendar.refetchEvents();

                    Swal.fire(
                        'Deleted!',
                        'The event has been deleted.',
                        'success'
                    ).then(() => {
    location.reload();
});
                } else {
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting the event.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
               Swal.fire('Error!', 'Unable to delete event.', 'error');
            });
        }
    });
});


});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const url = "{{ route('team.ranking') }}";

  (async () => {
    try {
      const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
      if (!res.ok) {
        const text = await res.text();
        console.error('Team ranking fetch failed:', res.status, text);
        document.querySelector("#teamRanking tbody").innerHTML =
          '<tr><td colspan="8" class="text-center text-danger">Error loading ranking</td></tr>';
        return;
      }

      const data = await res.json();
      const tbody = document.querySelector("#teamRanking tbody");
      tbody.innerHTML = "";

      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center">No ranking data</td></tr>';
        return;
      }

      data.sort((a, b) => (b.total_progress ?? 0) - (a.total_progress ?? 0));

      let rank = 1;
for (const row of data) {
  // Determine medal icon or plain rank number
  let rankDisplay = "";
  if (rank === 1) rankDisplay = `<img src="{{ asset('img/rank-icon/1st.png') }}" alt="Rank 1" width="20">`;
  else if (rank === 2) rankDisplay = `<img src="{{ asset('img/rank-icon/2nd.png') }}" alt="Rank 2" width="20">`;
  else if (rank === 3) rankDisplay = `<img src="{{ asset('img/rank-icon/3rd.png') }}" alt="Rank 3" width="20">`;
  else rankDisplay = rank; // plain number for 4th, 5th, etc.

  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${rankDisplay}</td>
    <td>${row.team_name ?? 'N/A'}</td>
    <td>${row.activity_name ?? 'N/A'}</td>
    <td>${row.unit ?? 'N/A'}</td>
    <td>Level ${row.level_number ?? 'N/A'}</td>
    <td>${row.total_progress ?? 0}</td>
    <td>${row.progress_percentage ? row.progress_percentage.toFixed(2) + '%' : '0%'}</td>
    <td>${row.completed_at ? new Date(row.completed_at).toLocaleString() : 'No date'}</td>
  `;

  tbody.appendChild(tr);
  rank++; // increment rank at the end
}


      // Initialize DataTable
      $('#teamRanking').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: false, 
        order: [[5, 'desc']], // sort by "Total Progress"
        pageLength: 3,
        destroy: true
      });
    } catch (err) {
      console.error('Error fetching team ranking:', err);
      document.querySelector("#teamRanking tbody").innerHTML =
        '<tr><td colspan="8" class="text-center text-danger">Error loading ranking</td></tr>';
    }
  })();
});

</script>
<script src="{{asset('assets/js/anime.min.js')}}"></script>
{{-- 
  <script>
    // Animate multiple elements on page load
    window.addEventListener('load', () => {
      anime({
        targets: '.box',
        opacity: [0, 1],           // Fade in
        translateY: [50, 0],       // Move up
        scale: [0.8, 1],           // Pop effect
        duration: 1000,
        easing: 'easeOutExpo',
        delay: anime.stagger(200)  // Stagger by 200ms for each box
      });
    });
  </script> --}}


@endsection
@endif