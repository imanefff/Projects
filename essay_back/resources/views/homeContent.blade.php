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


<div class="site-blocks-cover inner-page" data-aos="fade">
    <div class="container">
        <div class="row">
            <div class="col-md-6 ml-auto order-md-2 align-self-start">
                <div class="site-block-cover-content">
                    <h2 class="sub-title">#Best Summer Deals 2019</h2>
                    <h1>Exclusive Online Offers</h1>
                    <p><a href="#all_deals" class="btn btn-black rounded-0">Shop Now</a></p>
                </div>
            </div>
            <div class="col-md-6 order-1 align-self-end">
                <img src="{{ asset('frontEnd') }}/images/model_2.png" alt="Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<div class="site-section" id="hot_deals">
    <div class="container">
        <div class="row">
            <div class="title-section text-center mb-5 col-12">
                <h2 class="text-uppercase" style="top: 50px;">Hot Deals&nbsp;<i style="color:#ee4266;" class="fab fa-hotjar"></i></h2>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-12 block-3">
                <div class="nonloop-block-3 owl-carousel">
                @foreach($listHotOffer as $creative)
                <div class="item forClick" data-id="{{$creative->id}}">
                    <div class="item-entry">
                        <a href="{{ $creative->offer->offer_url }}"  target="_blank" class="product-item md-height d-block">
                        <img src="storage\{{ $creative->creative_image }}" alt="Image" class="img-fluid" >
                        </a>
                        <h2 style="text-align: center;height:60px; !important;display:block;" class="item-title"><a href="{{ $creative->offer->offer_url }}" target="_blank">{{ $creative->offer->name }}&nbsp;<span  class="badge badge-pill danger-color">NEW</span></a></h2>
                        <center>
                            <div class="star-rating">
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                            </div>
                        </center>
                        <center><a target="_blank" href="{{ $creative->offer->offer_url }}"  type="button" class="btn btn-danger btn-rounded ">Buy Now</a></center>

                    </div>
                </div>

                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<div class="site-section" id="all_deals">
    <div class="container">
        <div class="title-section mb-5">
            <h2 class="text-uppercase"><span class="d-block">Today's</span> Best Deals</h2>
        </div>
        <div class="row mb-5">
            <div class="col-md-12 order-1">
                <div class="row mb-3">
                @foreach($creatives as $creative)
                    <div class="container" style="width:350px">
                    <!--Card-->
                        <!--  height:60px !important;display: block;line-height: 60px; -->
                        <div  class="card card-cascade card-ecommerce widery forClick" data-id="{{$creative->id}}">
                            <!--Card image-->
                            <div class="view view-cascade overla"  >
                                <img class="card-img-top imgProduct" src="storage\{{ $creative->creative_image }}" alt="Image">
                                <a target="_blank" href="{{ $creative->offer->offer_url }}">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <!--/.Card image-->
                            <!--Card content-->
                            <div class="card-body card-body-cascade text-center">
                                <!--Category & Title-->
                                <h4 class="card-title"><strong><a style=" height:60px; !important;display:block;" target="_blank" href="{{ $creative->offer->offer_url }}" >{{ $creative->offer->name }}</a></strong></h4>
                                <br>
                                <!--Description-->
                                <p class="card-text">{{ Str::limit($creative->offer->description,100) }}<a style="color: blue;" href='{{url("/shop/{$creative->id}")}}' >View Details</a></p>
                                <!--Card footer-->
                                <div class="card-footer">
                                    <span class="float-left">
                                        <a target="_blank" href="{{ $creative->offer->offer_url }}"  type="button" class="btn btn-danger btn-rounded">Buy Now</a>
                                    </span>
                                    <span class="float-right mt-2 pt-1" >
                                        <a title="View details" href='{{url("/shop/{$creative->id}")}}' ><i style="color:#0FBCB9;margin-right: 6px;" class="fas fa-info-circle"></i>
                                        </a>
                                        @guest
                                        <a  onclick="toastr.info('To add Deals to your favorite list, You need to login first.');"><i style="color:red;" class="far fa-heart"></i></a>
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
                <div class="row">
                    <div class="col-md-12 text-center">
                    {{ $creatives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section" id="top_deals">
    <div class="container">
        <div class="row">
            <div class="title-section text-center mb-5 col-12">
                <h2 class="text-uppercase">Top EXCLUSIVES Deals&nbsp;<i style="color:#ee4266;" class="fas fa-fire"></i></h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 block-3">
                <div class="nonloop-block-3 owl-carousel">
                @foreach($listTopOffer as $creative)
                    <div class="item forClick" data-id="{{$creative->id}}">
                        <div class="item-entry">
                            <a href="{{ $creative->offer->offer_url }}"  target="_blank" class="product-item md-height d-block">
                                <img src="storage\{{ $creative->creative_image }}" alt="Image" class="img-fluid" >
                            </a>
                            <h2 style="text-align: center;" class="item-title"><a href="{{ $creative->offer->offer_url }}" target="_blank">{{ $creative->offer->name }}</a></h2>
                            <div class="star-rating">
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                                <span class="icon-star2 text-warning"></span>
                            </div>

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
    @if (count($errors) > 0)
    <script>
        $( document ).ready(function() {
            $('#newsletter').modal('show');
        });
    </script>
    @endif

    <!-- Script for Newsletter Modal PopUp and RegisterPopUP - Show Only once per session  -->
    <script type="text/javascript">
    $(function() {
        if ((typeof Storage != "undefined")) {
            if (!localStorage.getItem("done") ) {
                setTimeout(function() {
                    $('#newsletter').modal('show');
                }, 2000);
                setTimeout(function() {
                    $('#registerpopup').modal('show');
                }, 20000);
            }
            localStorage.setItem("done", true);
        }
    });
     // Incriment the value of Clicks when a user click in an offer

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.forClick' ).click(function(e) {
        let id=$(this).data("id");
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

        $.post('/favorite' , { id : id } , (data) => {
            if(data.toggle){
                $(this).find('i').removeClass("far").addClass( "fas" )
                toastr.success('Deal successfully added to your favorite list');
            }
            else{
                $(this).find('i').removeClass( "fas" ).addClass( "far" )
                toastr.warning('Deal removed from your favorite list');
            }
        });
    });
    </script>

@endsection
