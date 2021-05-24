
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="padding:10px 13px">

        @if(  url()->current() == url('/server/commend') || url()->current() == url('/server/timeline') )
            <a class="navbar-brand" href="{{ url('/') }}" > {{ config('app.name', 'Laravel') }} </a>
        @else
            <a class="navbar-brand" href="{{ url('/') }}" style="color: #1da6bb !important;" > {{ config('app.name', 'Laravel') }} </a>
        @endif
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" >
                <span class="navbar-toggler-icon"></span>
            </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item" >
                <div class="drop_do">
                    <button class="btn btn-dark btnDrop"  data-pes="false">PMTS  &nbsp; <i class="fas fa-sort-down"></i></button>
                    <div class="dropContent">
                        <ul>
                            <li> <a class="nav-link" href="{{url('/server/commend')}}"> Manage PMTAs </a> </li>
                            <li> <a class="nav-link" href="{{url('/server/timeline')}}">Server Timeline </a> </li>
                            <li> <a class="nav-link" target="_blank" href="http://hawk.syspim.com:63380/tools/pmtas_monitor">PMTAs Monitor</a> </li>
                        </ul>
                    </div>
                </div>
            </li>


            <li class="nav-item" >
                <div class="drop_do">
                    <button class="btn btn-dark btnDrop"  data-pes="false">Apps &nbsp; <i class="fas fa-sort-down"></i></button>
                    <div class="dropContent">
                        <ul>
                            <li> <a class="nav-link" target="_blank" href="http://hawk.syspim.com:63380">HAWK  </a> </li>
                            <li> <a class="nav-link" target="_blank" href="https://stat.syspim.com/">STATS</a> </li>
                            <li> <a class="nav-link" target="_blank" href="http://collect.mindworksdirect.com:56431">POSTMASTER</a> </li>
                            <li> <a class="nav-link" target="_blank" href="http://stools.pimhost.com:23380">SUPPORT</a> </li>
                            <li> <a class="nav-link" target="_blank" href="http://srvmanager.syspim.com/">PIM Ressources</a> </li>
                        </ul>
                    </div>
                </div>
            </li>


        </ul>


        <div class="drop_do">
            <button  class="btn btn-info btnDrop" data-pes="true">  {{ Auth::user()->name }}  &nbsp; <i class="fas fa-sort-down"></i> </button>
            <div class="dropContent">
                <ul>
                    <li> <a class="nav-link">  Entity : {{ Auth::user()->entity->name }}  </a > </li>
                        @if( (Auth::user()->permission =="admin" || Auth::user()->permission =="manager"  ) ) <li> <a class="nav-link" href="{{url('/users/list')}}"> Users management </a>    </li>@endif
                        @if( Auth::user()->permission =="agent_offer" &&  Auth::user()->entity_id != 1) <li> <a class="nav-link" href="{{url('/entity/switch/1')}}"> Switch to PIM 01 </a>    </li>@endif
                        @if( Auth::user()->permission =="agent_offer" &&  Auth::user()->entity_id != 5 ) <li> <a class="nav-link" href="{{url('/entity/switch/5')}}"> Switch to PIM 02 </a>    </li>@endif
                        @if( Auth::user()->permission =="agent_offer" &&  Auth::user()->entity_id != 8 ) <li> <a class="nav-link" href="{{url('/entity/switch/8')}}"> Switch to PIM 03 </a>    </li>@endif

                    <div class="dropdown-divider" style="margin-top: 3px;margin-bottom: 3px;"></div>
                    <li>
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    </li>
                </ul>

            </div>
        </div>

    </div>
</nav>
