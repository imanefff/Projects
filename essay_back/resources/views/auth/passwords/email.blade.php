@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-cascade narrower">
                <!-- <div class="card-header">{{ __('Reset Password') }}</div> -->
                <div class="view view-cascade gradient-card-header mdb-color lighten-3">
                  <h5 class="mb-0 font-weight-bold"> Change Password</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row">
                            <div class="col">
                              <div class="md-form mb-0 form-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <label for="email" >{{ __('E-Mail Address') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center my-4">
                                <button type="submit" class="btn btn-info btn-rounded">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>

@endsection
