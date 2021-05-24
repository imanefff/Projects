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


<section class="container">
     <div class="row">
                <div class="signin-container col-md-6 col-sm-6 col-xs-6">

  <div class="modal-dialog" role="document" >
    <!--Content-->
    <div class="modal-content form-elegant">
      <!--Header-->
      <div class="modal-header text-center">
        <h3 class="modal-title w-100 dark-grey-text font-weight-bold my-3" id="myModalLabel"><strong>Register</strong></h3>
      </div>
      <!--Body-->
      <div class="modal-body mx-4">
        <form method="POST" action="{{ route('register') }}">
                        @csrf
                      
        <!--Body-->

        <div class="md-form mb-5">
          <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
          @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif

    
          <label data-error="wrong" data-success="right" for="name" >Your name</label>
        </div>



        <div class="md-form mb-5">
          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
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

                    <p style="font-size:13px;">(at least 8 characters)</p>
                  


          @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

        </div>

        <div class="md-form pb-3">
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
          <label for="password-confirm" data-error="wrong" data-success="right" for="Form-pass1">Confirm Password</label>

        </div>

        {!! NoCaptcha::renderJs('EN') !!}
                                    {!! NoCaptcha::display()!!}

                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="text-danger">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif

<br><br>

        <div class="text-center mb-3">
          <button type="submit" class="btn blue-gradient btn-block btn-rounded z-depth-1a">Register</button>
        </div>

        <div>
              <p style="font-size:14px;" align="center">By joining, I agree to WinLitDeals’s <a href="https://sites.google.com/view/winlitdeals/home" target="__blank" style="color: #0757DB;">Privacy Policy</a> and <a style="color: #0757DB;" href="https://sites.google.com/view/winlitdealsterms/home" target="__blank">Terms of Use</a>.</p>
            </div>
        <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign up
          with:</p>

        <div class="row my-3 d-flex justify-content-center">
          <!--Facebook-->
          <a href="{{ url('/redirect') }}" title="Sign in with Facebook" type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></a>
       
          <!--Google +-->
          <a href="{{ url('/redirectGoogle') }}" title="Sign in with Google" type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></a>
        </div>
        </form>
      </div>

      
      <!--Footer-->
      <div class="modal-footer mx-5 pt-3 mb-1">
        <p style="font-size: 16px;" class="font-small grey-text d-flex justify-content-end">Already a member? <a href="{{ route('login') }}" class="blue-text ml-1">
            Log In</a></p>
      </div>
    </div>
    <!--/.Content-->
  </div>


  </div>


<!-- End Modal -->

 <!-- Image -->

 <div class="col-md-6 col-sm-6 col-xs-6" >
    <br>

     
            <div style="margin: 37px ";>
                <div>
                            <h2 style="
    background: linear-gradient(to right, #30CFD0 0%, #330867 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-family: 'Poppins', sans-serif;
   
                            ">Unlock Member Benefits Today</h2><br>
        <div class="text-dark font-weight-bold ">
            <p> &nbsp; As a member, you can:</p>
            

                        <ul>

                              <li><span  style="line-height: 2.0;">Save offers and deals to access at any time.</span></li>
                              
                              <li><span style="line-height: 2.0;" >Get weekly Coupons.</span></li>
                              <li><span style="line-height: 2.0;"> Access exclusive deals, earn rewards and stay up to date on savings.</span></li>
                              <li><span style="line-height: 2.0;">Join the conversation by sharing your favorites deals on Social Media.</span></li>
                              <li><span>Create alerts and receive notifications when we post deals you want.</span></li>
                                </ul>
                    </div>
                        </div>
                    
                </div>
                </div>




</section>


<br>
<br>
<br>


@endsection
