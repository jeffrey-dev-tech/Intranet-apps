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
<div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Welcome {{ Auth::user()->name }}!</h4>
</div>
        </div>

               
        <div class="row" style="border-radius: 20px;">
          <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card overflow-hidden">
              <div class="card-body" style="background:#0683d5;">
             
            <div class="quote-container">

<h3 class="quote-title">ANNOUNCEMENTS</h3>
<table id="holidayTable" class="table"></table>

        </div>

                <div class="flot-wrapper">
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
        <div class="carousel-inner">
           <div class="carousel-item  active">
                <img src="{{ asset('img/Anniv/ikida.gif') }}" class="d-block w-100 carousel-image" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    {{-- <h3 style="color:white; text-shadow: 2px 2px 5px green;">Happy Birthday</h3> --}}
                    {{-- <p>September 10</p> --}}
                </div>
            </div>
               <div class="carousel-item  ">
                <img src="{{ asset('img/Anniv/larry.gif') }}" class="d-block w-100 carousel-image" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    {{-- <h3 style="color:white; text-shadow: 2px 2px 5px green;">Happy Birthday</h3> --}}
                    {{-- <p>September 10</p> --}}
                </div>
            </div>

          
            <!-- <div class="carousel-item ">
                <img src="{{ asset('img/Birthday/Anniv_Jeffrey.png') }}" class="d-block w-100 carousel-image" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    {{-- <h3 style="color:white; text-shadow: 2px 2px 5px green;">Happy Anniversary</h3>
                    <p>September 10</p> --}}
                </div>
            </div> -->
           
        
            <!--<div class="carousel-item">
                <img src="{{ asset('img/Dashboard/announcement.png') }}" class="d-block w-100 carousel-image" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    {{-- <h3 style="color:white; text-shadow: 2px 2px 5px green;">Happy Birthday</h3> --}}
                    
                </div>
            </div> -->
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
               @if(Auth::check() && Auth::user()->email === 'jeffrey.salagubang.js@sanden-rs.com')
            <option value="Holiday">Holiday</option>
              @endif
                <option value="Training & Seminars">Training & Seminars</option>
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
        <button type="submit" class="btn btn-primary">Save Event</button>
      </div>
    </form>
  </div>
</div>
<!-- View Event Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Agenda Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th scope="row">Title</th>
              <td><span id="eventTitle"></span></td>
            </tr>
             <tr>
              <th scope="row">Agenda Type</th>
              <td><span id="eventAgenda"></span></td>
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
              <td><span id="eventDescription"></span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


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
// Hintayin na matapos ma-load ang buong HTML document bago patakbuhin ang script
document.addEventListener('DOMContentLoaded', function () {

    // Kumuha ng data mula sa API endpoint para sa mga holidays
    fetch('{{ route('api.holidays') }}')
        .then(response => response.json()) // I-convert ang response sa JSON format
        .then(holidays => {
            const table = document.getElementById('holidayTable'); // Hanapin ang table element sa HTML
            table.innerHTML = ''; // Linisin muna ang laman ng table

            // Kung walang holiday na nakuha
            if (holidays.length === 0) {
                const row = document.createElement('tr'); // Gumawa ng bagong row
                row.innerHTML = '<td colspan="6" class="text-center">No holidays found</td>'; // Mensahe kung walang holiday
                table.appendChild(row); // Idagdag ang row sa table
            } else {
                // I-loop ang holidays kada 6 na entry bawat row
                for (let i = 0; i < holidays.length; i += 6) {
                    const row = document.createElement('tr'); // Gumawa ng bagong row
                    const group = holidays.slice(i, i + 6); // Kumuha ng grupo ng hanggang 6 holidays

                    group.forEach(holiday => {
                        const td = document.createElement('td'); // Gumawa ng bagong cell

                        const date = new Date(holiday.start); // I-convert ang petsa mula sa API
                        const options = { month: 'short', day: '2-digit' }; // Format ng petsa
                        const formattedDate = date.toLocaleDateString('en-US', options).replace(',', '').replace(' ', '-'); // Format ng petsa: MMM-DD

                        // Ipasok ang title at formatted date sa cell
                        td.innerHTML = `<p class="quote-text">${holiday.title} - ${formattedDate}</p>`;
                        row.appendChild(td); // Idagdag ang cell sa row
                    });

                    table.appendChild(row); // Idagdag ang row sa table
                }
            }
        })
        .catch(error => {
            // Kung may error sa pagkuha ng data
            console.error('Error fetching holidays:', error);
            const row = document.createElement('tr'); // Gumawa ng bagong row para sa error
            row.innerHTML = '<td colspan="6">Error loading holidays</td>'; // Mensahe ng error
            document.getElementById('holidayTable').appendChild(row); // Idagdag ang row sa table
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
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: '{{ route("calendar.events") }}', // Fetch events from backend
    dayMaxEventRows: true, // ✅ Enable "+ more" link for month view
    dayMaxEvents: true,    // ✅ Alternative for different views
    eventClick: function(info) {
        info.jsEvent.preventDefault();

        fetch('{{ route("calendar.show", ":id") }}'.replace(':id', info.event.id))
            .then(response => response.json())
            .then(data => {
                document.getElementById('eventTitle').textContent = data.title;
                document.getElementById('eventStart').textContent = data.start;
                      document.getElementById('eventAgenda').textContent = data.agenda;
                document.getElementById('eventEnd').textContent = data.end ? data.end : 'N/A';
                document.getElementById('eventDescription').textContent = data.description ? data.description : 'No description';
                document.getElementById('deleteEventBtn').setAttribute('data-id', info.event.id);
                $('#viewEventModal').modal('show');
            });
    }
});


    calendar.render();

    // Handle form submit with fetch

document.getElementById('eventForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    // Confirm before submitting
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
            fetch('{{ route("calendar.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    $('#eventModal').modal('hide'); // Close modal
                    document.getElementById('eventForm').reset();
                    calendar.refetchEvents();
Swal.fire({
    title: 'Created!',
    text: 'Your event has been added.',
    icon: 'success',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    location.reload();
});
                } else {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
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



@endsection
@endif