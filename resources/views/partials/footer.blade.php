
@if(Auth::check())
<!-- Trigger Button -->


<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="changePasswordForm" method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          @if(session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
          @endif
          @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          @error('current_password')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror

          <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Enter your current password" required>
          </div>
          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your new password" required>
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control"placeholder="Confirm your new password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Change</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop normal form submit

    let form = this;
    let formData = new FormData(form);

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change your password?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
					  'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred.'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong: ' + error
                });
            });
        }
    });
});
</script>
<!-- partial:../../partials/_footer.html -->
			<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
				<p class="text-muted text-center text-md-left">Sanden Intranet Apps v.1.0.0.0- MIS </p>
				<p class="text-muted text-center text-md-left mb-0 d-none d-md-block">05 Makiling Drive, Carmelray Industrial Park II,
Km. 54 National Highway, Calamba 4027 Laguna</p>
			</footer>@endif
			<!-- partial -->
	
		</div>
	</div>
<!-- core:js -->
<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
<script src="{{ asset('assets/vendors/owl.carousel/owl.carousel.min.js') }}"></script>
<!-- endinject -->
<script src="{{ asset('assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/js/wizard.js') }}"></script>
<!-- PDF.js -->
<script src="{{ asset('assets/js/pdf.min.js') }}"></script>
<script src="{{ asset('assets/js/pdf.worker.min.js') }}"></script>

<!-- plugin js for this page -->
<script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<!-- end plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<!-- endinject -->

<!-- custom js for this page -->
<script src="{{ asset('assets/js/data-table.js') }}"></script>
	<!-- <script src="{{ asset('assets/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
	<script src="{{ asset('assets/vendors/fullcalendar/main.min.js') }}"></script>
	<script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
	<!-- end custom js for this page -->
</body>
</html>