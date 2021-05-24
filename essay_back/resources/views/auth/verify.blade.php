@extends('layouts.master')

@section('content')
<div class="container" style="margin-top: 40px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card -->
<div class="card card-cascade wider reverse">

  <!-- Card image -->
  <div class="view view-cascade overlay">
    <img class="card-img-top" src="{{ asset('frontEnd') }}/images/offers-bg.jpeg" alt="Card image cap">
    <a href="#!">
      <div class="mask rgba-white-slight"></div>
    </a>
  </div>

  <!-- Card content -->
  <div class="card-body card-body-cascade text-center">

    <!-- Title -->
    <h4 class="card-title text-info" style="font-size: 28px;"><strong>{{ __('Verify Your Email Address') }}</strong></h4>
    
    
    <!-- Text -->
    <p class="card-text">@if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}"><b>{{ __('click here to request another') }}</b></a>.
    </p>



  </div>

</div>
<!-- Card -->
          
        </div>
    </div>
</div>
@endsection


  <!-- <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
 -->