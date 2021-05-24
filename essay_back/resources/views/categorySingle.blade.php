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




    <div class="bg-light py-3" style="background-color: #FBFBFB!important; ">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ url('/')}}">Home</a> <span class="mx-2 mb-0">/</span> <a href="{{ url('shop')}}">Shop</a> <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">{{ $category->name }}</strong>
                </div>
        </div>
        </div>
    </div>


    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-9 order-1">
                    <div class="row align">
                        <div class="title-section mb-5">
                        <h2 id="category-name" class="text-uppercase">{{ $category->name }}</h2>
                        </div>
                    </div>
                    <div class="row mb-5">
                        @foreach( $offers as $offer )
                            @foreach( $offer->creatives as $creative )

                                    <div class="container" style="width:350px">

                    <div  class="card card-cascade card-ecommerce widery forClick" data-id="{{$creative->id}}">
                        <!--Card image-->
                        <div class="view view-cascade overla"  >
                            <img class="card-img-top imgProduct " src="..\..\storage\{{ $creative->creative_image }}" alt="Image">
                            <a target="_blank" href="{{ $creative->offer->offer_url }}">
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>
                    <!--/.Card image-->
                    <!--Card content-->
                    <div class="card-body card-body-cascade text-center">
                        <!--Category & Title-->
                        <!-- <h5>Shoes</h5> -->
                        <h4 class="card-title"><strong><a style=" height:60px; !important;display:block;" target="_blank" href="{{ $creative->offer->offer_url }}" >{{ $offer->name }}</a></strong></h4>
                    <br>


    <p class="card-text">{{ Str::limit($offer->description,100) }}<a style="color: blue;" href='{{url("/shop/{$creative->id}")}}' >View Details</a></p>

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
                @endforeach

    </div>


        <div class="row">
            <!-- pagination -->
        </div>

        </div>

    <div class="col-md-3 order-2 mb-5 mb-md-0">
        <div class="border p-4 rounded mb-4" style=" margin-top: 90px;">
            <h3 class="mb-3 h6 text-uppercase text-black d-block"><b>Categories</b></h3>
            <ul class="list-unstyled mb-0 " >
                @foreach(\App\Category::where('is_active', 1)->get() as $category)
                <li class="mb-1"><a href='{{url("../shop/category/{$category->id} ")}}' class="d-flex"><span>{{ $category->name }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
        </div>

    </div>
    </div>



@endsection


@section('js')

<script>

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
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

</script>

@endsection
