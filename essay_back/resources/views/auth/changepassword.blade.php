@extends('layouts.master')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-4">
  <!-- profile-bar -->
  <div class="card ">


    <!-- Card content -->
    <div class="card-body card-body-cascade text-center">

      <img onclick="imageClick('/profile')" src="storage/users/{{ $user->photo }}" class="z-depth-1 mb-3 mx-auto" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">

       <h3 style="padding-top: 45px;">{{ $user->name }}'s Profile</h3>

      <p class="text-muted"><small></small></p>
      <!-- <div class="row flex-center">
        <button class="btn btn-info btn-rounded btn-sm">Upload New Photo</button><br>
        <button class="btn btn-danger btn-rounded btn-sm">Delete</button>
      </div> -->
    </div>
    <!-- Card content -->

  </div>
  <!-- Card -->
  <div class="card card-cascade narrower">

   <!--  <div class="btn btn-lighten-3 mdb-color"> -->
       <a href="{{ url('favorite') }}" role="button" class="text-white btn btn-lighten-3 mdb-color"> Favorites </a>
   <!--  </div> -->

    <!-- <div class="btn btn-lighten-3 mdb-color"> -->
       <a href="{{ url('/profile') }}" role="button" class="text-white btn btn-lighten-3 mdb-color"> Edit Profile </a>
  <!--   </div> -->

    <!-- <div class="btn btn-lighten-3 mdb-color"> -->
        <a href="{{url('/changePassword')}}" class="text-white btn btn-lighten-3 mdb-color">Change Password</a>
    <!-- </div> -->

    <!-- <div class="btn btn-lighten-3 mdb-color"> -->
       <a href="{{url('/manageNewsletter')}}" role="button" class="text-white btn btn-lighten-3 mdb-color"> Manage Newsletters </a>
    <!-- </div> -->
</div>
<!-- profile-bar -->
</div>
<div class="col-8">

    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card card-cascade narrower">
              <div class="view view-cascade gradient-card-header mdb-color lighten-3">
                <h5 class="mb-0 font-weight-bold"> Change Password</h5>
              </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
                        {{ csrf_field() }}
                         

                  <div class="row">

                    <div class="col">

                        <div class="md-form mb-0 form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">

                        
                           <input id="current-password" type="password" class="form-control" name="current-password" required>
                                @if ($errors->has('current-password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('current-password') }}</strong>
                                </span>
                                @endif
                          <label for="current-password" data-error="wrong" >Current Password</label>

                        </div>

                         </div>
                         </div>


                         <div class="row">

                    <div class="col">

                        <div class="md-form mb-0 form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">

                           <input id="new-password" type="password" class="form-control" name="new-password" required>

                                @if ($errors->has('new-password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('new-password') }}</strong>
                                </span>
                                @endif
                            <label for="new-password" >New Password</label>
                        </div>
                      </div>
                    </div>

                    <div class="row">

                    <div class="col">

                        <div class="md-form mb-0 form-group">
                          <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>


                            <label for="new-password-confirm">Confirm Password</label>

                        </div>

                      </div>
                    </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center my-4">
                                <input  type="submit" value="Change Password" class="btn btn-info btn-rounded">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
</div>


@endsection
@section('js')
<script type="text/javascript">
// Redirect to profile page
function imageClick(url) {
    window.location = url;
}

</script>
