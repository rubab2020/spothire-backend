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
                <th>No</th>
                <th>Title</th>
                <th>Employer</th>
                <th>A/c Type</th>
                <th>Advert Deadline</th>
                <th>Is Deleted?</th>
                <th>Action</th>
              </tr>
            </thead>
            
            <tbody>
              @foreach($jobs as $index => $job)
              	<tr>
                  <td>{{ $index+1 }}</td>
                  <td>{{ $job->job_title }}</td>
                  <td> 
                    <img src="
                      {{ 
                        asset(
                          App\User::getPhoto(
                            $job->user->id,
                            $job->user->picture_sm
                          )
                        ) 
                      }}"
                      width="40"
                      height="40" 
                    > 
                    {{ $job->user->name }}
                  </td>
                  <td>
                    {{ 
                      $job->user->user_type == $userTypes['individual'] 
                        ? 'Individual' 
                        : 'Company' 
                    }}
                  </td>
                  <td>{{ $job->advert_deadline }}</td>
                  <td>{{ $job->deleted_at == null ? 'No' : 'Yes'  }}</td>
                  <td>
                    <select 
                      class="form-control review-status" 
                      name="review_status" 
                      data-jobid="{{$job->id}}"
                    >
                      @foreach($reviewStatuses as $key=>$status)
                        <option 
                          value="{{ $key }}"
                          {{ 
                            ($key == $job->review_status) 
                              ? 'selected' : '' 
                          }}
                        >
                          {{ $status }}
                        </option>
                      @endforeach
                    </select>
                  </td>
              	</tr>
							@endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content') }
    });

    let prevSelectOptionVal;
    let reviewStatus;
    let jobId;
    $(document.body).on('change', '.review-status', function() {
      prevSelectOptionVal = $(this).val();
      reviewStatus = $(this).val();
      jobId = $(this).attr('data-jobid');
    }).on('change', function(){
      if (confirm('Are you sure you want to change review status?')) {
        // $(this).blur(); // Firefox fix for option change
        
        $.ajax({
          type: 'POST',
          url: '/admin/job/change-review-status',
          data: {review_status: reviewStatus, job_id: jobId},
          success: function (data) {
            $.toast({
              title: data.message,
              type: data.status,
              delay: 5000
            });

          },
          error: function (data) {
            $.toast({
              title: data.message,
              type: data.status,
              delay: 5000
            });
          }
        });
      }
      else{
        $(this).val(prevSelectOptionVal);
      }
    });
  });
</script>
@endsection