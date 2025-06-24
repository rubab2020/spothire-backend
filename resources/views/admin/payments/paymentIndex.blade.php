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
        <i class="fa fa-table"></i> Data Table Example
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Job</th>
                <th>Payment Type</th>
                <th>Bkash Number</th>
                <th>Bkash Transaction ID</th>
                <th>Payment Status</th>
                <th>Action</th>
              </tr>
            </thead>
            
            <tbody>
              @foreach($payments as $payment)
              	<tr>
                  <td>
                    @foreach($jobs as $key=>$jobTitle)
                      @if($key == $payment->job_id)
                        {{$jobTitle}}
                        <?php break; ?>
                      @endIf
                    @endforeach
                  </td>
                  <td>{{$payment->payment_type}}</td>
                  <td>{{$payment->bkash_number}}</td>
                  <td>{{$payment->bkash_transaction_id}}</td>
									<td>{{$payment->status}}</td>
                  <td>
                    @if($payment->status == 'pending')
                      {!! Form::open(['url' => '/admin/payment/complete', 'method'=>'POST']) !!}
                        {{ Form::hidden('id', $payment->id) }}
                      <button type="submit" class="btn btn success">Complete</button>
                      {!! Form::close() !!}
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
@endsection

<!-- The Modal -->
<!-- <div class="modal" id="confirmDisablingUser">
  <div class="modal-dialog">
    <div class="modal-content"> -->
    
      <!-- Modal Header -->
      <!-- <div class="modal-header">
        <h4 class="modal-title">Confirm</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div> -->
      
      <!-- Modal body -->
      <!-- <div class="modal-body">
        Are you sure?
      </div> -->
      
      <!-- Modal footer -->
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-success">Done</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      
    </div>
  </div>
</div> -->

<script type="text/javascript">
	// function setDeactiveDataToModal(userId, type){
	// 	console.log(userId, type);
	// }

	// var data = {
	// 	'user_id': ,
	// 	'is_active': 
	// };
	// $.ajax({
	//     url: "/update-winner",
	//     type:'POST',
	//     data: data,
	//     success: function(resp) {
	//     	console.log(resp);
 //        // if($.isEmptyObject(data.error)){
 //        //     swal('Confirmed!',data.success); # or swal('Confirmed!','success')
 //        // }else{
 //        //     swal('error!',data.error); # or swal('error!','Errorrrrr')
 //        // }
	//     }
	// });
</script>