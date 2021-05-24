

@extends('layouts.master')

@section('content')

<div class="container">
  <!-- Card -->
  <!-- Grid -->
  <div class="row">

<div class="col-lg-10 mb-4" style="margin-top: 100px;">
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
            <form action="newsletterUpdate"  method="Post">
                {{ csrf_field() }}
                <!-- First row -->
                <div class="row">
                    <!-- First column -->

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
                        <p class="text-left"><b>Sifting through too many deals you don't want? Get only the deals you like, from type of offers </b></p>
                    <section class="section-preview">
           
                        <div class="form-check text-left" >

                            <div class="row" >
                           
                                    <ul style="width: 20px; padding-left: 0px;">
                                         <input type="checkbox" class="form-check-input" name="top" value="top" id="top"><label class="form-check-label " style="
                                                width: 300px;
                                                padding-left: 0px;
                                                left: 0px;
                                                padding-left: 30px;
                                                right: 150px;
                                                height: 15px;"  for="top">top offers</label>	 
                                         <input type="checkbox" class="form-check-input" name="hot" value="hot" id="hot"><label class="form-check-label"style="
                                                width: 300px;
                                                padding-left: 0px;
                                                left: 0px;
                                                padding-left: 30px;
                                                right: 150px;
                                                height: 15px;" for="hot" >hot offers</label>
                                    </ul>
                       
                            </div>
                        </div>
           

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
$val="";


if ($('#top').is(":checked"))
{
	$val=$('#top').val();

}else if($('#hot').is(":checked"))
{
	 $val=$('#hot').val();

}

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




  $("form input:radio").change(function() {


    if ($(this).val() == "0") {

      $(".md-form").css('opacity', '.2');

    }
    // Else Enable radio buttons.
    else {
      $(".md-form").css('opacity', '1');
    }
   });

//});





// Redirect to profile page
function imageClick(url) {
    window.location = url;
}


</script>
@endsection

