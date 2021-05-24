{{-- {{ $posts->links('front.pagination') }} --}}

@extends('layouts.temp')
@section('css')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
 table > tbody > tr > * {
    line-height: 38px;
 }
 .addOffer>a{
     float: right;
     margin: 5px;
 }
 .contents {
    width: 1250px;
    margin: 50px auto;
    }

    .container{
        margin-left: 20px;
        margin-right: 20px;
    }

    table > tbody > tr> td:last-child{
        text-align: center;
    }

    .toggle-off.btn {
    background-color: #6c757d;
    color: #fff;
    }



</style>

@endsection

@section('content')
<div class="contents">
    <div class="clearfix addOffer">
    <a href="/offer/create" class="btn btn-primary">Add Offer</a>
    <h1>Offers </h1>

    </div>
    <hr>
    <br>






        <table class="table table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Advertiser</th>
                    <th scope="col">Sid</th>
                    <th scope="col">Name</th>
                    <th scope="col">Payout</th>
                    <th scope="col">Type</th>
                    {{-- <th scope="col">Status</th> --}}
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Show</th>
                  </tr>
                </thead>
                <tbody id="search">
                    @foreach ($offers as $offer)
                        <tr>
                            <th scope="row">{{$offer->advertiser->name}}</th>
                            <td>{{$offer->sid}}</td>
                            <td>{{ substr($offer->name, 0, 35) }}... </td>
                            <td>{{$offer->default_payout}}</td>
                            <td>{{$offer->type}}</td>
                            {{-- <td>{{$offer->status}}</td> --}}
                            <td>{{App\User::findOrFail($offer->user_id)->name}}</td>

                            <td><input type="checkbox"  class="toggle-event" data-toggle="toggle" data-status="{{$offer->status}}" data-id="{{$offer->id}}"  data-onstyle="success" data-on="Enabled" data-off="Disabled"></td>
                            <td>@if($offer->status == 1)<button type="button"  data-id={{$offer->id}} class="btn btn-warning btnDelete">Delete</button>@else --- @endif </td>
                            <td><a class="btn btn-warning" href="/offer/update/{{$offer->id}}">Update</a> </td>
                            <td><a class="btn btn-link" href="/offer/{{$offer->id}}">Show</a> </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>





@isset($offers)
    {{ $offers->links('vendor.pagination.bootstrap-4') }}
@endisset
</div>

@endsection
@section('scriptes')
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

@php

    echo '<script>';

    $js_array = json_encode($offers);

    echo "var javascript_array = ". $js_array . ";\n";

    echo '</script>';

@endphp

<script>
        $(function() {
          $('.toggle-event').bootstrapToggle({
            on: 'Enabled',
            off: 'Disabled'
          });
        })

        $(".toggle-event").each(function(){
           // console.log($(this).data("status"));
           if($(this).data("status") == 0 )  $(this).prop('checked', false).change();
            else { $(this).prop('checked', true).change(); $(this).attr('disabled','disabled'); }
        });



         $('.toggle-event').change(function(){
            //  console.log($(this).data("id"));
            // $(this).attr('disabled','disabled');
         });

         $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".btnDelete").click(function(){

                $.post('offer/delete',{id:$(this).data("id")},function(data){
                   // console.log(data);
                    location.reload();
                });

            });




         });



      </script>
@endsection

{{-- <td><input type="checkbox"  class="toggle-event" data-toggle="toggle" data-status="`+v['status']+`" data-id="`+v['id']+`"  data-onstyle="success" data-on="Enabled" data-off="Disabled"></td> --}}
