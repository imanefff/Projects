
@extends('layouts.master')

@section('content')
<div class="container">

      <!-- Section: Edit Account -->
      <section class="section">
        <!-- First row -->
        <div class="row justify-content-center">
          <!-- First card -->
          <div class="col-lg-4 mb-4">

            <!-- Card -->
            <div class="card ">


              <!-- Card content -->
              <div class="card-body card-body-cascade text-center"
                   style="
                     margin-bottom: 16px;
              ">
                  <button class="hoverable" title="Upload Image" data-toggle="modal" data-target="#uploadimg"
                            style="width:150px;height:150px;float:left;border-radius:50%;margin-right: 0px;padding-left: 0px;
                            padding-right: 0px;padding-bottom: 0px;padding-top: 0px;border-left-width: 0px;border-right-width: 0px;
                            border-bottom-width: 0px;border-top-width: 0px;">
                <div class="view overlay zoom">
                  <img src="storage/users/{{ $user->photo }}"class="z-depth-1 mb-3 mx-auto"
                       style="cursor: pointer; width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                       <div class="mask flex-center">
                          <p style="font-weight: bold;font-size:15px;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;" class="white-text">Upload Image</p>
                       </div>
                </div>
                   </button>

                 <h3 style="padding-top: 45px;">{{ $user->name }}'s Profile</h3>
                <br />
                <!-- Central Modal Warning Demo-->
              <div  style="z-index: 10000;margin-top: 77px;" class="modal" id="uploadimg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-notify modal-info" role="document">
              <!--Content-->
              <div class="modal-content">
              <form enctype="multipart/form-data" action="/profile" method="POST">
              <!--Header-->
              @csrf
              <div class="modal-header">
              <p class="heading">Upload Image</p>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="white-text">&times;</span>
              </button>
              </div>

              <!--Body-->
              <div class="modal-body">

              <div class="row">
              <div class="col-3 text-center">
                <img src="storage/users/{{ $user->photo }}" alt=""
                  class="img-fluid z-depth-1-half rounded-circle">
                <div style="height: 10px"></div>
                <p class="title mb-0">{{ $user->name }}</p>

              </div>

              <div class="col-9">
                <p><input type="file" name="photo"></p>
                <p class="card-text" style="font-size:11.5px;">Tips: Upload an image in the JPEG format, less than 2 MB. Your image will be automatically cropped and resized to 100*100 pixels.</p>
              </div>
              </div>

              <!--Footer-->
              <div class="modal-footer justify-content-center">
              <input type="submit" class="btn btn-info">
              <a type="button" class="btn btn-outline-info waves-effect" data-dismiss="modal">No, thanks</a>
              </div>
              </form>
              </div>
              <!--/.Content-->
              </div>
           </div>
           <!-- Card content -->
           </div>
           <!-- Central Modal Warning Demo-->
              </div>
            </div>
            <!-- Card -->
              <div class="card card-cascade narrower">

            <a class="btn btn-lighten-3 mdb-color text-white" href="{{ url('favorite') }}" role="button" class="text-white "> Favorites</a>

            <a class="btn btn-lighten-3 mdb-color text-white" href="{{ url('/profile') }}" role="button" > Edit Profile</a>

            <a class="btn btn-lighten-3 mdb-color text-white" href="{{url('/changePassword')}}" >Change Password</a>

    	   <a class="text-white btn btn-lighten-3 mdb-color" href="{{url('/manageNewsletter')}}" role="button" > Manage Newsletters </a>



          </div>
      </div>
      <!-- First card -->

          <div class="col-lg-8 mb-4">

            <!-- Card -->
            <div class="card card-cascade narrower">

              <!-- Card image -->
              <div class="view view-cascade gradient-card-header mdb-color lighten-3">
                <h5 class="mb-0 font-weight-bold">Edit Account</h5>
              </div>
              <!-- Card image -->

              <!-- Card content -->
              <div class="card-body card-body-cascade text-center">

                <!-- Edit Form -->
                <form action="{{ route('profileUpdate') }}"   method="POST">
                  {{ csrf_field() }}
                  <!-- First row -->
                  <div class="row">

                    <!-- First column -->
                    <div class="col">
                      <div class="md-form mb-0">
                        <input type="text" id="form1" class="form-control "  name="last_name" value="{{$user->last_name}}" >
                        <label for="form1" >Last Name</label>
                      </div>
                    </div>
                    <!-- Second column -->
                    <div class="col-4">
                      <div class="md-form mb-0">
                        <input type="text" id="form2" class="form-control " name="first_name" value="{{$user->name}}">
                        <label for="form2" >First Name</label>
                      </div>
                    </div>
                </div>
                  <!-- Second row -->

                  <div class="row">
                    <!-- First column  -->
                    <div class="col">
                      <div class="md-form mb-0">
                        <input type="text" class="form-control " id="adresseinput" name="adresse" value="{{$user->adresse}}">
                        <label for="adresseinput">Adresse</label>
                      </div>
                    </div>
                    <!-- First column  -->
                    <!-- Second column  -->
                    <!-- Second column  -->
                  </div>
                      <!-- Second row -->

                    <!--third row  -->
                   <div class="row">
                   <!-- First column  -->
                   <div class="col" style="margin-top: 25px;">
                     @php
                       $contries = [
        "United States(US)","Afghanistan(AF)","Aland Islands(AX)","Albania(AL)", "Algeria(DZ)" ,"American Samoa(AS)" ,"Andorra(AD)",
"Angola(AO)","Anguilla(AI)","Antigua and Barbuda(AG)","Argentina(AR)","Armenia(AM)","Aruba(AW)","Australia(AU)" ,"Austria(AT)","Azerbaijan(AZ)",
"Bangladesh(BD)","Bahrain(BH)","Bahamas(BS)","Barbados(BB)","Belarus(BY)","Belgium(BE)","Belize(BZ)","Benin(BJ)",
"Bermuda(BM)",  "Bhutan(BT)","Bolivia(BO)" , "Bosnia and Herzegovina(BA)",  "Botswana(BW)","Bouvet Island(BV)" , "Brazil(BR)", "Brunei(BN)",
"Bulgaria(BG)","Burkina Faso(BF)","Burundi(BI)","Cambodia(KH)","Cameroon(CM)","Canada(CA)" ,"Cape Verde(CV)","Central African Republic(CF)",
"Chad(TD)","Chile(CL)","Christmas Islands(CX)","Cocos (keeling) Islands(CC)","Colombia(CO)","Comoros(KM)","Congo (Congo-Kinshasa)(CD)","Congo(CG)",
"Cook Islands(CK)" , "Costa Rica(CR)" , "Cote D'Ivoire(CI)","China(CN)" ,"Croatia(HR)","Cuba(CU)","Cuba(CU)","Czech(CZ)","Cyprus(CY)","Denmark(DK)",
"Djibouti(DJ)","Dominica(DM)","East Timor()","Ecuador(EC)","Egypt(EG)","Equatorial Guinea(GQ)","Eritrea(ER)","Estonia(EE)","Ethiopia(ET)","Faroe Islands(FO)","Fiji(FJ)",
"Finland(FI)","France(FR)","MetropolitanFrance(FX)","French Guiana(GF)","French Polynesia(PF)","Gabon(GA)","Gambia(GM)","Georgia(GE)","Germany(DE)","Ghana(GH)",
"Gibraltar(GI)","Greece(GR)","Grenada(GD)","Guadeloupe(GP)","Guam(GU)","Guatemala(GT)","Guernsey(GG)","Guinea-Bissau(GW)","Guinea(GN)","Guyana(GY)","Haiti(HT)",
"Honduras(HN)","Hungary(HU)","Iceland(IS)","India(IN)","Indonesia(ID)","Iran(IR)","Iraq(IQ)","Ireland(IE)","Isle of Man(IM)","Israel(IL)","Italy(IT)","Jamaica(JM)",
"Japan(JP)","Jersey(JE)","Jordan(JO)","Kazakhstan(KZ)","Kenya(KE)","Kiribati(KI)","Korea (South)(KR)","Korea (North)(KD)","Kuwait(KW)","Kyrgyzstan(KG)",
"Laos(LO)","Latvia(LV)","Lebanon(LB)","Lesotho(LS)","Liberia(LR)","Libya(LY)","Liechtenstein(LI)","Lithuania(LT)","Luxembourg(LU)","Macedonia(MK)",
"Malawi(MW)","Malaysia(MY)","Madagascar(MG)","Maldives(MV)","Mali(ML)","Malta(MT)","Marshall Islands(MH)","Martinique(MQ)","Mauritania(MR)","Mauritius(MU)",
"Mayotte(YT)","Mexico(MX)","Micronesia(MF)","Moldova(MD)","Monaco(MC)","Mongolia(MN)","Montenegro(ME)","Montserrat(MS)","Morocco(MA)","Mozambique(MZ)",
"Myanmar(MM)","Namibia(NA)","Nauru(NR)","Nepal(NP)","Netherlands(NL)","New Caledonia(NC)","New Zealand(NZ)","Nicaragua(NI)","Niger(NE)","Nigeria(NG)",
"Niue(NU)","Norfolk Island(NF)","Norway(NO)","Oman(OM)","Pakistan(PK)","Palau(PW)","Palestine(PS)","Panama(PA)","Papua New Guinea(PG)","Peru(PE)",
"Philippines(PH)","Pitcairn Islands(PN)","Poland(PL)","Portugal(PT)","Puerto Rico(PR)","Qatar(QA)","Reunion(RE)","Romania(RO)","Rwanda(RW)",
"Russian Federation(RU)","Saint Helena(SH)","Saint Kitts-Nevis(KN)","Saint Lucia(LC)","Saint Vincent and the Grenadines(VG)","El Salvador(SV)",
"Samoa(WS)","San Marino(SM)","Sao Tome and Principe(ST)","Saudi Arabia(SA)","Senegal(SN)","Seychelles(SC)","Sierra Leone(SL)","Singapore(SG)","Serbia(RS)",
"Slovakia(SK)","Slovenia(SI)","Solomon Islands(SB)","Somalia(SO)","South Africa(ZA)","Spain(ES)","Sri Lanka(LK)","Sudan(SD)","Suriname(SR)","Swaziland(SZ)",
"Sweden(SE)","Switzerland(CH)","Syria(SY)","Tajikistan(TJ)","Tanzania(TZ)","Thailand(TH)","Trinidad and Tobago(TT)","Timor-Leste(TL)","Togo(TG)",
"Tokelau(TK)","Tonga(TO)","Tunisia(TN)","Turkey(TR)","Turkmenistan(TM)","Tuvalu(TV)","Uganda(UG)","Ukraine(UA)","United Arab Emirates(AE)","United Kingdom(UK)",
"Uruguay(UY)","Uzbekistan(UZ)","Vanuatu(VN)","Vatican City(VA)","Venezuela(VE)","Vietnam(VN)","Wallis and Futuna(WF)","Western Sahara(EH)","Yemen(YE)",
"Yugoslavia(YU)","Zambia(ZM)","Zimbabwe(ZW)"

                                 ]
                    @endphp
                     <select class="browser-default custom-select" searchable="Search here.." name="country" value="{{$user->country}}">
                          <option value=""  >country</option>
                       @foreach( $contries as $country )
                              <option value="{{ $country }}" @if( $user->country == $country  ) selected @endif  >  {{  $country }} </option>
                       @endforeach

                     </select>

                   </div>
                   <!-- First column  -->

                   <!-- Second column  -->
                   <div class="col-4">
                     <div class="md-form mb-0">
                       <input type="text" class="form-control " id="cityform" name="city" value="{{$user->city}}">
                       <label for="cityform">City</label>
                     </div>
                   </div>
                   <!-- Second column  -->

                   <!-- third column  -->
                   <div class="col-4">
                     <div class="md-form mb-0">
                       <input type="text" class="form-control " id="zipform" name="zip" value="{{$user->zip}}">
                       <label for="zipform">Zip Code</label>
                     </div>
                   </div>
                   <!-- third column  -->

                 </div>

                   <!--third row  -->


                  <!-- fourth row -->
  <div class="row">
                    <!-- First column -->
  <div class="col" style="margin-top: 25px;">
    @php
         $genders = [
              "Male","Female","Transgender"
                     ]
    @endphp
    <select class="browser-default custom-select" searchable="Search here.." name="gender" value="{{$user->gender}}">
                                          <option value=""  >Gender</option>
             @foreach( $genders as $gender )
                    <option value="{{ $gender }}" @if( $user->gender == $gender  ) selected @endif  >  {{  $gender }} </option>
             @endforeach
    </select>
  </div>
               <!-- Second column -->
