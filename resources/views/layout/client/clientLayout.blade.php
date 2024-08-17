<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css'])

</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ url('/photos/logo.png') }}" alt="AdminLTELogo" height="60"
                width="60"><br>
                <p class="animation__wobble">Clinic Reservation System</p>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>


            @include('layout.account')
          

         

        </nav>

        @include('layout.client.clientSidebar')

        @include('layout.modal')
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <section class="content">
                @yield('clientContent')
            </section>
        </div>
        <aside class="control-sidebar control-sidebar-dark">

        </aside>
    </div>

    @vite(['resources/js/app.js'])

    @yield('script')


</body>

</html>

<script type="module">
    $(function(){

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        $('#accountUpdateForm').submit(function(event) {
            event.preventDefault();
            const url = $(this).attr('action');
            const email = $(this).find('input[type="email"]').val();
            const id = $(this).find('#id').val();
            const userType = $(this).find('#userType').val();
            $(this).find('.getLink').prop('disabled', true).text('Sending email');
            $.ajax({
                    url: url,
                    type: 'PUT',
                    data: { email: email, id, userType },
                    dataType: 'json',
                    success: function(data) {
                        $('.msg').removeClass('text-danger d-none').addClass('text-success').text("Email sent succesfully");
                        $('.getLink').prop('disabled', false).text('Resend');
                    },
                    error: function(response) { 
                        const errMsg = response.responseJSON.error || "Something went wrong!";
                        $('.msg').removeClass('text-success d-none').addClass('text-danger').text(errMsg);
                        $('.getLink').prop('disabled', false).text('Get link');
                    }
                });
        });
    })
</script>
