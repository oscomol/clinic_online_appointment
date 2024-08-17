@extends('layout.authentication.authLayout')

@section('title')
    Register
@endsection

@section('authContent')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>CR</b>SYSTEM</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                @if (session('message'))
                <p class="login-box-msg">Did not recieve an email ? Check email and resend.</p>
                @else
                <p class="login-box-msg">To register, enter a valid email address.</p>
                @endif
                
                <form action="{{route('adminRegister')}}" method="post" id="verify">
                    @csrf
                    @method('post')
                    <input type="hidden" value="1" name="userType">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <p class="customStyle text-danger"></p>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                @if (session('message'))
                                    Resend
                                @else
                                    Send me an email
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="{{url('/login')}}">Already have an account</a>
                </p>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        $(function(){
            $('#verify').submit(function(event){
                event.preventDefault();
                $(this).find('button').text('Please wait...').prop('disabled', true);
                var url = $(this).attr('action');
                $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('.customStyle').removeClass('text-danger').addClass('text-success').css('margin-top', '-15px').text("Email sent succesfully");
                    $('#verify')[0].reset();
                    $('#verify').find('button').text('Get a link').prop('disabled', false);
                },
                error: function(err) {
                    const status = err.responseJSON.error;
                    $('.customStyle').removeClass('text-success').addClass('text-danger').css('margin-top', '-15px').text(status);
                    $('#verify')[0].reset();
                    $('#verify').find('button').text('Get a link').prop('disabled', false);
                }
            });
            });
        })
    </script>
@endsection