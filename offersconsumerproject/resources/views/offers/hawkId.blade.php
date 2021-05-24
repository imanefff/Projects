

@extends('layouts.temp')

@section('css')

@endsection

@section('content')


<div class="contents">
    <h1> Set Offer Hawk id </h1>
    <hr class="hr-primary" />
    <div class="row">
        <div class="col-sm" >
          sid :  {{$offer->sid}}
        </div>
        <div class="col-sm">
          name :  {{$offer->name}}
        </div>
        <div class="col-sm">
            {{-- {{$offer}} --}}
        </div>
      </div>

      <br><br>
    <form action="/offer/already/edit/{{$offer->id}}" method="POST">
        @csrf

        <div class="form-group row">
            <label  class="col-sm-2 col-form-label">Hawk ID</label>
            <div class="col-sm-10">
            <input class="form-control" type="text" name="hawkId" placeholder=" set Hawk ID">
            </div>
          </div>


          <div class="form-group">
            <input type="submit" class="btn btn-primary" value=" --- Edit --- " style="float:right; margin-right:20px">
            </div>


    </form>
    {{-- {{$offer}} --}}
</div>


@endsection


@section('scriptes')

@endsection
