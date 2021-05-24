 <footer class="site-footer custom-border-top">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
            <h3 class="footer-heading mb-4">COMPANY</h3>
            <ul class="list-unstyled">
                  
                  <li><a href="{{ url('about')}}">About Us</a></li>
                  <li><a href="{{ url('contact')}}">Contact Us</a></li>
                  <li><a href="{{ url('about/#the_team')}}">Meet the Team</a></li>
                  
                </ul>
            
          </div>
          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                <h3 class="footer-heading mb-4">Quick Links</h3>
                <ul class="list-unstyled">
                  <li><a href="{{ url('/shop')}}">ALL Deals</a></li>
                  <li><a href="{{ url('/#hot_deals')}}">HOT Deals</a></li>
                  <li><a href="{{ url('/#top_deals')}}">TOP Deals</a></li>
                  
                  
                </ul>
           
          </div>

          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
            <div class="block-5 ">
              <h3 class="footer-heading mb-4">Contact Info</h3>
             
              <ul class="list-unstyled">
                <li class="address"><p>San Francisco,</p><p>California, USA</p></li>
                <li class="email">win.lit.deals@gmail.com</li>
              </ul>
            
            </div>
            </div>

                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
               <form action="/newsletter" method="post">
                @csrf
                <h3 class="footer-heading mb-4">Subscribe</h3>
               <div class="input-group">
               <input name = "news_email" type="email" class="form-control{{ $errors->has('news_email') ? ' is-invalid' : '' }}" placeholder="Enter your email" value="{{ old('news_email') }}" required>
             @if ($errors->has('news_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('news_email') }}</strong>
                                    </span>
                                @endif
               <span class="input-group-btn">
               <button style="bottom: 5px;" class="btn btn-danger" type="submit">
                  <i  class="far fa-paper-plane"></i>
                 </button>
                </span>
                   </div>
              </form>
           
          </div>






       <!--      <div>
              <form action="/newsletter" method="post">
                @csrf
                <label for="email_subscribe" class="footer-heading">Subscribe</label>
               <div class="input-group">
               <input name = "news_email" type="email" class="form-control{{ $errors->has('news_email') ? ' is-invalid' : '' }}" placeholder="Enter your email" value="{{ old('news_email') }}" required>
             @if ($errors->has('news_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('news_email') }}</strong>
                                    </span>
                                @endif
               <span class="input-group-btn">
               <button style="bottom: 5px;" class="btn btn-danger" type="submit">
                  <i  class="far fa-paper-plane"></i>
                 </button>
                </span>
                   </div>
              </form>
            </div> -->
          
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <p>

            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | WinLitDeals Inc | <a href="https://sites.google.com/view/winlitdeals/home" target="__blank" style="color: #0757DB;">Privacy Policy</a> | <a style="color: #0757DB;" href="https://sites.google.com/view/winlitdealsterms/home" target="__blank">Terms of Use</a>

            </p>
          </div>

        </div>
      </div>
    </footer>
  </div>
