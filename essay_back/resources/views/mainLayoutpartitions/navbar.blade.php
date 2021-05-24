<style type="text/css">
.dropdown-menu {
  display: none;
  position: absolute;
  background-color: #FFFFFF;
  
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;


}


.dropdown-menu a {
  color: black;
 
  text-decoration: none;
  display: block;
}

.dropdown-menu a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-menu {display: block;}



  
</style>

<div class="site-navbar bg-white py-2">

   <div class="search-wrap">
     <div class="container">
     
       <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
       <form action="{{ url('search')}}"  method="GET">
   

       <input value="{{ request()->input('query') }}" type="text" name="query" id="query" class="form-control forSearch" placeholder="Search a deal.." />
    

       </form>
      
     </div>
   </div>

   <div class="container">
     <div class="d-flex align-items-center justify-content-between">
       <div class="logo">
         <div class="site-logo">
           <a href="{{ url('/')}}" class="js-logo-clone">WinLitDeals</a>
         </div>
       </div>

       <div class="main-nav d-none d-lg-block">
         <nav class="site-navigation text-right text-md-center" role="navigation">
           <ul class="site-menu js-clone-nav d-none d-lg-block">
             <li class="has-children  @if(url()->current() ==  url('/') ) active @endif"><a href="{{ url('/')}}">Home</a>
              <ul class="dropdown">
                <li><a href="{{ url('/shop')}}"><b>ALL DEALS</b></a></li>
                <li><a href="{{ url('/#top_deals')}}"><b>TOP DEALS</b></a></li>
                <li><a href="{{ url('/#hot_deals')}}"><b>HOT DEALS</b></a></li>
              </ul>


             </li>

             <li class="has-children">
               <a href="{{ url('/shop')}}">CATEGORIES</a>
               <ul class="dropdown">


                 @foreach(\App\Category::where('is_active', 1)->get() as $category)
                 <li><a href='{{url("../shop/category/{$category->id} ")}}'><b>{{ $category->name }}</b></a></li>
                 @endforeach
                 
                 
               </ul>
             </li>
             <li class="@if( url()->current() ==  url('about') ) active @endif" ><a  href="{{ url('about')}}">About us</a></li>
             <li class="@if( url()->current() ==  url('contact') ) active @endif" ><a href="{{ url('contact')}}">Contact us</a></li>
             <li style="width: 120px;"></li>
             </ul>
             </nav>
             </div>

            <div id="icons">

                 @guest
              
              @if (Route::has('login'))
              <a title="Log In" href="{{ route('login') }}" class="icons-btn d-inline-block"><span style="font-size: 19px;" class="fas fa-user-alt"></span></a>
              @endif

              @else
              <li class="dropdown d-inline-block">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="position:relative; padding-left:50px;">
                           <img src="../../storage/users/{{ Auth::user()->photo }}" style="width:35px; height:32px; position:absolute; left:10px; border-radius:100%;bottom: -4px;">
                               {{ Auth::user()->name }} <span class="caret"></span>
                          </a>


                            <ul class="dropdown-menu" role="menu">
                                <li><a style="padding: 12px 16px;" href="{{ url('/profile') }}"><i style="color:#218CD7;" class="fa fa-btn fa-user"></i>&nbsp;&nbsp;Profile</a></li>
                                @if( Auth::user()->role_id==1 | Auth::user()->role_id==3)
                                 <li><a style="padding: 12px 16px;" href="{{ url('/admin') }}"><i style="color:#218CD7;" class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard</a></li>                       
                                @endif

                                @if( Auth::user()->role_id==4)
                                 <li><a style="padding: 12px 16px;" href="{{ url('/') }}"><i style="color:#218CD7;" class="fas fa-plus"></i>&nbsp;&nbsp;Add offer</a></li>
                                 <li><a style="padding: 12px 16px;" href="{{ url('/') }}"><i style="color:#218CD7;" class="fas fa-database"></i>&nbsp;&nbsp;Get offers</a></li>

                                 @endif


                                <li><a style="padding: 12px 16px;" href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"><i style="color:#218CD7;" class="fas fa-sign-out-alt"
                                  ></i>&nbsp;&nbsp;Sign Out</a></li>

                                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                       @csrf
                                   </form>
                            </ul>
                        </li>
              
                       


             
             

               @endguest
               <a title="Search an offer" href="#" class="icons-btn d-inline-block js-search-open"><span style="font-size: 19px;" class="icon-search"></span></a>
                <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
       
           </div>
  

   </div>
 </div>
</div>

