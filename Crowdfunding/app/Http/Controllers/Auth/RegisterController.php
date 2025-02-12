<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\URL;
use Mail;

class RegisterController extends Controller
{
    use RegistersUsers;

    // protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $settings = AdminSettings::first();
        $data['_captcha'] = $settings->captcha;

        $messages = [
            'countries_id.required' => trans('misc.please_select_country'),
            'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
            'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
        ];

        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'countries_id' => 'required',
            'agree_gdpr' => 'required',
            'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $settings = AdminSettings::first();

        $status = 'active'; // Default to 'active' if no email verification

        // Generate a random token for email verification
        $token = str_random(75);

        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'countries_id' => $data['countries_id'],
            'avatar' => 'default.jpg',
            'status' => $status,
            'role' => 'normal',
            'token' => $token,  // Store the token for verification
        ]);

        // Check if email verification is enabled
        if ($settings->email_verification == 1) {
            $status = 'pending'; // Set the user status to pending for email verification

            // Generate the verification URL
            $verificationLink = URL::temporarySignedRoute(
                'verification.verify', // Route name for email verification
                now()->addMinutes(60),  // Expiry time for the URL
                ['id' => $user->id, 'hash' => sha1($user->email)] // Route parameters
            );

            // Send the verification email to the user
            $_username = $data['name'];
            $_email_user = $data['email'];
            $_title_site = $settings->title;
            $_email_noreply = $settings->email_no_reply;

            Mail::send('emails.verify', [
                'username' => $_username,
                'title_site' => $_title_site,
                'verification_link' => $verificationLink
            ], function ($message) use ($_email_user, $_username, $_title_site, $_email_noreply) {
                $message->from($_email_noreply, $_title_site);
                $message->subject(trans('users.title_email_verify'));
                $message->to($_email_user, $_username);
            });
        }

        return redirect()->route('verification.sent');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $settings = AdminSettings::first();

        if ($settings->registration_active == 'on') {
            return view('auth.register');
        } else {
            return redirect('/');
        }
    }

    /**
     * Verify the user's email using the signed URL.
     *
     * @param  int  $id
     * @param  string  $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        // Verify the hash matches the hashed email
        if (sha1($user->email) === $hash) {
            $user->status = 'active';
            $user->token = null; // Remove the token after successful verification
            $user->save();
           Auth::login($user);


            // Redirect to the login page or wherever you'd like
            return redirect('/')->with('success', 'Your email has been successfully verified.');
        }

        return redirect('/')->with('error', 'Invalid verification link or expired!');
    }
}
