@extends('layout.authentication.authLayout')

@section('title')
    Account Update
@endsection

@section('authContent')
    <div class="register-box">
        <div class="register-logo">
            <a href="../../index2.html"><b>CR</b>SYSTEM</a>
        </div>


        <div class="card">
            <div class="card-body register-card-body">
            <p class="login-box-msg">Update account</p>
                <form action="{{route('saveUpdate')}}" method="post" id="saveUpdate">
                    @csrf
                    @method('put')
                    <input type="hidden" name="email" value="{{$email}}">
                    <input type="hidden" name="id" value="{{$user->id}}">

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Your new email" name="email" id="email" value="{{$email}}" readonly>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-email"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name" name="name" id="name" value="{{$user->name}}">
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

                    @if (session('success'))
                        <p class="text-success" style="margin-top: -15px;">
                            {{session('success')}}
                        </p>
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

                            @if (session('success'))
                            <p>
                                <a href="{{url('/login')}}">Login in now</a>
                            </p>
                            @else
                            <button type="submit" class="btn btn-primary btn-block updateBtn">Update</button>
                            @endif
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection

@section('script')

    <script type="module">
        $(function(){
          $('#saveUpdate').submit(function(){
            $(this).find('.updateBtn').prop('disabled', true);
          });
        })
    </script>
    
@endsection