<div class="col-4">
   <div class=" md-form mb-0 ">
      <input type="date" id="inputMDEx" class="form-control validate " name="date_birth" value="{{$user->date_birth}}">
      <label for="inputMDEx"></label>
  </div>
</div>


</div>
             <!-- fourth row -->

                <!-- 5 row -->

<div class="row">
  <div class="col" style="margin-top: 25px;">
    @php
    $educations = [
              "Less than High School","Some High School","High School Diploma or Equivalent","Some College","Associate Degree",
              "Bachelor’s Degree","Master’s Degree","Doctorate Degree","None of the Above"
                ]
    @endphp
    <select class="browser-default custom-select" searchable="Search here.." name="education" value="{{$user->education}}">
                                         <option value=""  >Education</option>
            @foreach( $educations as $education )
                    <option value="{{ $education }}" @if( $user->education == $education  ) selected @endif  >  {{  $education }} </option>
            @endforeach
    </select>

  </div>
  <div class="col-4" style="margin-top: 25px;">
    @php
    $maritals = [
              "married","single","separated","divorced","widowed"
                    ]
    @endphp
<select  class="browser-default custom-select" searchable="Search here.." name="marital_status" value="{{$user->marital_status}}">
                                      <option value=""  >Marital_Status</option>
         @foreach( $maritals as $marital_status )
                <option value="{{ $marital_status }}" @if( $user->marital_status == $marital_status  ) selected @endif  >  {{  $marital_status }} </option>
         @endforeach
