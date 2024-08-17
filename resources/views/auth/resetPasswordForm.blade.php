@extends('layout.authentication.authLayout')

@section('title')
    Reset Password
@endsection

@section('authContent')
    <div class="register-box">
        <div class="register-logo">
            <a href="../../index2.html"><b>CR</b>SYSTEM</a>
        </div>


        <div class="card">
            <div class="card-body register-card-body">
            <p class="login-box-msg">Reset Password</p>
                <form action="{{route('reset-password')}}" method="post" id="resetPassword">
                    @csrf
                    @method('post')

                    <input type="hidden"name="email" value="{{$email}}">
                   
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" id="confirmPassword">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

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
                            <button type="submit" class="btn btn-primary btn-block">Reset</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        $('#resetPassword').submit(function(event){
            $(this).find('button').prop('disabled', true).text("Please wait...");
        })
    </script>
@endsection
