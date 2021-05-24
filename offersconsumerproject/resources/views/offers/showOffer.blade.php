
@extends('layouts.temp')

@section('css')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link href="{{ asset('css/offcanvas.css') }}" rel="stylesheet">
<style>

.contain {
    margin-top: 50px;
}

p.media-body.pb-3.mb-0.small.lh-125.border-bottom.border-gray{
    color : black;
    font-size: 15px;
}


hr.ps {
    border: none;
    border-top: 3px double #d0d7d8;
    color: #272c30;
    overflow: visible;
    text-align: center;
    height: 5px;
}
hr.ps:after {
    background: #fff;
    content: '§';
    padding: 0 4px;
    position: relative;
    top: -13px;
}
#tgl {
    float: right;
    margin: 10px;
}

.delCreative{
    float : right;
}

/* .toggle-off.btn {
    background-color: #6c757d;
    color: #fff;
} */
#pips {
    padding: 8px 10px;
    margin: 10px 0 0 0 ;
    color: #343a40;
    border-radius: 3px;
    text-decoration: underline;
    text-decoration-color: #0fc7b6;
}


</style>
@endsection

@section('content')
    <div class="contain">

        <div class="heads clearfix">

            @if($offer->status == 1) <span class="float-right " id='pips'>Offer Already Sent </span>
            @else  <button type="button" id="tgl" class="btn btn-secondary" data-status="{{$offer->status}}" data-id="{{$offer->id}}" > Send </button> @endif
            <a class="btn btn-warning" style='float:right; margin: 10px;' href="/offer/update/{{$offer->id}}">Update</a>
            <a href="/offer/create" class="btn btn-primary" style="float:right; margin: 10px;">Add Offer</a>

            <h1> Offer :</h1>

        </div>

        <hr>

        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm" style="background-color: #ccc;">

            <div class="lh-100">
                <h6 class="mb-0 lh-100" style='padding: 5px; color : #111;'> &nbsp; Name : {{substr($offer->name,0,150)}} ...</h6>
                <h6 class="mb-0 lh-100" style='padding: 5px; color : #111;'> &nbsp; Sid : {{$offer->sid}}  </h6>
                <h6 class="mb-0 lh-100" style='padding: 5px; color : #111;'> &nbsp; Advertiser : {{$offer->advertiser->name}}</h6>
                <h6 class="mb-0 lh-100" style='padding: 5px; color : #111;'> &nbsp; Advertiser Affiliate Id : {{$offer->advertiser->affiliate_id}}</h6>
            </div>

        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Offer Url</strong>
                    {{$offer->offer_url}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Preview Url</strong>
                    {{$offer->preview_url}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Unsubscribe Url</strong>
                    {{$offer->unsubscribe_url}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Description</strong>
                    {{ strip_tags( $offer->description)}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Categories</strong>
                    {{$offer->categories}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Default Payout</strong>
                    {{$offer->default_payout}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">From Lines</strong>
                    {!! str_replace("\n","<br>",$offer->from_lines)!!}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Subject Lines</strong>
                    {!! str_replace("\n","<br>",$offer->subject_lines)!!}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                <strong class="d-block text-gray-dark">Countries</strong>
                    {{$offer->countries}}
                </p>
            </div>

            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Suppression Link</strong>
                    {{$offer->suppression_link}}
                </p>
            </div>

        </div>


        <div class="accordion" id="accordionExample">

            @foreach ($offer->creatives()->where('is_deleted',0)->get() as $key =>  $creative)
                <div class="card">
                    <div class="card-header" id="headingOne">

                        @if(count($creative->creativeimages) == 0) <button class="btn btn-danger delCreative" data-id="{{$creative->id}}"> Delete  </button> @endif
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                    Creative {{$key+1}} : {{$creative->name}}
                                </button>
                            </h5>

                    </div>

                    <div id="collapse{{$key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            @foreach ($creative->creativeimages as $image)
                                <img src="{{ url('/'.$image->creative_url) }}" class="img-fluid" alt="Responsive image">
                            @endforeach
                        </div>
                    </div>
                </div>

            @endforeach

            <br>
            <br>

        </div>



    </div>
@endsection

@section('scriptes')


    @php
        echo '<script>';
        echo "var url = '" . url('/') ."';";

        echo '</script>';
    @endphp

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" ></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tgl').click(function(){
            if( $('#tgl').data("status") != 1){
                    $.post('http://hawk.syspim.com/mL33szRGT2DRnS3hfSHa/hawkScript.php/',{offer_id:$('#tgl').data("id"),url:url},function(data){
                //    $.post('http://127.0.0.1:1234/',{offer_id:$('#tgl').data("id"),url:url},function(data){
                    // console.log(data)
                        location.reload();
                    });
            }

        });

        $(".delCreative").click(function(){
            //  console.log($(this).data('id'));
            //  alert("sorry link not allowed now ");
            var r = confirm("Are you Sure !!!!");
                if (r == true) {
                $.post('/creative/delete',{id:$(this).data('id')},function(data){
                    console.log(data);
                    location.reload();
                });
            }
        });

    </script>


@endsection
