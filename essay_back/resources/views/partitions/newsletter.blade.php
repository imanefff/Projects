
<style type="text/css">





  .modal-newsletter {
    color: #9f9f9f;
    width: 525px;
    font-size: 15px;
  }





  .modal-newsletter .modal-content {
    padding: 40px 50px;
    border-radius: 100px;
    border: none;
  }
  .modal-newsletter .modal-header {
    border-bottom: none;
        position: relative;
    text-align: center;
    border-radius: 5px 5px 0 0;
  }
  .modal-newsletter h4 {
    color: #000;
    text-align: center;
    font-family: 'Raleway', sans-serif;
    font-weight: 800;
    font-size: 30px;
    margin: 0;
    text-transform: uppercase;
  }
  .modal-newsletter .close {
    position: absolute;
    top: -25px;
    right: -35px;
    color: #c0c3c8;
    text-shadow: none;
    opacity: 0.5;
    font-size: 26px;
    font-weight: normal;
  }
  .modal-newsletter .close:hover {
    opacity: 0.8;
  }
  .modal-newsletter .form-control, .modal-newsletter .btn {
    min-height: 46px;
    text-align: center;

  }
  .modal-newsletter .form-control {
    box-shadow: none;
    background: #f5f5f5;
    border-color: #d5d5d5;
    }
    .modal-newsletter .form-control:focus {
    border-color: #ccc;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }
    .modal-newsletter .btn {
        color: #fff;
    background: #353535;
    text-decoration: none;
    transition: all 0.4s;
        line-height: normal;
    padding: 6px 20px;
        border: none;
    margin-top: 20px;
    font-family: 'Raleway', sans-serif;
    text-transform: uppercase;
    }

    .modal-newsletter .form-group {
    padding: 0 20px;
    margin-top: 30px;
    }
    .modal-newsletter .footer-link{
    margin-top: 20px;
    min-height: 25px;
    }
    .modal-newsletter .footer-link a {
    color: #353535;
    display: inline-block;
    border-bottom: 2px solid;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    font-size: 14px;
    }

    .hint-text {
    margin: 100px auto;
    text-align: center;
    }
</style>



<div style="z-index: 10000;margin-top: 77px" id="newsletter" class="modal fade">
    <div class="modal-dialog modal-newsletter">
    <div class="modal-content">
        <form    action="/newsletter" method="post"  >
        @csrf
        {{-- action="/newsletter" method="post" --}}
            <div class="modal-header">
                <h4>Join Our Newsletter</h4>
            </div>
            <div class="modal-body text-center">
                <p class="font-weight-bold text-info" >Subscribe to our newsletter and get the latest deals and money-saving advice delivered straight to your inbox</p>
                <div class="form-group">
                    <input name = "news_email" type="email" class="form-control{{ $errors->has('news_email') ? ' is-invalid' : '' }}" placeholder="Enter your email" value="{{ old('news_email') }}" required>
                    @if ($errors->has('news_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('news_email') }}</strong>
                                    </span>
                                @endif
                    <input type="submit" class="btn aqua-gradient btn-rounded " value="Subscribe">
                    <br>
                <div>
                    <p style=" font-size:13px;" align="center">*By completing this form you are signing up to receive our emails and can unsubscribe at any time.</p>
                </div>
                </div>
                <div class="footer-link"><a  data-dismiss="modal" aria-hidden="true" href="#">No Thanks</a>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>


