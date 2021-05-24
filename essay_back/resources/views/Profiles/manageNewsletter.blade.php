@extends('layouts.master')

@section('content')

<div class="container">
  <!-- Card -->
  <!-- Grid -->
  <div class="row">
    <div class="col-4">
<!-- profile-bar -->
  <div class="card ">
    <!-- Card content -->
    <div class="card-body card-body-cascade text-center" >

      <img onclick="imageClick('/profile')" src="storage/users/{{ $user->photo }}" class="z-depth-1 mb-3 mx-auto" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">

       <h3 style="padding-top: 45px;">{{ $user->name }}'s Profile</h3>

      <p class="text-muted"><small></small></p>

    </div>
    <!-- Card content -->

  </div>
  <!-- Card -->
    <div class="card card-cascade narrower">

      <a class="btn btn-lighten-3 mdb-color text-white" href="{{ url('favorite') }}" role="button" class="text-white "> Favorites</a>

      <a class="btn btn-lighten-3 mdb-color text-white" href="{{ url('/profile') }}" role="button" > Edit Profile</a>

      <a class="btn btn-lighten-3 mdb-color text-white" href="{{url('/changePassword')}}" >Change Password</a>

      <a class="text-white btn btn-lighten-3 mdb-color" href="{{url('/manageNewsletter')}}" role="button" > Manage Newsletters </a>

    </div>
<!-- profile-bar -->

    </div>
            <div class="col-lg-8 mb-4">
              <!-- Card -->
              <div class="card card-cascade narrower">
                <!-- Card image -->
                <div class="view view-cascade gradient-card-header mdb-color lighten-3">
                  <h5 class="mb-0 font-weight-bold">Manage Newsletter</h5>
                </div>
                <!-- Card image -->
                <!-- Card content -->
                <div class="card-body card-body-cascade text-center">
                <!-- Edit Form -->
                <form action="/newsletterUpdate"   method="POST">
                  {{ csrf_field() }}
                  <!-- First row -->
                  <div class="row">
                    <!-- First column -->
                    <div class=" col-9 ">
                      <!-- Default unchecked -->
                      <p class="text-left"><b> Would you like to receive a daily email with the best offers from WinLitDeals ?</b></p>
                       </div>
                       <div class=" col-3 " >

                       <div class="form-check">
                         <input type="radio" class="form-check-input" id="defaultUnchecked" name="defaultExampleRadios" value="1" @if(  $user->recieve_news  == 1  ) checked @endif >
                         <label class="form-check-label" for="defaultUnchecked">Yes</label>
                       </div>

                       <!-- Default checked -->
                       <div class="form-check" style="right: 1.8px;">

                         <input type="radio" class="form-check-input" id="defaultChecked" name="defaultExampleRadios" value="0"  @if(  $user->recieve_news  == 0  ) checked @endif >
                         <label class="form-check-label" for="defaultChecked">No</label>
                       </div>

                    </div>
                    </div>

                


              

                <!-- Second row -->
                <div class="row">
                  <div class="col">
                  
                      <div class="md-form">
                         <label for="appt"><b>Choose what time to receive the deals:  </b></label>
                        <input style="margin-top: 3px;" type="time" id="appt" name="selected_time" value="{{$user->selected_time}}" >

                       
                       

                      </div>


                  </div>
                </div>

                <br>
               <!-- Second row -->

               <!-- third row -->

               <div class="row ">
                 <div class="col " >
                                <p class="text-left"><b>Sifting through too many deals you don't want? Get only the deals you like, from one or many specific categories</b></p>
                     <section class="section-preview">
                     

                    @php $categoriesUserSelected  =  explode("//" , $user->prefered_news ); @endphp
                    @foreach($categories as $category)

                     <div class="form-check text-left" >

                                <div class="row" >
                                  <div class="col " >
                                  <ul style="
                                          width: 20px;
                                          padding-left: 0px;  ">
                                      <input type="checkbox" class="form-check-input" id="{{  $category->name }}" value="{{ $category->name }}" name="prefered_news[]" @if (in_array( $category->name , $categoriesUserSelected )) checked  @endif >
                                     <label class="form-check-label"  for="{{  $category->name }}" style="
                                                width: 300px;
                                                padding-left: 0px;
                                                left: 0px;
                                                padding-left: 30px;
                                                right: 150px;
                                                height: 15px;
                                                                  ">{{  $category->name }}</label> </ul>
                                </div>

                              </div>

                    </div>
                    @endforeach

                 </section>

               </div>
             </div>
              <!-- third row -->

              <!-- fourth row -->
              <div class="row">
                <div class="col">
                  <div class="col-md-12 text-center my-4">
                    <input  type="submit" value="Save Change" class="btn btn-info btn-rounded">
                  </div>
                </div>
              </div>
             <!-- fourth row -->

                 </form>
                </div>
              </div>

            </div>

</div>
</div>
@endsection
@section('js')

<script type="text/javascript">

$.ajaxSetup({
headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

// Material Select Initialization
$(document).ready(function() {
$('.mdb-select').materialSelect();
destroy: true

});

$('#input_starttime').pickatime({
// Light or Dark theme
darktheme: true
});


$(document).ready(function() {
  $("form input:radio").change(function() {


  
  
    if ($(this).val() == "0") {
     
      $(".md-form").css('opacity', '.2');
      

    }
    // Else Enable radio buttons.
    else {
      
      $(".md-form").css('opacity', '1');
    }

     });

});





// Redirect to profile page
function imageClick(url) {
    window.location = url;
}


</script>
@endsection
