@extends('Front.layout.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::get('error'))
							<div class="alert alert-danger">
								{{Session::get('error')}}
							</div>
						@endif
        <div class="login-form">
            <form action="{{route('account.authenticate')}}" id="loginForm" name="loginForm" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" class="form-control  @error('email')is-invalid @enderror" name="email" placeholder="Email"  value="{{old('email')}}">
                    @error('email')
                    <p class="invalid-feedback">{{$message}}</p>
                @enderror
                </div>

                <div class="form-group">
                    <input type="password" class="form-control @error('password')is-invalid @enderror" name="password" placeholder="Password" >
                    @error('password')
                    <p class="invalid-feedback">{{$message}}</p>
                @enderror
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
            </form>
            <div class="text-center small">Don't have an account? <a href="{{route('account.register')}}">Sign up</a></div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
    <script type="text/javascript">
        // $("#loginForm").submit(function(event){
        //     event.preventDefault();
        //     $.ajax({
        //         url:'',
        //         type:'post',
        //         data:$(this).serializeArray(),
        //         dataType:'json',
        //         success:function(response){
        //             var $message=response.success;
        //             console.log($message);
        //         },
        //     });
        // });
    </script>
@endsection