</select>


  </div>

</div>
                    <!-- 5 row -->

                    <!-- 6 row -->
                    <!-- <div class="row">


                      <div class="col">
                        <br>
                                                     <p class="text-left"><b> Choose your prefered categories to show in your home page</b> </p>
                        <section class="section-preview">
                            @php $categoriesUserSelected  =  explode("//" , $user->prefered_categories ); @endphp
                            @foreach($categories as $category)
                             <div class="form-check text-left" >
                                        <div class="row" >
                                          <div class="col " >
                                          <ul style="
                                                  width: 20px;
                                                  padding-left: 0px;  ">
                                             <input type="checkbox" class="form-check-input" id="{{  $category->name }}" value="{{ $category->name }}" name="prefered_categories[]" @if (in_array( $category->name , $categoriesUserSelected )) checked  @endif >
                                             <label class="form-check-label"  for="{{  $category->name }}" style="
                                                        width: 300px;
                                                        padding-left: 0px;
                                                        left: 0px;
                                                        padding-left: 30px;
                                                        right: 150px;
                                                        height: 15px;
                                                                          ">{{  $category->name }}</label> </ul>

                                        </div>
                                      </div>
                            </div>
                            @endforeach
                         </section>
                      </div>
                    </div> -->
<br>
                    <!-- 7 row -->
                  <div class="row">
                    <!-- First column -->
                    <div class="col-md-12">
                      <div class="md-form mb-0">
                        <textarea type="text" id="form78" class="md-textarea form-control" name="about"  > {{ $user->about }}</textarea>
                        <label for="form78" ><b>About me </b></label>
                      </div>
                    </div>
                  </div>
                   <!-- 7 row -->

                  <!-- Fourth row -->
                  <div class="row">
                    <div class="col-md-12 text-center my-4">
                      <input  type="submit" value="Update Account" class="btn btn-info btn-rounded" onclick="toastr.success('Your information are saved successfully');">
                      <p style="font-size: 12px;" >Delete your account ?  <a href="{{ url('/deleteAccount') }}" class="blue-text ml-1">click here</a></p>
                    </div>
                  </div>
                  <!-- Fourth row -->

                </form>
                <!-- Edit Form -->

              </div>
              <!-- Card content -->

            </div>
            <!-- Card -->

          </div>
          <!-- Second column -->
 </section>
        </div>
        <!-- First row -->

     
      <!-- Section: Edit Account -->

    

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

@section('js')



<script type="text/javascript">

toastr.options.showMethod = 'slideDown';
toastr.options.hideMethod = 'slideUp';
toastr.options.closeMethod = 'slideUp';

  $('.mdb-select').materialSelect({
  destroy: true
  });

  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });


</script>
@endsection
