@extends('layouts.master')


@section('content')
<br>

<div class="bg-light py-3" style="background-color: #FBFBFB!important; ">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{ url('/')}}">Home</a> <span class="mx-2 mb-0">/</span> <a href="{{ url('shop')}}">Shop</a> <span class="mx-2 mb-0">/</span>
           <strong class="text-black">{{ $creative->offer->name }}</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">

        <!-- Grid row -->
  <div class="row">

    <!-- Grid column -->
    <div class="col-lg-5">

      <!-- Featured image -->
      <div class="view overlay rounded z-depth-2 mb-lg-0 mb-4">
        <img  class="img-fluid " src="..\storage\{{ $creative->creative_image }}" alt="Sample image">
        <a class="forClick" data-id="{{$creative->id}}" target="_blank" href="{{ $creative->offer->offer_url }}">
          <div class="mask rgba-white-slight"></div>
        </a>
      </div>

    </div>
    <!-- Grid column -->

    <!-- Grid column -->
    <div class="col-lg-7">

      <!-- Category -->
      <a target="_blank" href="{{ $creative->offer->offer_url }}"class="blue-text">
        <h3 class="font-weight-bold mb-3">{{ $creative->offer->name }}</h3>
      </a>
      <!-- Post title -->
      <h6 class="font-weight-bold mb-3"><i style="color:red;" class="fas fa-fire"></i><strong>&nbsp;{{ $creative->offer->Category }}</strong></h6>
      <!-- Excerpt -->
      <p>{{ $creative->offer->description }}</p>
      <!-- Post data -->

      <!-- Read more button -->
      <a data-id="{{$creative->id}}" target="_blank" href="{{ $creative->offer->offer_url }}" class="btn btn-danger btn-rounded forClick">See Deal</a>
      <span class="float-right mt-2 pt-1" >
        <i class="dropup">


        <a data-toggle="dropdown" data-placement="top" title="Share"><i class="fas fa-share-alt mr-3"></i>

            <ul class="dropdown-menu" style=" min-width:0px;" >
                <li>
                  <a data-original-title="Twitter" title="Share on Twitter" rel="tooltip"  href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn btn-tw" data-placement="left">
                <i class="fab fa-twitter"></i>
              </a>
              </li>
              <li>
                <a data-original-title="Facebook" rel="tooltip"  title="Share on Facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn btn-fb" data-placement="left">
                <i class="fab fa-facebook"></i>
              </a>
              </li>



                    </ul>

        </a>
      </i>




       @guest
         <a  onclick="toastr.info('To add Deals to your favorite list, You need to login first');"><i style="color:red;" class="far fa-heart"></i></a>

      @else
         <a class="active favorite-toggle" data-id="{{$creative->id}}" title="Added to Favorite">
          <i style="color:red;" class=" {{ !Auth::user()->favorite_creatives->where('pivot.creative_id',$creative->id)->count()  == 0 ? 'fas' : 'far'}} fa-heart"></i>
        </a>

     @endguest

      </span>
    </div>

  </div>
  <!--/.Card content-->

</div>
<!--/.Card-->




      </div>
      <br>




    <div class="site-section block-3 site-blocks-2">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>TODAY'S TOP DEALS&nbsp;<i style="color:red;" class="fas fa-fire-alt"></i></h2>

          </div>
        </div>

            <div class="row">
          <div class="col-md-12 block-3">
            <div class="nonloop-block-3 owl-carousel">
               @foreach($creatives as $creative)
              <div class="item forClick" data-id="{{$creative->id}}">
                <div class="item-entry" >
                  <a href="{{ $creative->offer->offer_url }}"  target="_blank" class="product-item md-height d-block">
                    <img src="..\storage\{{ $creative->creative_image }}" alt="Image" class="img-fluid" >
                  </a>
                  <h2 style="text-align: center;" class="item-title"><a href="{{ $creative->offer->offer_url }}" target="_blank">{{ $creative->offer->name }}</a></h2>
                  <center><div class="star-rating">
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                  </div></center>
                  <center><a target="_blank" href="{{ $creative->offer->offer_url }}"  type="button" class="btn btn-danger btn-rounded">Buy Now</a></center>
                </div>
              </div>

               @endforeach


            </div>
          </div>
        </div>

      </div>
    </div>


@endsection

@section('js')

<script>

  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
  $( '.forClick' ).click(function(e) {
            let id=$(this).data("id");
            console.log(id)
            $.post('/shop2',{id:id},function(data){
                console.log(data);
            })
            $.post('/userclick',{id:id},function(data){
                console.log(data);
            })

});

  toastr.options.showMethod = 'slideDown';
  toastr.options.hideMethod = 'slideUp';
  toastr.options.closeMethod = 'slideUp';

  $('.favorite-toggle').click( function () {
   var id = this.dataset.id

   $.post( '/favorite' , { id : id } , (data) => {
      if(data.toggle){
        $(this).find('i').removeClass("far").addClass( "fas" )
        toastr.success('Deal successfully added to your favorite list');}
      else{
       $(this).find('i').removeClass( "fas" ).addClass( "far" )
       toastr.warning('Deal removed from your favorite list');}


   })
});

var popupMeta = {
    width: 400,
    height: 400
}
$(document).on('click', '.social-share', function(event){
    event.preventDefault();

    var vPosition = Math.floor(($(window).width() - popupMeta.width) / 2),
        hPosition = Math.floor(($(window).height() - popupMeta.height) / 2);

    var url = $(this).attr('href');
    var popup = window.open(url, 'Social Share',
        'width='+popupMeta.width+',height='+popupMeta.height+
        ',left='+vpPsition+',top='+hPosition+
        ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

    if (popup) {
        popup.focus();
        return false;
    }
});
</script>

@endsection
