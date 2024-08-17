@extends('layout.authentication.authLayout')

@section('title')
    CRSystem | Login
@endsection

@section('authContent')
<div class="login-box">

    <div class="login-logo">
        <a href="../../index2.html"><b>CR</b>SYSTEM</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">Sign in to continue</p>

               @if (session('error'))
               <div class="alert alert-danger pb-0">
                    <p>Hmmm, credentials not valid!</p>
                </div>
               @endif
               @if (session('updatedMsg'))
               <div class="alert alert-success pb-0">
                    <p>{{session('updatedMsg')}}</p>
                </div>
               @endif

            <form action="{{route('login')}}" method="post">
                @csrf
                @method('post')
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{$email}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" @if ($email)
                                checked
                            @endif>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn btn-sm">Sign In</button>
                    </div>

                </div>
            </form>

            <p class="mb-1">
                <a href="{{url('/forgot-password')}}">I forgot my password</a>
            </p>
            <p class="mb-0">
                <a href="{{url('/verify')}}" class="text-center">Register a new membership</a>
            </p>
        </div>

    </div>
</div>    
@endsection

