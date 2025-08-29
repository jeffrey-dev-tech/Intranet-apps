@extends('layouts.app')
@section('title', 'Features Assign')
@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <h2>Manage Features</h2>

            {{-- ✅ Success and Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- ✅ Add Feature Form --}}
            <h4 class="mt-4">Add New Feature</h4>
            <form action="{{ route('special.access.addFeature') }}" method="POST" class="mb-4">
                @csrf
                <div class="form-group">
                    <label>Feature Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., features.update_sftp_sql" required>
                </div>

                <div class="form-group mt-2">
                    <label>Description:</label>
                    <input type="text" name="description" class="form-control" placeholder="Short description"required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Add Feature</button>
            </form>

            <hr>

            {{-- ✅ Features Table --}}
     <h4 class="mt-4">Existing Features</h4>
     <div class="table-responsive">
<table class="table table-bordered table-hover" id="dataTableExample">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th width="120">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($features as $feature)
            <tr>
                <td>{{ $feature->name }}</td>
                <td>{{ $feature->description ?? 'No description' }}</td>
                <td>
                    <form id="delete-form-{{ $feature->id }}" >
                        
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $feature->id }})">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center">No features found</td></tr>
        @endforelse
    </tbody>
</table>
</div>
            <hr>

            {{-- ✅ Assign Feature to User --}}
            <h4 class="mt-4">Assign Feature to User</h4>
            <form id="Assign_Form"class="mb-4">
                <div class="row">
                   <div class="col-md-5">
    <label>Select User:</label>
    <select id="select-user" name="user_id" class="form-control" required>
        <option value="" disabled selected>Choose a user</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
        @endforeach
    </select>
</div>

                    <div class="col-md-5">
                        <label>Select Feature:</label>
                        <select name="feature_id" id="select-features" class="form-control" required>
                            <option value="" disabled selected>Choose a feature</option>
                            @foreach($features as $feature)
                                <option value="{{ $feature->id }}">{{ $feature->name }} - {{ $feature->description ?? 'No description' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                       <button id="assignBtn" class="btn btn-success">Assign</button>
                    </div>
                </div>
            </form>

            <hr>

            {{-- ✅ Current User Feature Assignments --}}
            <h4 class="mt-4">Current User Feature Assignments</h4>
            <div class="table-responsive">
            <table class="table table-bordered table-hover" id="FeatureAssignmentsTbl">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Feature(s)</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @if($user->features->count())
                            @foreach($user->features as $feature)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $feature->name }} ({{ $feature->description ?? 'No description' }})</td>
                                    <td>
                                        <form action="{{ route('special.access.removeFeature', [$user->id, $feature->id]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Remove this feature from user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                           
                        @endif
                    @empty
                        <tr><td colspan="3" class="text-center">No users found</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('assignBtn').addEventListener('click', async (e) => {
    e.preventDefault(); // ✅ Stops page refresh

    const result = await Swal.fire({
      title: 'Proceed?',
      text: 'Do you want to continue?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    });

    if (result.isConfirmed) {
      Swal.fire({
        title: 'Assigning...',
        text: 'Please wait while we assign the features.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
      });

      try {
        let form = document.getElementById('Assign_Form');
        let formData = new FormData(form);

        const response = await fetch("{{ route('special.access.assign') }}", {
          method: 'POST',
          body: formData, // ✅ Send FormData directly
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        let data;
        try {
          data = await response.json();
        } catch {
          data = { message: 'Unexpected response format.' };
        }

        if (response.status === 403) {
          Swal.fire({
            icon: 'error',
            title: 'Unauthorized',
            text: data.message || 'You are not allowed to perform this action.',
            confirmButtonText: 'OK'
          });
          return;
        }

        if (!response.ok) {
          throw new Error(data.error || 'Server error: ' + response.status);
        }

        await Swal.fire({
          icon: 'success',
          title: 'Assigned!',
          text: data.message || 'Feature assigned successfully.',
          confirmButtonText: 'OK'
        });

        location.reload();

      } catch (error) {
        console.error('Assign error:', error);
        Swal.fire('Error', 'An error occurred during assignment.', 'error');
      }
    }
  });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Delete this feature? It will delete existing users with this feature.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the feature.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            // Perform AJAX request to delete
            fetch(`/special-access/features/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Feature deleted successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload page after success
                    });
                } else {
                    Swal.fire('Error!','Something went wrong.', 'error');
                }
            })
            .catch(() => {
                Swal.close();
                Swal.fire('Error!', 'Server error. Please try again later.', 'error');
            });
        }
    });
}
</script>
<script>
    // Initialize Choices.js on the select element
    const userSelect = new Choices('#select-user', {
        searchEnabled: true,      // Enable search
        itemSelectText: '',       // Remove "Press to select" text
        shouldSort: false,        // Keep original order
        placeholder: true,        // Enable placeholder
        searchPlaceholderValue: 'Search user...', // Search box placeholder
    });
</script>
<script>
    // Initialize Choices.js on the select element
    const featureSelect = new Choices('#select-features', {
        searchEnabled: true,      // Enable search
        itemSelectText: '',       // Remove "Press to select" text
        shouldSort: false,        // Keep original order
        placeholder: true,        // Enable placeholder
        searchPlaceholderValue: 'Search features...', // Search box placeholder
    });
</script>
<script>
$(document).ready(function() {
    $('#FeatureAssignmentsTbl').DataTable({
        pageLength: 5,        // Show 10 rows per page
        ordering: true,        // Enable column sorting
        searching: true,       // Enable search box
        lengthChange: true,    // Show "Show entries" dropdown
    });
});
</script>
@endsection
