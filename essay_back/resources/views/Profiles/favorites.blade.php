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
      <!-- <div class="row flex-center">
        <button class="btn btn-info btn-rounded btn-sm">Upload New Photo</button><br>
        <button class="btn btn-danger btn-rounded btn-sm">Delete</button>
      </div> -->
    </div>
    <!-- Card content -->

  </div>
  <!-- Card -->
    <div class="card card-cascade narrower">

      <!-- <div class="btn btn-lighten-3 mdb-color"> -->
         <a href="{{ url('favorite') }}" role="button" class="text-white btn btn-lighten-3 mdb-color"> Favorites </a>
   <!--    </div> -->

     <!--  <div class="btn btn-lighten-3 mdb-color"> -->
         <a href="{{ url('/profile') }}" role="button" class="text-white btn btn-lighten-3 mdb-color "> Edit Profile </a>
      <!-- </div> -->

      <!-- <div class="btn btn-lighten-3 mdb-color"> -->
          <a href="{{url('/changePassword')}}" class="text-white btn btn-lighten-3 mdb-color">Change Password</a>
     <!--  </div> -->

    <!--   <div class="btn btn-lighten-3 mdb-color"> -->
         <a  href="{{url('/manageNewsletter')}}" role="button" class="text-white btn btn-lighten-3 mdb-color "> Manage Newsletters </a>
      <!-- </div> -->

</div>
<!-- profile-bar -->

</div>
<div class="col">

        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-cascade narrower">
                  <div class="view view-cascade gradient-card-header mdb-color lighten-3">
                    <h5 class="mb-0 font-weight-bold">Favorites</h5>
                  </div>
                    <div class="header">

                      <!-- <h2> -->
                        <div class="row" style="margin-left: 0px;
                                               margin-right: 0px;
                                               border-top-width: 20px;
                                               margin-top: 35px; " >



                                    @foreach($creatives as $key=>$creative)
                                    <div class ="col-md-6 ">
                                     <div class="container" style="width:350px">

                                    <!--Card-->
                   <div class="card card-cascade card-ecommerce wider forClick" data-id="{{$creative->id}}">

                     <!--Card image-->
                     <div class="view view-cascade overlay">
                       <img class="card-img-top" src="storage\{{ $creative->creative_image }}" alt="Image" style=" width:100%;height: 15vw;
                       object-fit: cover;">
                       <a target="_blank" href="{{ $creative->offer->offer_url }}">
                         <div class="mask rgba-white-slight"></div>
                       </a>
                     </div>
                     <!--/.Card image-->

                     <!--Card content-->
                     <div class="card-body card-body-cascade text-center" >
                       <!--Category & Title-->
                       <!-- <h5>Shoes</h5> -->
                        <h4 class="card-title"><strong><a style=" height:60px; !important;display:block;" target="_blank" href="{{ $creative->offer->offer_url }}" >{{ $creative->offer->name }}</a></strong></h4>

  <br>

                       <!--Rating-->


                       <!--Description-->


                       <p class="card-text">{{ Str::limit($creative->offer->description,100) }}</p>

                       <!--Card footer-->
                       <div class="card-footer">
                         <span class="float-left">
                           <a target="_blank" href="{{ $creative->offer->offer_url }}"  type="button" class="btn btn-danger btn-rounded">Buy Now </a>
                         </span>
                         <span class="float-right mt-2 pt-1" >

               <a title="View details" href='{{url("/shop/{$creative->id}")}}' ><i style="color:#0FBCB9;margin-right: 6px;" class="fas fa-info-circle"></i>
         
        </a>

       @guest

                           @else
                           <a onclick="toastr.success('successfully removed form your favorite list');" class="active favorite-toggle" data-id="{{$creative->id}}" title="Added to Favorite">
                            <i class=" {{ !Auth::user()->favorite_creatives->where('pivot.creative_id',$creative->id)->count()  == 0 ? 'fas' : 'far'}} fa-heart"></i>
                          </a>
                           @endguest

                           
                         </span>
                       </div>

                     </div>
                                     </div>

                                   <br><br><br>

                                 </div>
                         </div>



                                    @endforeach

                                  </div>
                        </div>
                    </div>
                </div>

        </div>
        <!-- #END# Exportable Table -->
    </div>
  </div>

@endsection

@section('js')

<script>

toastr.options.showMethod = 'slideDown';
toastr.options.hideMethod = 'slideUp';
toastr.options.closeMethod = 'slideUp';


$.ajaxSetup({
headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

$('.favorite-toggle').click( function () {
   var id = this.dataset.id
  /// console.log( id )

   $.post( '/favorite' , { id : id } , (data) => {

     if(!data.toggle)
        $(this).parents( ".card.card-cascade.card-ecommerce.wider" ).fadeOut( "slow" )

    })
});

    $( '.forClick' ).click(function(e) {
            let id=$(this).data("id");
            $.post('/shop2',{id:id},function(data){
              console.log(data);
            })
             $.post('/userclick',{id:id},function(data){
              console.log(data);
            })
          


});


// $.post('' , {} , (data) => {})
// Redirect to profile page
function imageClick(url) {
    window.location = url;
}

</script>

@endsection