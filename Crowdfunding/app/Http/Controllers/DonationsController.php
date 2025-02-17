<?php

namespace App\Http\Controllers;


use App\Helper;
use Carbon\Carbon;
use App\Models\User;
use PayPal\Api\PaymentExecution;
use App\Models\Rewards;
use App\Models\Campaigns;
use App\Models\Donations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use App\Models\AdminSettings;
use App\Models\PaymentGateways;
use Illuminate\Support\Facades\Auth;
use Fahim\PaypalIPN\PaypalIPNListener;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;

class DonationsController extends Controller
{
	public function __construct(AdminSettings $settings, Request $request)
	{
		$this->settings = $settings::first();
		$this->request = $request;
	}

	/**
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, $slug = null)
	{
		$response = Campaigns::where('id', $id)->where('status', 'active')->firstOrFail();

		$pledgeID = $this->request->input('pledge');

		$findPledge = $response->rewards->find($pledgeID);

		if (isset($findPledge)) {
			$pledgeClaimed = $response->donations()->where('rewards_id', $findPledge->id)->count();
		}

		if (isset($findPledge) && $pledgeClaimed < $findPledge->quantity) {
			$pledge = $findPledge;
		} else {
			$pledge = null;
		}

		$timeNow = strtotime(Carbon::now());

		if ($response->deadline != '') {
			$deadline = strtotime($response->deadline);
		}

		// Redirect if campaign is ended
		if (!isset($deadline) && $response->finalized == 1) {
			return redirect('campaign/' . $response->id);
		} else if (isset($deadline) && $response->finalized == 1) {
			return redirect('campaign/' . $response->id);
		} else if (isset($deadline) && $deadline < $timeNow) {
			return redirect('campaign/' . $response->id);
		}

		$uriCampaign = $this->request->path();

		if (str_slug($response->title) == '') {
			$slugUrl  = '';
		} else {
			$slugUrl  = '/' . str_slug($response->title);
		}

		$url_campaign = 'donate/' . $response->id . $slugUrl;

		//<<<-- * Redirect the user real page * -->>>
		$uriCanonical = $url_campaign;

		if ($uriCampaign != $uriCanonical) {
			return redirect($uriCanonical);
		}

		$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', '');

		// All Donations
		$donations = $response->donations()->orderBy('id', 'desc')->paginate(2);

		// Updates
		$updates = $response->updates()->orderBy('id', 'desc')->paginate(1);

		if (str_slug($response->title) == '') {
			$slug_url  = '';
		} else {
			$slug_url  = '/' . str_slug($response->title);
		}

		// Bank Transfer Info
		$_bankTransfer = PaymentGateways::where('id', 3)->where('enabled', '1')->select('bank_info')->first();

		// Stripe Key
		$_stripe = PaymentGateways::where('id', 2)->where('enabled', '1')->select('key')->first();

		return view('default.donate')
			->with([
				'response' => $response,
				'pledge' => $pledge,
				'percentage' => $percentage,
				'donations' => $donations,
				'updates' => $updates,
				'slug_url' => $slug_url,
				'_bankTransfer' => $_bankTransfer,
				'_stripe' => $_stripe
			]);
	} // End Method

	// Send donation and validation
	public function send()
	{

		$campaign = Campaigns::findOrFail($this->request->_id);

		//<---- Verify Pledge send
		if (isset($this->request->_pledge) && $this->request->_pledge != 0) {
			$findPledge = $campaign->rewards->where('id', $this->request->_pledge)
				->where('campaigns_id', $this->request->_id)
				->where('amount', $this->request->amount)->first();

			$pledgeClaimed = $campaign->donations()->where('rewards_id', $findPledge->id)->count();
		}

		if (isset($findPledge) && $pledgeClaimed < $findPledge->quantity) {
			$this->request->_pledge = $findPledge->id;
		} else {
			$this->request->_pledge = 0;
		}

		// Currency Position
		if ($this->settings->currency_position == 'right') {
			$currencyPosition =  2;
		} else {
			$currencyPosition =  null;
		}

		Validator::extend('check_payment_gateway', function ($attribute, $value, $parameters) {
			return PaymentGateways::find($value);
		});

		$data = $this->request->all();

		if (auth()->check() && $this->settings->captcha_on_donations == 'on') {
			$data['_captcha'] = 1;
		} else {
			$data['_captcha'] = $this->settings->captcha_on_donations == 'off' ? $data['_captcha'] = 1 : $data['_captcha'] = 0;
		}

		$messages = array(
			'amount.min' => trans('misc.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'amount.max' => trans('misc.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'payment_gateway.check_payment_gateway' => trans('admin.payments_error'),

			'bank_transfer.required_if' => trans('admin.bank_transfer_required'),
			'bank_transfer.min' => trans('admin.bank_transfer_limit'),
			'bank_transfer.max' => trans('admin.bank_transfer_limit'),
			'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
			'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
		);

		//<---- Validation
		$validator = Validator::make($data, [
			'amount' => 'required|integer|min:' . $this->settings->min_donation_amount . '|max:' . $this->settings->max_donation_amount,
			'full_name'     => 'required|max:20',
			'email'     => 'required|email|max:100',
			'country'     => 'required',
			'postal_code'     => 'required|max:10',
			'comment'     => 'nullable|max:50',
			'payment_gateway' => 'required|check_payment_gateway',
			'bank_transfer' => 'required_if:payment_gateway,==,3|min:10|max:300',
			'g-recaptcha-response' => 'required_if:_captcha,==,0|captcha'
		], $messages);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		}

		// Get name of Payment Gateway
		$payment = PaymentGateways::find($this->request->payment_gateway);

		if (!$payment) {
			return response()->json([
				'success' => false,
				'errors' => ['error' => trans('admin.payments_error')],
			]);
		}

		$data = [
			'campaign_id'    => $campaign->id,
			'campaign_title' => e($campaign->title),
		];

		$data_all = $this->request->except(['_token']);
		$dataGlobal = array_merge($data, $data_all);

		// Send data to the payment processor
		return redirect()->route(str_slug($payment->name), $dataGlobal);
	} //<--------- End Method  Send

    public function paymentOptions($id)
    {
        $payment = AdminSettings::first();
        $campaign = Campaigns::findOrFail($id);
        // dd($campaign);
        $paymentGateways = PaymentGateways::all(); // Fetch available payment gateways

        return view('donation.payment-options', compact('campaign', 'paymentGateways','payment'));
    }
    public function payWithCard($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = PaymentGateways::where('name','card')->first();


        return view('donation.card_payment', [
            'campaign' => $campaign,
            'settings' => $settings,
            'stripeKey' => $settings->key
        ]);
        // return view('donation.card_payment', compact('campaign'));
    }

    public function processCardPayment(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        $stripeGateway = PaymentGateways::where('name', 'card')->firstOrFail();
        $stripeSecretKey = $stripeGateway->key_secret;

        Stripe::setApiKey($stripeSecretKey);

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required',
            'postal_code' => 'required|max:10',
            'comment' => 'nullable|max:255',
            'payment_gateway' => 'required|in:stripe',
            'anonymous' => 'nullable|in:0,1',
            'stripeToken' => 'required' // Ensure Stripe token is present
        ]);


        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        try {
            // Process Stripe Payment
            $charge = \Stripe\Charge::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => "Donation to {$campaign->title}",
            ]);
            // dd($charge);

            // Store Donation Record
          $donations=  Donations::create([
                'fullname' => $request->full_name,
                'campaigns_id' => $id,
                'email' => $request->email,
                'donation' => $request->amount,
                'anonymous' => $request->has('anonymous') ? '1' : '0',
                'payment_gateway' => 'card',
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'comment' => $request->comment,
                'approved' => '0',
            ]);


            $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });
            // dd($donations);

            return back()->with('success', 'Donation successful!');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function payWithPayPal($id)
    {
         $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'paypal')->firstOrFail();
        return view('donation.paypal', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }
    public function payWithZelle($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'zelle')->firstOrFail();
        return view('donation.zelle', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }
    public function processzelle(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required',
            'postal_code' => 'required|max:10',
'anonymous' => 'nullable|in:0,1',
            'comment' => 'nullable|max:255',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);


        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
   if ($request->hasFile('bank_transfer')) {
            $banktransfer = $request->file('bank_transfer');
            $passportFrontName = time() . '_bank_transfer.' . $banktransfer->getClientOriginalExtension();
            $banktransfer->move(public_path('banktransfer'), $passportFrontName);
            $receiptPath = 'banktransfer/' . $passportFrontName;
        }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'campaigns_id' => $id,
            'email' => $request->email,
            'donation' => $request->amount,
            'payment_gateway' => 'zelle',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);

        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });


        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }

    public function payWithCashApp($id)
    {
         $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'cashapp')->firstOrFail();
        return view('donation.cashapp', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }
    public function processcashapp(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required',
            'postal_code' => 'required|max:10',
            'comment' => 'nullable|max:255',
'anonymous' => 'nullable|in:0,1',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);


        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
        if ($request->hasFile('bank_transfer')) {
            $banktransfer = $request->file('bank_transfer');
            $passportFrontName = time() . '_bank_transfer.' . $banktransfer->getClientOriginalExtension();
            $banktransfer->move(public_path('banktransfer'), $passportFrontName);
            $receiptPath = 'banktransfer/' . $passportFrontName;
        }
    
        // if ($request->hasFile('bank_transfer')) {
        //     $receiptPath = $request->file('bank_transfer')->store('bank_transfer', 'public');
        // } else {
        //     $receiptPath = null;
        // }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
            'campaigns_id' => $id,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'email' => $request->email,
            'donation' => $request->amount,
            'payment_gateway' => 'cashapp',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);
        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });

        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }

    public function payWithVenmo($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'vemo')->firstOrFail();
        return view('donation.vemo', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }

    public function processvemo(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
'anonymous' => 'nullable|in:0,1',
            'country' => 'required',
            'postal_code' => 'required|max:10',
            'comment' => 'nullable|max:255',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);


        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
        if ($request->hasFile('bank_transfer')) {
            $banktransfer = $request->file('bank_transfer');
            $passportFrontName = time() . '_bank_transfer.' . $banktransfer->getClientOriginalExtension();
            $banktransfer->move(public_path('banktransfer'), $passportFrontName);
            $receiptPath = 'banktransfer/' . $passportFrontName;
        }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
            'campaigns_id' => $id,
            'email' => $request->email,
            'donation' => $request->amount,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'payment_gateway' => 'vemo',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);
        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });

        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }

    public function payWithCrypto($id)
    {
        $campaign = Campaigns::findOrFail($id);
        return view('donation.crypto', compact('campaign'));
    }
    public function processcrypto(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();
        $coin = PaymentGateways::where('name', 'coinpayments')->firstOrFail();
        $merchant_id = $coin->key;

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required|string',
            'postal_code' => 'required|string|max:10|regex:/^[A-Za-z0-9-]+$/',
            'comment' => 'nullable|string|max:255',
'anonymous' => 'nullable|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        $orderId = 'ORD-' . uniqid();

        // Save Donation Request
        $donation = Donations::create([
            'fullname' => $request->full_name,
            'email' => $request->email,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'donation' => $request->amount,
            'order_id' => $orderId,
            'approved' => '0',
            'campaigns_id' => $id,
            'payment_gateway'=>'coinpayments'
        ]);

        // $_title_site = $settings->title;

        // Mail::send('emails.donationsAdmin', [
        //     'username' => $request->full_name,
        //     'title_site' => $_title_site,
        //     'amount'=>$request->amount,
        //     'email'=>$request->email,
        //     'campaign'=>$campaign,
        // ], function ($message) use ($request,$_title_site) {
        //     $message->from('noreply@venueri.com', $_title_site);
        //     $message->to('dollarhunter044@gmail.com',$request->full_name);
        //     $message->subject('donations');
        // });
        $url = 'https://api.oxapay.com/merchants/request';
        $callbackurl = route('coin.callback', ['campid' => $campaign->id, 'orderid' => $orderId]);

        $data = [
            'merchant' => $merchant_id,
            'amount' => $request->amount,
            'currency' =>  'usd', // Make currency configurable
            'lifeTime' => 30,
            'feePaidByPayer' => 0,
            'underPaidCover' => 2.5,
            'callbackUrl' => $callbackurl,
            'returnUrl' => env('OXAPAY_RETURN_URL'),
            'description' => 'Donation Payment',
            'orderId' => $orderId,
            'email' => $request->email,
        ];

        try {
            try {
                $client = new Client(['timeout' => 60]); // Set a 60-second timeout

                $response = $client->post($url, [
                    'json' => $data,
                    'headers' => ['Content-Type' => 'application/json'],
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                // Check if result is 100 (success) and payment URL exists
                if ($result['result'] == 100 && isset($result['payLink'])) {
                    return redirect($result['payLink']);
                } else {
                    Log::error('Oxapay Payment Failed', ['response' => $result]);
                    // dd($result);
                    return back()->with('error', 'Payment request failed: ' . ($result['message'] ?? 'Unknown error'));
                }

            } catch (\Exception $e) {
                Log::error('Oxapay API Error', ['error' => $e->getMessage()]);
                dd($e->getMessage());
                return back()->with('error', 'Payment request failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Oxapay API Error', ['error' => $e->getMessage()]);

            return back()->with('error', 'Payment request failed. Please try again.');
        }
    }

    public function handleCallback(Request $request)
    {
        $orderId = $request->input('orderId');
        $status = $request->input('status'); // success or failed

        // Find donation record
        $donation = Donations::where('order_id', $orderId)->first();

        if ($donation) {
            if ($status === 'success') {
                $donation->update(['payment_status' => 'success']);
            } else {
                $donation->update(['payment_status' => 'failed']);
            }
            return back()->with('success', 'Donation successful!');
        }

        return response()->json(['message' => 'Order not found'], 404);
    }

    public function wiretransfer($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'wiretransfer')->firstOrFail();
        return view('donation.wiretransfer', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }
    public function banktransfer($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $bank = PaymentGateways::where('name', 'Bank Transfer')->firstOrFail();
        return view('donation.bank_transfer', [
            'campaign' => $campaign,
            'bank'=>$bank->bank_info
        ]);
    }

    public function processbanktransfer(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required',
            'postal_code' => 'required|max:10',
'anonymous' => 'nullable|in:0,1',
            'comment' => 'nullable|max:255',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);


        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
        if ($request->hasFile('bank_transfer')) {
            $banktransfer = $request->file('bank_transfer');
            $passportFrontName = time() . '_bank_transfer.' . $banktransfer->getClientOriginalExtension();
            $banktransfer->move(public_path('banktransfer'), $passportFrontName);
            $receiptPath = 'banktransfer/' . $passportFrontName;
        }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
            'campaigns_id' => $id,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'email' => $request->email,
            'donation' => $request->amount,
            'payment_gateway' => 'Bank Transfer',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);
        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@fundmegram.com', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });

        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }
    public function processwiretransfer(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'country' => 'required',
            'postal_code' => 'required|max:10',
            'comment' => 'nullable|max:255',
'anonymous' => 'nullable|in:0,1',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);
    // dd($validation->errors());

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
        if ($request->hasFile('bank_transfer')) {
            $receiptPath = $request->file('bank_transfer')->store('bank_transfer', 'public');
        } else {
            $receiptPath = null;
        }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
            'campaigns_id' => $id,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'email' => $request->email,
            'donation' => $request->amount,
            'payment_gateway' => 'wiretransfer',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);
//mailto admin
        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });
// dd($donate);

        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }

    public function processPayPalPayment(Request $request, $id){
        $campaign = Campaigns::findOrFail($id);
        $settings = AdminSettings::first();

        // Validate input
        $validation = Validator::make($request->all(), [
            'amount' => 'required|integer|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:100',
'anonymous' => 'nullable|in:0,1',
            'country' => 'required',
            'postal_code' => 'required|max:10',
            'comment' => 'nullable|max:255',
            // 'payment_gateway' => 'required|in:Bank Transfer',
            'bank_transfer' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Limit 2MB
            ]);
    // dd($validation->errors());

        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        // dd($validation);
        // Handle File Upload
        if ($request->hasFile('bank_transfer')) {
            $banktransfer = $request->file('bank_transfer');
            $passportFrontName = time() . '_bank_transfer.' . $banktransfer->getClientOriginalExtension();
            $banktransfer->move(public_path('banktransfer'), $passportFrontName);
            $receiptPath = 'banktransfer/' . $passportFrontName;
        }

        // Store in DB
     $donate=   Donations::create([
            'fullname' => $request->full_name,
            'campaigns_id' => $id,
'anonymous' => $request->has('anonymous') ? '1' : '0',
            'email' => $request->email,
            'donation' => $request->amount,
            'payment_gateway' => 'paypal',
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'comment' => $request->comment,
            'approved' => '0',
            'bank_transfer' => $receiptPath,
        ]);
//mailtoadmin
        $_title_site = $settings->title;
        Mail::send('emails.donationsAdmin', [
            'username' => $request->full_name,
            'title_site' => $_title_site,
            'amount'=>$request->amount,
            'email'=>$request->email,
            'campaign'=>$campaign,
        ], function ($message) use ($request,$_title_site) {
            $message->from('noreply@appointme.me', $_title_site);
            $message->to('donorthem@gmail.com',$request->full_name);
            $message->subject('donations');
        });
// dd($donate);

        return back()->with('success', 'Donation submitted successfully! Awaiting admin approval.');
    }
    // public function processPayPalPayment(Request $request, $id)
    // {
    //     try {
    //         // Fetch campaign and settings
    //         $campaign = Campaigns::findOrFail($id);
    //         $settings = AdminSettings::firstOrFail();

    //         // Retrieve PayPal credentials
    //         $paypalGateway = PaymentGateways::where('name', 'paypal')->firstOrFail();
    //         $paypalClientId = $paypalGateway->client_id;
    //         $paypalSecret = $paypalGateway->client_secret;

    //         // Set up PayPal API context
    //         $apiContext = new ApiContext(new OAuthTokenCredential($paypalClientId, $paypalSecret));
    //         $apiContext->setConfig(['mode' => config('paypal.mode', 'sandbox')]);

    //         // Validate request data
    //         $validation = Validator::make($request->all(), [
    //             'amount' => 'required|numeric|min:' . $settings->min_donation_amount . '|max:' . $settings->max_donation_amount,
    //             'full_name' => 'required|max:255',
    //             'email' => 'required|email|max:100',
    //             'country' => 'required',
    //             'postal_code' => 'required|max:10',
    //             'comment' => 'nullable|max:255',
    //         ]);

    //         if ($validation->fails()) {
    //             return back()->withErrors($validation)->withInput();
    //         }

    //         // PayPal Payer Info
    //         $payer = new Payer();
    //         $payer->setPaymentMethod("paypal");

    //         // Payment Amount
    //         $amount = new Amount();
    //         $amount->setCurrency("USD")
    //                ->setTotal($request->amount);

    //         // Transaction Details
    //         $transaction = new Transaction();
    //         $transaction->setAmount($amount)
    //                     ->setDescription("Donation to {$campaign->title}");

    //         // Redirect URLs
    //         $redirectUrls = new RedirectUrls();
    //         $redirectUrls->setReturnUrl(url('/paypal/success/'.$id))
    //                      ->setCancelUrl(url('/paypal/cancel/'.$id));

    //         // Payment Setup
    //         $payment = new Payment();
    //         $payment->setIntent("sale")
    //                 ->setPayer($payer)
    //                 ->setTransactions([$transaction])
    //                 ->setRedirectUrls($redirectUrls);

    //         // Create Payment
    //         try {
    //             $payment->create($apiContext);

    //         } catch (PayPalConnectionException $ex) {
    //             Log::error('PayPal API Error:', ['error' => $ex->getData()]);

    //         // return response()->json(['errro'=>$ex->getData()]);
    //         return back()->with('error', 'Payment could not be processed. Please try again later.');

    //         } catch (\Exception $ex) {
    //             Log::error('General Payment Error:', ['error' => $ex->getMessage()]);
    //             return back()->with('error', 'An error occurred while processing the payment.');
    //         }

    //             // Redirect user to PayPal approval link
    //     foreach($payment->getLinks() as $link) {
    //         if($link->getrel() == 'approval_url'){
    //         $redirect_url=$link->getHref();
    //         break;
    //         }
    //     }
    //     Session::put('paypal_payment_id', $payment->getId());
    //     if(isset($redirect_url )){
    //     return  Redirect::away($redirect_url);
    //     }
    //         // return redirect()->away($payment->getApprovalLink());

    //     } catch (\Exception $ex) {
    //         Log::error('Payment Processing Error:', ['error' => $ex->getMessage()]);
    //         return back()->with('error', 'Payment failed: ' . $ex->getMessage());
    //     }
    // }


    // public function paypalSuccess(Request $request)
    // {
    //     try {
    //         // Retrieve PayPal credentials
    //         $paypalGateway = PaymentGateways::where('name', 'paypal')->firstOrFail();
    //         $apiContext = new ApiContext(new OAuthTokenCredential($paypalGateway->client_id, $paypalGateway->client_secret));
    //         $apiContext->setConfig(['mode' => config('paypal.mode', 'sandbox')]);

    //         // Validate the PayPal response
    //         if (!$request->input('PayerID') || !$request->input('paymentId')) {
    //             return redirect()->route('donation.paypal')->with('error', 'Payment verification failed. Please try again.');
    //         }

    //         // Get the payment from PayPal
    //         $payment = Payment::get($request->input('paymentId'), $apiContext);
    //         $execution = new PaymentExecution();
    //         $execution->setPayerId($request->input('PayerID'));

    //         // Execute the payment
    //         $payment->execute($execution, $apiContext);

    //         // Retrieve session data
    //         if (!Session::has('full_name') || !Session::has('amount')) {
    //             return redirect()->route('donation.paypal')->with('error', 'Session expired. Please try again.');
    //         }

    //         // Store Donation Record
    //         Donations::create([
    //             'fullname'         => Session::get('full_name'),
    //             'campaigns_id'     => Session::get('campaign_id'),
    //             'email'            => Session::get('email'),
    //             'donation'         => Session::get('amount'),
    //             'payment_gateway'  => 'paypal',
    //             'country'          => Session::get('country'),
    //             'postal_code'      => Session::get('postal_code'),
    //             'comment'          => Session::get('comment'),
    //             'approved'         => '0',
    //         ]);

    //         // Clear the session data
    //         Session::forget(['full_name', 'email', 'campaign_id', 'amount', 'country', 'postal_code', 'comment']);

    //         return redirect()->route('donation.paypal')->with('success', 'Donation successful!');
    //     } catch (\Exception $ex) {
    //         Log::error('PayPal Success Error:', ['error' => $ex->getMessage()]);
    //         return redirect()->route('paypal.cancel')->with('error', $ex->getMessage());
    //     }
    // }


    // public function paypalCancel()
    // {
    //     return redirect()->route('donation.payment_options')->with('error', 'Payment was canceled.');
    // }




}
