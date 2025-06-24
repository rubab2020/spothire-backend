@extends('layouts.default-admin')
@section('content')
	<div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Tables</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Users
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" data-order='[[ 0, "desc" ]]' data-page-length='25'>
            <thead>
              <tr>
                <th>No</th>
                <th>User Type</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            
            <tbody>
              @php($i = 1)
              @foreach($users as $user)
              	<tr>
                  <td>{{ $i++ }}</td>
									<td>
                    @if($user->user_type)
                      <span style="color: #00a8ff">Company</span>
                    @else
                      <span style="color: #6c5ce7">Individual</span>
                    @endif
                  </td>
									<td>
                    <img 
                      src="{{ asset(App\User::getPhotoPath($user->id).$user->picture) }}"
                      style="width: auto; height: 30px;"
                    >
                    {{ $user->name }}
                  </td>
									<td>{{ $user->email }}</td>
									<td>{{ $user->phone }}</td>
									<td>{{ $user->gender }}</td>
									<td>
                    @if($user->valid) 
                      <span style="color:#27ae60;">Active</span> 
                    @else 
                      <span style="color:#c0392b;">Inactive</span>
                    @endif
                  </td>
									<td>
										@if($user->valid)
											<button 
                        type="button" 
                        class="btn btn-danger" 
                        data-toggle="modal" 
                        data-target="#confirmDisablingUser" 
                        onclick="setDeactiveDataToModal({{ $user->id }}, 'disable')"
                      >
												Disable
											</button>
										@else
											<button type="button" class="btn btn-success">Enable</button>
										@endif
									</td>
              	</tr>
							@endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
    </div>
  </div>

  <!-- The Modal -->
  <div class="modal" id="confirmDisablingUser">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Confirm</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          Are you sure about deactiveting this user?
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success">Done</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function setDeactiveDataToModal(userId, type){
      console.log(userId, type);
      
      // var data = {
      // 'user_id': ,
      // 'is_active': 
      // };
      // $.ajax({
      //     url: "/update-winner",
      //     type:'POST',
      //     data: data,
      //     success: function(resp) {
      //       console.log(resp);
      //       // if($.isEmptyObject(data.error)){
      //       //     swal('Confirmed!',data.success); # or swal('Confirmed!','success')
      //       // }else{
      //       //     swal('error!',data.error); # or swal('error!','Errorrrrr')
      //       // }
      //     }
      // });
    }
  </script>
@endsection