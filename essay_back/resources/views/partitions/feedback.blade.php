
<style>
      
    

      #btn{ 
        position: absolute;
          top: 50%;
          right: 0;
       }

.content {
    z-index: 100;
    max-width: 42px;
    position: absolute;
    top: 50%;
    right: 0;
    border-radius: 5px 0 0 5px;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
}

      .content > button > img {
       width: 30px;
    height: 33px;
    padding: 2px 0;
}

.content > button {
  background: #eee;
    border: none;
    box-sizing: border-box;
    /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.68); */
    /*border-radius: 5px 0 0 0;*/
}


.content :first-child{
  border-radius: 5px 0 0 0;
}


.content :first-child > img {
  margin-bottom: 2px;
  margin-top: 3px;
} 
.content :last-child{
  border-radius: 0 0 0 5px;
}

.content :last-child > img {
  margin-bottom: 3px;
  margin-top: 2px;
}


    </style>


    <!-- Right button for feedback and newsletter -->

    <div class="content" style="position: fixed">
    
    <button class="hoverable" title="Join Newsletter" data-toggle="modal" data-target="#newsletter" ><img src="{{ asset('frontEnd') }}/images/1.png" alt=""></button>
    <button class="hoverable" title="Contact us" data-toggle="modal" data-target="#contactus"><img src="{{ asset('frontEnd') }}/images/2.png" alt=""></button>
  </div>



<!-- Modal: modalAbandonedCart-->
<div class="modal fade right" id="contactus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <p class="heading">Get In Touch</p>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="white-text">&times;</span>
        </button>
      </div>

      <!--Body-->
      <div class="modal-body">

        <div class="row">
          <div class="col-3">

            <p></p>
            <p class="text-center"><i class="far fa-question-circle fa-4x"></i></p>
          </div>

          <div class="col-9">
            <p>Got a question? We'd love to hear from you. Send us a message and we will respond as soon as possible.</p>
           
          </div>
        </div>
      </div>

      <!--Footer-->
      <div class="modal-footer justify-content-center">
        <a type="button" href="{{ url('contact')}}" class="btn btn-info">Contact us</a>
        <a type="button" class="btn btn-outline-info waves-effect" data-dismiss="modal">Cancel</a>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Modal: modalAbandonedCart-->



 






