@extends('layouts.default-admin')
@section('content')
<style>
  .card-counter{
    box-shadow: 2px 2px 10px #DADADA;
    margin: 5px;
    padding: 20px 10px;
    background-color: #fff;
    height: 100px;
    border-radius: 5px;
    transition: .3s linear all;
  }

  .card-counter:hover{
    box-shadow: 4px 4px 20px #DADADA;
    transition: .3s linear all;
  }

  .card-counter.primary{
    background-color: #007bff;
    color: #FFF;
  }

  .card-counter.danger{
    background-color: #ef5350;
    color: #FFF;
  }  

  .card-counter.success{
    background-color: #66bb6a;
    color: #FFF;
  }  

  .card-counter.info{
    background-color: #26c6da;
    color: #FFF;
  }  

  .card-counter i{
    font-size: 5em;
    opacity: 0.2;
  }

  .card-counter .count-numbers{
    position: absolute;
    right: 35px;
    top: 20px;
    font-size: 32px;
    display: block;
  }

  .card-counter .count-name{
    position: absolute;
    right: 35px;
    top: 65px;
    font-style: italic;
    text-transform: capitalize;
    opacity: 0.5;
    display: block;
    font-size: 18px;
  }
</style>
	<div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <!-- Example DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Dashboard
      </div>
      <div class="card-body">
        Welcome Back, Admin!
        <br>
        <h5>Today</h5>
        <div class="row">
          <div class="col-md-3">
            <div class="card-counter primary">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $totalJobsToday }}</span>
              <span class="count-name">Total Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter success">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $approvedJobsToday }}</span>
              <span class="count-name">Approved Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter info">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $pendingJobsToday }}</span>
              <span class="count-name">Pending Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter danger">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $rejectedJobsToday }}</span>
              <span class="count-name">Rejected Jobs</span>
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card-counter primary">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $totalUsersToday }}</span>
              <span class="count-name">Total Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter success">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $verifiedUsersToday }}</span>
              <span class="count-name">Verified Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter info ">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $unverifiedUsersToday }}</span>
              <span class="count-name">Unverified Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter danger">
              <i class="fa fa-users"></i>
              <span class="count-numbers">0</span>
              <span class="count-name">Banned Users</span>
            </div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-3">
            <div class="card-counter primary">
              <i class="fa fa-code-fork"></i>
              <span class="count-numbers">12</span>
              <span class="count-name">Flowz</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter danger">
              <i class="fa fa-ticket"></i>
              <span class="count-numbers">599</span>
              <span class="count-name">Instances</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter success">
              <i class="fa fa-database"></i>
              <span class="count-numbers">6875</span>
              <span class="count-name">Data</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter info">
              <i class="fa fa-users"></i>
              <span class="count-numbers">35</span>
              <span class="count-name">Users</span>
            </div>
          </div>
        </div> -->

        <h5>All</h5>
        <div class="row">
          <div class="col-md-3">
            <div class="card-counter primary">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $totalJobs }}</span>
              <span class="count-name">Total Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter success">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $approvedJobs }}</span>
              <span class="count-name">Approved Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter info">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $pendingJobs }}</span>
              <span class="count-name">Pending Jobs</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter danger">
              <i class="fa fa-database"></i>
              <span class="count-numbers">{{ $rejectedJobs }}</span>
              <span class="count-name">Rejected Jobs</span>
            </div>
          </div>
          
          <div class="col-md-3">
            <div class="card-counter primary">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $totalUsers }}</span>
              <span class="count-name">Total Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter success">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $verifiedUsers }}</span>
              <span class="count-name">Verified Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter info ">
              <i class="fa fa-users"></i>
              <span class="count-numbers">{{ $unverifiedUsers }}</span>
              <span class="count-name">Unverified Users</span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card-counter danger">
              <i class="fa fa-users"></i>
              <span class="count-numbers">0</span>
              <span class="count-name">Banned Users</span>
            </div>
          </div>
        </div>
    </div>
  </div>
  </div>
@endsection