@extends('layout.authentication.authLayout')

@section('title')
    Link expired
@endsection

@section('authContent')
<div class="container text-center">
    <div class="expired-content">
        <h1 class="display-4">Oops! Page Expired</h1>
        <p class="lead">It looks like this page has expired. Please try refreshing the page or return to the <a href="/clinic/login">Home</a>.</p>
        <img src="{{ url('/photos/expired.png') }}" alt="Page Expired" class="img-fluid mt-4">
    </div>
</div>

<style>
    .container{
        width: 100vw;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .expired-content h1 {
        font-size: 3rem;
        color: #ff4d4d;
    }
    .expired-content p {
        font-size: 1.25rem;
        color: #333;
    }
    .expired-content a {
        color: #007bff;
        text-decoration: none;
    }
    .expired-content a:hover {
        text-decoration: underline;
    }
</style>
@endsection
