<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\Success;
use Illuminate\Http\Request;
use App\Newsletter;
use App\Creative;

use App\Http\Requests\newsletterunique;
use Illuminate\Support\Facades\DB;
class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partitions.newsletter');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newsletterunique $request)
    {
        $this->validate($request, [
            'news_email'=>'required|distinct'
        ]);
        $newsletter = new Newsletter();
        $newsletter->news_email=$request->input('news_email');
        $newsletter->request_ip=$_SERVER['REMOTE_ADDR'];
        $newsletter->http_user_agent=$_SERVER['HTTP_USER_AGENT'];
        if ($newsletter->save())
        {
            Mail::send('emails.welcome',['news_email' => $newsletter->news_email],function ($message)use($newsletter)
            {
               $message->from('win.lit.deals@gmail.com', 'Allison Jones');
               $message->to($newsletter->news_email);
               $message->subject('welcome newsletter');
            });
       return redirect('/')->with('alert','You have successfully applied for our Newsletter');

    //   return redirect()->route('homeContent');

        }else{
            return redirect()->back()->withErrors($validator);
        }

    }
    // public function autoMail(Request $request)
    // {
    //     $this->validate($request, [
    //         'news_email'=>'required|distinct'
    //     ]);
    //     $newsletter = new NewsLetter();
    //     $newsletter->news_email = $request->input('news_email');
    //     if ($newsletter->save())
    //     {
    //         Mail::to($newsletter->news_email)->send(new Success($newsletter));
    //         return redirect()->back()->with('alert','You have successfully applied for our Newsletter');
    //     }else{
    //         return redirect()->back()->withErrors($validator);
    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
