@extends('layouts.master')



@section('extra-css')
    <style>
        
        .form-elegant .font-small {
    font-size: 0.8rem; }

.form-elegant .z-depth-1a {
    -webkit-box-shadow: 0 2px 5px 0 rgba(55, 161, 255, 0.26), 0 4px 12px 0 rgba(121, 155, 254, 0.25);
    box-shadow: 0 2px 5px 0 rgba(55, 161, 255, 0.26), 0 4px 12px 0 rgba(121, 155, 254, 0.25); }

.form-elegant .z-depth-1-half,
.form-elegant .btn:hover {
    -webkit-box-shadow: 0 5px 11px 0 rgba(85, 182, 255, 0.28), 0 4px 15px 0 rgba(36, 133, 255, 0.15);
    box-shadow: 0 5px 11px 0 rgba(85, 182, 255, 0.28), 0 4px 15px 0 rgba(36, 133, 255, 0.15); }

.form-elegant .modal-header {
    border-bottom: none; }

.modal-dialog .form-elegant .btn .fab {
    color: #2196f3!important; }

.form-elegant .modal-body, .form-elegant .modal-footer {
    font-weight: 400; }

    </style>
@endsection


@section('content')

<!-- Modal -->

<section class="container">
     <div class="row" style="margin-top: 30px" >
                <div class="signin-container col-md-6 col-sm-6 col-xs-6">

  <div class="modal-dialog" role="document" >
    <!--Content-->
    <div class="modal-content form-elegant">
      <!--Header-->
      <div class="modal-header text-center">
        <h3 class="modal-title w-100 dark-grey-text font-weight-bold my-3" id="myModalLabel"><strong>Sign in</strong></h3>
      </div>
      <!--Body-->
      <div class="modal-body mx-4">
        <form method="POST" action="{{ route('login') }}">
                        @csrf
        <!--Body-->
        <div class="md-form mb-5">
          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
          @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

    
          <label data-error="wrong" data-success="right" for="email">Your email</label>
        </div>

        <div class="md-form pb-3">
          <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
          <label for="password" data-error="wrong" data-success="right" for="Form-pass1">Your password</label>


           @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
           @endif


           @if (Route::has('password.request'))

          <p class="font-small blue-text d-flex justify-content-end"><a href="{{ route('password.request') }}"class="blue-text ml-1">
              Forgot Password?</a></p>

           @endif


        </div>

        <div class="text-center mb-3">
          <button type="submit" class="btn blue-gradient btn-block btn-rounded z-depth-1a">Sign in</button>
        </div>
        <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign in
          with:</p>

        <div class="row my-3 d-flex justify-content-center">
          <!--Facebook-->
          <a href="{{url('/login/facebook/callback')}}" title="Sign in with Facebook" type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></a>
          <!--Google +-->
          <a href="{{ url('/redirectGoogle') }}" title="Sign in with Google" type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></a>
        </div>
        </form>
      </div>

      
      <!--Footer-->
      <div class="modal-footer mx-5 pt-3 mb-1">
        <p style="font-size: 16.5px;" class="font-small grey-text d-flex justify-content-end">Not a member? <a href="{{ route('register') }}" class="blue-text ml-1">
            Sign Up</a></p>
      </div>
    </div>
    <!--/.Content-->
  </div>
  </div>


<!-- End Modal -->

 <!-- Image -->

 <div class="col-md-6 col-sm-6 col-xs-6" >
  <p style="text-align: center;font-size: 16.5px;"><b>Don't have an account? </b><a href="{{ route('register') }}" class="blue-text ml-1">
            Register here</a></p>
                    <img src="{{ asset('frontEnd') }}/images/login.png" style="margin: 35px auto 0;max-width: 100%;margin-bottom: 50px;display: block;">
                </div>
                </div>

 </section>


























<br>
<br>
<br>






@endsection
