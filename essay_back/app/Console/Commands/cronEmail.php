<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Success;
use Illuminate\Http\Request;
use App\Newsletter;
use App\Http\Requests\newsletterunique;
use Illuminate\Support\Facades\DB;
use App\Creative;
class cronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Send a Daily email to all users with a word and its meaning';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
     public function handle()
      {
     
     $creatives = Creative::where([ ['is_active', 1], ['is_hot_offer', 1],['is_hot_offer', 1]])->get();

    

          $emails = DB::table('newsletters')->select('news_email')->get();
          foreach ($emails AS $email) {    
          Mail::send('emails.success',['news_email' => $email->news_email,'creatives'=>$creatives],function($message)use($email)
          {
               $message->from('win.lit.deals@gmail.com', 'Allison Jones');
               $message->to($email->news_email);
               $message->subject('Newsletter daily');     
          });

         }

     }
} 

