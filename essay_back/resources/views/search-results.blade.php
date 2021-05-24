@extends('layouts.master')

@section('extra-css')
<style type="text/css">
  
.imgProduct{
   width:100%;
   height: 15vw;
   object-fit: cover;
}


@media only screen and (max-width: 900px) {
 .imgProduct{
   
   height: 35vw;

  }
}


</style>


@endsection


@section('content')

  

    <div class="search-results-container container">
        <h1>Search Results</h1>
        <p class="search-results-count">{{ $creatives->total() }} result(s) for '{!! request()->input('query') !!}'</p>

        @if ($creatives->total() > 0)
        
          <div class="row mb-3">




              

                 @foreach($creatives as $creative)

                 <div class="container" style="width:350px">

                 <!--Card-->
               <!--  height:60px !important;display: block;line-height: 60px; -->
<div class="card card-cascade card-ecommerce wider forClick" data-id="{{$creative->id}}" >

  <!--Card image-->
  <div class="view view-cascade overlay">
    <img class="card-img-top imgProduct" src="storage\{{ $creative->creative_image }}" alt="Image" >
    <a target="_blank" href="{{ $creative->offer->offer_url }}">
      <div class="mask rgba-white-slight"></div>
    </a>
  </div>
  <!--/.Card image-->

  <!--Card content-->
  <div class="card-body card-body-cascade text-center">
    <!--Category & Title-->
    <!-- <h5>Shoes</h5> -->
    <h4 class="card-title"><strong><a style="color:#FF5733; height:60px; !important;display:block;" target="_blank" href="{{ $creative->offer->offer_url }}" >{{ $creative->offer->name }}</a></strong></h4>

    <!--Rating-->
    <ul class="rating">
      <li><i class="fas fa-star"></i></li>
      <li><i class="fas fa-star"></i></li>
      <li><i class="fas fa-star"></i></li>
      <li><i class="fas fa-star"></i></li>
      <li><i class="fas fa-star-half-alt"></i></li>
    </ul>

    <!--Description-->


    <p class="card-text">{{ Str::limit($creative->offer->description,100) }}</p>

    <!--Card footer-->
    <div class="card-footer">
      <span class="float-left">
        <a target="_blank" href="{{ $creative->offer->offer_url }}"  type="button" class="btn btn-danger btn-rounded">Buy Now</a>
      </span>
      <span class="float-right mt-2 pt-1" >
        <a title="View details" href='{{url("/shop/{$creative->id}")}}' ><i style="color:#0FBCB9;margin-right: 6px;" class="fas fa-info-circle"></i>
         
        </a>
      

       @guest
         <a  onclick="toastr.info('To add Deals to your favorite list, You need to login first');"><i class="far fa-heart"></i></a>

      @else
         <a class="active favorite-toggle" data-id="{{$creative->id}}" title="Added to Favorite">
          <i class=" {{ !Auth::user()->favorite_creatives->where('pivot.creative_id',$creative->id)->count()  == 0 ? 'fas' : 'far'}} fa-heart"></i>
        </a>

     @endguest
      </span>
    </div>

  </div>
  <!--/.Card content-->

</div>
<!--/.Card-->

                  
                <br><br><br>

      </div>
       
     

        @endforeach
             

          </div>


        {{ $creatives->appends(request()->input())->links() }}
        @endif
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

   console.log(  )
   $.post( '/favorite' , { id : id } , (data) => {
      if(data.toggle){
        $(this).find('i').removeClass("far").addClass( "fas" )
        toastr.success('successfully added to your favorite list');}
      else{
       $(this).find('i').removeClass( "fas" ).addClass( "far" )
       toastr.warning('successfully removed from your favorite list');}


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
</script>

@endsection