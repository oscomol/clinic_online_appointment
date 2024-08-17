@extends('layout.authentication.authLayout')

@section('title')
    Account setup
@endsection

@section('authContent')
    <div class="register-box">
        <div class="register-logo">
            <a href="../../index2.html"><b>CR</b>SYSTEM</a>
        </div>


        <div class="card">
            <div class="card-body register-card-body">
            <p class="login-box-msg">Register a new membership</p>
                <form action="{{route('register')}}" method="post" id="register">
                    @csrf
                    @method('post')
                    <input type="hidden" name="email" value="{{$email}}">
                    <input type="hidden" name="userType" value="{{$userType}}">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name" name="name" id="name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    
                   
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
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>

                    </div>

                </form>
                <a href="{{url('/login')}}" class="text-center">I already have a membership</a>
            </div>

        </div>
    </div>
@endsection

@section('script')

    <script type="module">
        $(function(){
           $('.register').submit(function(){
            $(this).find('button').prop('disabled', true).text('Please wait....');
           });
        })
    </script>
    
@endsection