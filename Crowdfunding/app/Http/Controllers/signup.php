<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;

class Signup extends Controller
{
    public function register(Request $request)
    {
        $settings = AdminSettings::first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'countries_id' => 'required|integer|exists:countries,id',
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the user with 'pending' status and email verification flag
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'countries_id' => $request->countries_id,
            'avatar' => 'default.jpg',
            'status' => 'pending', // Set status to 'pending'
            'role' => 'normal',
            'email_verified' => false,
        ]);

        // Generate the email verification URL
        $verificationLink = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $_title_site = $settings->title;
        $_email_noreply = $settings->email_no_reply;
        // Send the verification email
        Mail::send('emails.verify', [
            'username' => $user->name,
            'title_site' => $_title_site,
            'verification_link' => $verificationLink,
        ], function ($message) use ($user,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to($user->email,$user->name);
            $message->subject(trans('users.title_email_verify'));
        });
         return view('auth.verification-sent');
    }


    public function details(Request $request){
        $settings = AdminSettings::first();
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
        'homeaddress' => 'required|string|max:255',
        'streetaddress' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'zipcode' => 'required|regex:/^([A-Za-z0-9]{3,10}(\s?-?\s?[A-Za-z0-9]{3,10})?)$/',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $use=Auth::user();
        $use->homeaddress=$request->homeaddress;
        $use->streetaddress=$request->streetaddress;
        $use->city=$request->city;
        $use->state=$request->state;
        $use->zipcode=$request->zipcode;
        $use->save();
        return redirect('/');
    }
    public function det(){
        return view('auth.details');
    }

}
