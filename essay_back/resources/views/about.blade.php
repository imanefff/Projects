@extends('layouts.master')


@section('content')


    <div class="site-blocks-cover inner-page" data-aos="fade">
      <div class="container">
        <div class="row">
          <div class="col-md-6 ml-auto order-md-2 align-self-start">
            <div class="site-block-cover-content">
            <h2 class="sub-title">#Best Summer Deals 2019</h2>
            <h1>Special Online Offers</h1>
            <p><a href="{{ url('/#all_deals')}}" class="btn btn-black rounded-0">Shop Now</a></p>
            </div>
          </div>
          <div class="col-md-6 order-1 align-self-end">
            <img src="{{ asset('frontEnd') }}/images/aboutus.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <div class="custom-border-bottom py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{ url('/')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">About</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section site-section-sm site-blocks-1 border-0" data-aos="fade">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
            <div class="icon mr-4 align-self-start">
              <span class="fas fa-shopping-bag"></span>
            </div>
            <div class="text">
              <h2 class="text-uppercase"><b>We love to help you shop smarter</b></h2>
              <p>We believe that no matter what you’re buying, you should never have to pay full price. Basically, we care a lot about saving a lot.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
            <div class="icon mr-4 align-self-start">
              <span class="fas fa-hand-holding-usd"></span>
            </div>
            <div class="text">
              <h2 class="text-uppercase"><b>We don’t actually sell anything</b></h2>
              <p>Win lit Deals is a service, not a store. We will provide you with crazy low prices on stuff you need and stuff you didn't even know you needed.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
            <div class="icon mr-4 align-self-start">
              <span class="fas fa-users"></span>
            </div>
            <div class="text">
              <h2 class="text-uppercase"><b>Our team rules</b></h2>
              <p>We think happy people make the coolest websites, so we do our best to make the Win Lit Deals office a happy place filled with fun activities and free snacks.</p>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div id="the_team" class="site-section custom-border-bottom" data-aos="fade">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6">
            <div class="block-16">
              <figure>
                <img src="{{ asset('frontEnd') }}/images/office.jpg" alt="Image placeholder" class="img-fluid rounded">
                
              </figure>
            </div>
          </div>
          <div class="col-md-1"></div>
          <div  class="col-md-5">
            
            
            <div  class="site-section-heading pt-3 mb-4">
              <h2 class="text-black">How We Started</h2>
            </div>
            <p><b>Win Lit Deals was created by a group of young, tasteful individuals who have a mission to help users improve their life quality. </b></p>
            <p><b>Our talented leadership team has extensive experience and knowledge of the market. Together, we share the spirit of continuously optimizing our services through the latest data and technologies to maximize value for our users.</b></p>
            
          </div>
        </div>
      </div>
    </div>

    <div class="site-section custom-border-bottom" data-aos="fade">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>The Team</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-3">
  
            <div class="block-38 text-center">
              <div class="block-38-img">
                <div class="block-38-header">
                  <img src="{{ asset('frontEnd') }}/images/pp_1.jpg" alt="Image placeholder" class="mb-4">
                  <h3 class="block-38-heading h4">Allison A. Jones</h3>
                  <p class="block-38-subheading">Chief Executive Officer</p>
                </div>
               
              </div>
            </div>
          </div>
            <div class="col-md-6 col-lg-3">
            <div class="block-38 text-center">
              <div class="block-38-img">
                <div class="block-38-header">
                  <img src="{{ asset('frontEnd') }}/images/pp_2.jpg" alt="Image placeholder" class="mb-4">
                  <h3 class="block-38-heading h4">Timmy Ebel</h3>
                  <p class="block-38-subheading">Marketing</p>
                </div>
              
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="block-38 text-center">
              <div class="block-38-img">
                <div class="block-38-header">
                  <img src="{{ asset('frontEnd') }}/images/pp_3.jpg" alt="Image placeholder" class="mb-4">
                  <h3 class="block-38-heading h4">Olivia Petterson</h3>
                  <p class="block-38-subheading">SVP, Coupons & Publishing</p>
                </div>
              
              </div>
            </div>
          </div>
        
          <div class="col-md-6 col-lg-3">
            <div class="block-38 text-center">
              <div class="block-38-img">
                <div class="block-38-header">
                  <img src="{{ asset('frontEnd') }}/images/pp_4.jpg" alt="Image placeholder" class="mb-4">
                  <h3 class="block-38-heading h4">Rocky L. James</h3>
                  <p class="block-38-subheading">Web Developer</p>
                </div>
              
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  

    


@endsection
