@extends('layout.authentication.authLayout')

@section('title')
    Forgot Password
@endsection

@section('authContent')
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>CR</b>SYSTEM</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                 <form action="/clinic/reset-password" method="post" id="reset-password">
                    @csrf
                    @method('post')
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @if (session('sent'))
                        <p class="text-success" style="margin-top: -15px;">Email sent succesfully</p>
                    @endif
                    @if ($errors->any())
                    <div class="error">
                        <p class="text-danger" style="margin-top: -15px;">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                     @endif
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                @if (session('sent'))
                                Resend
                                @else
                                Request new password
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="{{url('/login')}}">Already have an account</a>
                </p>
                <p class="mb-0">
                    <a href="{{url('/verify')}}" class="text-center">Register a new membership</a>
                </p>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        $('#reset-password').submit(function(event){
            
            $(this).find('button').prop('disabled', true).text('Please wait...')
        })
    </script>
@endsection
