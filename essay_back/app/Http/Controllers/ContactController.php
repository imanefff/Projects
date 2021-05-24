<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Rules\Captcha;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        protected function validator(array $data)
    {
        return Validator::make($data, [

            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:8'],

            'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    public function index()
    {
        return view('contact');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
       public function store(Request $request)
    {


        $request->validate([
            // 'first_name' => ['required', 'string', 'max:255','regex:[A-Za-z1-9 ]'],
            // 'last_name' => ['required', 'string',  'max:255','regex:[A-Za-z1-9 ]'],
            // 'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string',  'max:500'],
            'g-recaptcha-response' => 'required|captcha',
        ]);


        $contact = new Contact();
        $contact->first_name=$request->input('first_name');
        $contact->last_name=$request->input('last_name');
        $contact->email=$request->input('email');
        $contact->phone=$request->input('phone');
        $contact->message=$request->input('message');
        $contact->request_ip=$_SERVER['REMOTE_ADDR'];
        $contact->http_user_agent=$_SERVER['HTTP_USER_AGENT'];
        $contact->save();
        return redirect('/')->with('success', 'Contact saved!');

    }
    

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
