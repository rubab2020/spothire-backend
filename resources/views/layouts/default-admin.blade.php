<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Bootstrap core CSS-->
  <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
  <!-- bootstrap toaster style from Script47/Toast github repo -->
  <style type="text/css">
    #toast-container{position:sticky;z-index:1055;top:0}#toast-wrapper{position:absolute;top:0;right:0;margin:5px}#toast-container>#toast-wrapper>.toast{min-width:150px}#toast-container>#toast-wrapper>.toast>.toast-header strong{padding-right:20px}
  </style>
  @yield('styles')
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  @include('partials.admin.nav')

  <div class="content-wrapper">
    @yield('content')

    @include('partials.admin.footer')

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset('vendor/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin.min.js')}}"></script>
    <!-- Custom scripts for this page-->
    <script src="{{asset('js/sb-admin-datatables.min.js')}}"></script>
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- bootstrap toaster style from Script47/Toast github repo -->
    <script type="text/javascript">
      (function(b){b.toast=function(a,h,g,l,k){b("#toast-container").length||(b("body").prepend('<div id="toast-container" aria-live="polite" aria-atomic="true"></div>'),b("#toast-container").append('<div id="toast-wrapper"></div>'));var c="",d="",e="text-muted",f="",m="object"===typeof a?a.title||"":a||"Notice!";h="object"===typeof a?a.subtitle||"":h||"";g="object"===typeof a?a.content||"":g||"";k="object"===typeof a?a.delay||3E3:k||3E3;switch("object"===typeof a?a.type||"":l||"info"){case "info":c="bg-info";
        f=e=d="text-white";break;case "success":c="bg-success";f=e=d="text-white";break;case "warning":case "warn":c="bg-warning";f=e=d="text-white";break;case "error":case "danger":c="bg-danger",f=e=d="text-white"}a='<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="'+k+'">'+('<div class="toast-header '+c+" "+d+'">')+('<strong class="mr-auto">'+m+"</strong>");a+='<small class="'+e+'">'+h+"</small>";a+='<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">';
        a+='<span aria-hidden="true" class="'+f+'">&times;</span>';a+="</button>";a+="</div>";""!==g&&(a+='<div class="toast-body">',a+=g,a+="</div>");a+="</div>";b("#toast-wrapper").append(a);b("#toast-wrapper .toast:last").toast("show")}})(jQuery);
    </script>

    <script type="text/javascript">
      $('.table').dataTable( {
          "order": [[ 0, 'desc' ],]
      } );
    </script>
    @yield('scripts')
  </div>
</body>

</html>