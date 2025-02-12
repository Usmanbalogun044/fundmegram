<?php

namespace App\Http\Controllers;

use Mail;
use App\Helper;
use Carbon\Carbon;
use App\Models\Like;
use App\Models\User;
use App\Models\Updates;
use App\Models\Campaigns;
use App\Models\Donations;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\PaymentGateways;
use App\Models\CampaignsReported;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class CampaignsController extends Controller
{

	public function __construct(AdminSettings $settings, Request $request)
	{
		$this->settings = $settings::first();
		$this->request = $request;
	}

	protected function validator(array $data, $id = null)
	{

		Validator::extend('ascii_only', function ($attribute, $value, $parameters) {
			return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		Validator::extend('text_required', function ($attribute, $value, $parameters) {
			$value = preg_replace("/\s|&nbsp;/", '', $value);
			return strip_tags($value);
		});

		Validator::extend('video_url', function ($attribute, $value, $parameters) {

			$urlValid = filter_var($value, FILTER_VALIDATE_URL) ? true : false;

			if ($urlValid) {
				$parse = parse_url($value);
				$host  = strtolower($parse['host']);
				if ($host) {
					if (in_array($host, [
						'youtube.com',
						'www.youtube.com',
						'youtu.be',
						'www.youtu.be',
						'vimeo.com',
						'player.vimeo.com'
					])) {
						return true;
					}
				}
			}
		});

		$sizeAllowed = $this->settings->file_size_allowed * 1024;
		$dimensions = explode('x', $this->settings->min_width_height_image);

		if ($this->settings->currency_position == 'right') {
			$currencyPosition =  2;
		} else {
			$currencyPosition =  null;
		}

		$messages = array(
			'photo.required' => trans('misc.please_select_image'),
			'categories_id.required' => trans('misc.please_select_category'),
			'description.required' => trans('misc.description_required'),
			'description.text_required' => trans('misc.text_required'),
			"video.video_url" => trans('misc.video_url_invalid'),
			'goal.min' => trans('misc.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'goal.max' => trans('misc.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

			"photo.max"   => trans('misc.max_size') . ' ' . Helper::formatBytes($sizeAllowed, 1),
		);

		// Create Rules
		if ($id == null) {
			return Validator::make($data, [
				'photo'           => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=' . $dimensions[0] . ',min_height=' . $dimensions[1] . '|max:' . $this->settings->file_size_allowed . '',
				'title'             => 'required|min:3|max:90',
				'categories_id'  => 'required',
				'goal'             => 'required|integer|max:' . $this->settings->max_campaign_amount . '|min:' . $this->settings->min_campaign_amount,
				'location'        => 'required|max:50',
				'description'  => 'required|min:20|text_required',
				'video' => 'url|video_url',
			], $messages);

			// Update Rules
		} else {
			return Validator::make($data, [
				'photo'           => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=' . $dimensions[0] . ',min_height=' . $dimensions[1] . '|max:' . $this->settings->file_size_allowed . '',
				'title'             => 'required|min:3|max:90',
				'categories_id'  => 'required',
				'goal'             => 'required|integer|max:' . $this->settings->max_campaign_amount . '|min:' . $this->settings->min_campaign_amount,
				'location'        => 'required|max:50',
				'description'  => 'required|min:20|text_required',
				'video'    => 'url|video_url',
			], $messages);
		}
	}

	public function create()
	{
		$temp = 'public/temp/';
		$pathSmall = 'public/campaigns/small/';
		$pathLarge = 'public/campaigns/large/';

		$input = $this->request->all();
		$validator = $this->validator($input);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		} //<-- Validator

		$extension = $this->request->file('photo')->extension();
		$fileLarge = strtolower(auth()->id() . time() . str_random(40) . '.' . $extension);
		$fileSmall = strtolower(auth()->id() . time() . str_random(35) . '.' . $extension);

		if (!$this->request->file('photo')->move($temp, $fileLarge)) {
			return response()->json([
				'success' => false,
				'errors' => ['error' => __('misc.image_upload_error')],
			]);
		}

		$image = Image::read($temp . $fileLarge);
		$maxWidth = ($image->width() < $image->height()) ? 400 : 800;

		$image->scale(width: $maxWidth)
			->encodeByExtension($extension)
			->save($pathLarge . $fileLarge);

		// Thumbnail
		$image->cover(width: 400, height: 300)
			->encodeByExtension($extension)
			->save($pathSmall . $fileSmall);

		\File::delete($temp . $fileLarge);

		$imageSmall  = $fileSmall;
		$imageLarge  = $fileLarge;

		//<= HTML Clean
		$configuration = [
			'HTML.Allowed' => 'iframe[src|width|height],strong,em,a[href],ul,ol,li,br,img[src|width|height]',
			"HTML.SafeIframe" => true,
			"URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
		];

		$description = \Purify::clean($this->request->description, $configuration);

		$description = trim(Helper::spaces($description));

		$_status = $this->settings->auto_approve_campaigns == '0' ? 'pending' : 'active';

		$sql                = new Campaigns;
		$sql->title         = trim($this->request->title);
		$sql->small_image   = $imageSmall;
		$sql->large_image   = $imageLarge;
		$sql->description   = Helper::removeBR($description);
		$sql->user_id       = auth()->id();
		$sql->date          =   $this->request->date;
		$sql->status        = $_status;
		$sql->token_id      = str_random(200);
		$sql->goal          = trim($this->request->goal);
		$sql->location      = trim($this->request->location);
		$sql->categories_id = $this->request->categories_id;
		$sql->deadline      = $this->request->deadline;
		$sql->video         = $this->request->video;
		$sql->save();

		$_target = $this->settings->auto_approve_campaigns == '0' ? url('dashboard/campaigns') : url('campaign', $sql->id);

		return response()->json([
			'success' => true,
			'target' => $_target,
		]);
	} //<<--- End Method


	public function view($id, $slug = null)
	{

		$response = Campaigns::where('id', $id)->where('status', 'active')->firstOrFail();

		$data = Campaigns::where('status', 'active')
			->where('id', '<>', $response->id)
			->where('finalized', '0')
			->whereCategoriesId($response->categories_id)
			->inRandomOrder()
			->paginate(3);

		$uriCampaign = $this->request->path();

		if (str_slug($response->title) == '') {
			$slugUrl  = '';
		} else {
			$slugUrl  = '/' . str_slug($response->title);
		}

		$url_campaign = 'campaign/' . $response->id . $slugUrl;

		//<<<-- * Redirect the user real page * -->>>
		$uriCanonical = $url_campaign;

		if ($uriCampaign != $uriCanonical) {
			return redirect($uriCanonical);
		}

		$percentage = number_format($response->donations()->sum('donation') / $response->goal * 100, 2, '.', '');

		// All Donations
		$donations = $response->donations()->orderBy('id', 'desc')->paginate(10);

		// Updates
		$updates = $response->updates()->orderBy('id', 'desc')->paginate(5);

		if (str_slug($response->title) == '') {
			$slug_url  = '';
		} else {
			$slug_url  = '/' . str_slug($response->title);
		}

		if (Auth::check()) {
			// LIKE ACTIVE
			$likeActive = Like::where('user_id', auth()->id())
				->where('campaigns_id', $response->id)
				->where('status', '1')
				->first();

			if ($likeActive) {
				$textLike   = trans('misc.unlike');
				$icoLike    = 'fa fa-heart';
				$statusLike = 'active';
			} else {
				$textLike   = trans('misc.like');
				$icoLike    = 'far fa-heart';
				$statusLike = '';
			}
		}

		// Deadline
		$timeNow = strtotime(Carbon::now());

		if ($response->deadline != '') {
			$deadline = strtotime($response->deadline);

			$date = strtotime($response->deadline);
			$remaining = $date - $timeNow;

			$days_remaining = floor($remaining / 86400);
		}

		return view('campaigns.view')
			->with([
				'response' => $response,
				'data' => $data,
				'percentage' => $percentage,
				'donations' => $donations,
				'updates' => $updates,
				'slug_url' => $slug_url,
				'textLike' => $textLike ?? null,
				'icoLike' => $icoLike ?? null,
				'statusLike' => $statusLike ?? null,
				'timeNow' => $timeNow,
				'deadline' => $deadline ?? null,
				'remaining' => $remaining ?? null,
				'days_remaining' => $days_remaining ?? null,
			]);
	} // End Method

	public function contactOrganizer()
	{
		$settings  = AdminSettings::first();

		$emailUser = User::find($this->request->id);

		if ($emailUser->email == '') {
			return response()->json([
				'success' => false,
				'error_fatal' => trans('misc.error'),
			]);
		}

		$validator = Validator::make($this->request->all(), [
			'name'       => 'required|max:30',
			'email'       => 'required|email',
			'message'       => 'required|min:10',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		}

		$sender = $settings->email_no_reply;
		$replyTo = $this->request->email;
		$user    = $this->request->name;
		$titleSite = $settings->title;
		$data = $this->request->message;
		$_emailUser = $emailUser->email;
		$_nameUser = $emailUser->name;

		Mail::send(
			'emails.contact-organizer',
			array('data' => $data),
			function ($message) use ($sender, $replyTo, $user, $titleSite, $_emailUser, $_nameUser) {
				$message->from($sender, $titleSite)
					->to($_emailUser, $_nameUser)
					->replyTo($replyTo, $user)
					->subject($titleSite . ' - ' . $user);
			}
		);

		return response()->json([
			'success' => true,
			'msg' => trans('misc.msg_success'),
		]);
	} // End Method

	public function edit($id)
	{
		$data = Campaigns::where('id', $this->request->id)
			->where('finalized', '0')
			->where('user_id', auth()->id())
			->firstOrFail();
// dd($data);

		return view('campaigns.edit')->withData($data);
	} //<---- End Method

	public function post_edit()
	{
		$sql = Campaigns::where('id', $this->request->id)->where('finalized', '0')->first();

		if (!isset($sql)) {
			return response()->json([
				'fatalError' => true,
				'target' => url('/'),
			]);
		}

		// PATHS
		$temp = 'public/temp/';
		$path_small = 'public/campaigns/small/';
		$path_large = 'public/campaigns/large/';

		// Old images
		$old_small = $path_small . $sql->small_image;
		$old_large = $path_large . $sql->large_image;

		$image_small = $sql->small_image;
		$image_large = $sql->large_image;


		$input      = $this->request->all();
		$validator = $this->validator($input, $sql->id);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		} //<-- Validator

		if ($this->request->hasFile('photo')) {

			$extension = $this->request->file('photo')->extension();
			$fileLarge = strtolower(auth()->id() . time() . str_random(40) . '.' . $extension);
			$fileSmall = strtolower(auth()->id() . time() . str_random(35) . '.' . $extension);

			if (!$this->request->file('photo')->move($temp, $fileLarge)) {
				return response()->json([
					'success' => false,
					'errors' => ['error' => __('misc.image_upload_error')],
				]);
			}

			$image = Image::read($temp . $fileLarge);
			$maxWidth = ($image->width() < $image->height()) ? 400 : 800;

			$image->scale(width: $maxWidth)
				->encodeByExtension($extension)
				->save($path_large . $fileLarge);

			// Thumbnail
			$image->cover(width: 400, height: 300)
				->encodeByExtension($extension)
				->save($path_small . $fileSmall);

			\File::delete($temp . $fileLarge);

			// Delete Old Images
			\File::delete($old_large);
			\File::delete($old_small);

			$image_small  = $fileSmall;
			$image_large  = $fileLarge;
		}

		if (isset($this->request->finish_campaign)) {
			$finish_campaign = '1';
			$endCampaign = true;
		} else {
			$finish_campaign = '0';
			$endCampaign = false;
		}

		//<= HTML Clean
		$configuration = [
			'HTML.Allowed' => 'iframe[src|width|height],strong,em,a[href],ul,ol,li,br,img[src|width|height]',
			"HTML.SafeIframe" => true,
			"URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
		];

		$description = \Purify::clean($this->request->description, $configuration);

		$description = trim(Helper::spaces($description));

		$sql->title                = trim($this->request->title);
		$sql->small_image   = $image_small;
		$sql->large_image   = $image_large;
		$sql->description     = Helper::removeBR($description);
		$sql->user_id          = auth()->id();
		$sql->goal               = trim($this->request->goal);
		$sql->location          = trim($this->request->location);
		$sql->finalized          = $finish_campaign;
		$sql->categories_id  = $this->request->categories_id;
		$sql->video         = $this->request->video;
        $sql->date          =   $this->request->date;
        $sql->deadline      = $this->request->deadline;
		$sql->save();

		return response()->json([
			'success' => true,
			'target' => url('campaign', $sql->id),
			'finish_campaign' => $endCampaign

		]);
	} //<<--- End Method

	public function delete($id)
	{

		$data = Campaigns::where('id', $this->request->id)
			->where('user_id', auth()->id())
			->firstOrFail();

		$path_small     = 'public/campaigns/small/';
		$path_large     = 'public/campaigns/large/';
		$path_updates = 'public/campaigns/updates/';

		$updates = $data->updates()->get();

		//Delete Updates
		foreach ($updates as $key) {

			if (\File::exists($path_updates . $key->image)) {
				\File::delete($path_updates . $key->image);
			} //<--- if file exists

			$key->delete();
		} //<--

		// Delete Campaign
		if (\File::exists($path_small . $data->small_image)) {
			\File::delete($path_small . $data->small_image);
		} //<--- if file exists

		if (\File::exists($path_large . $data->large_image)) {
			\File::delete($path_large . $data->large_image);
		} //<--- if file exists

		//Delete campaign Reported
		$campaignReporteds = CampaignsReported::where('campaigns_id', $this->request->id)->get();

		if ($campaignReporteds) {
			foreach ($campaignReporteds as $campaignReported) {
				$campaignReported->delete();
			} //<-- foreach
		} // IF

		$data->delete();

		return redirect('/');
	} //<<--- End Method


	public function update($id)
	{

		$data = Campaigns::where('id', $this->request->id)
			->where('user_id', auth()->id())
			->firstOrFail();

		return view('campaigns.update')->withData($data);
	} //<---- End Method

	public function post_update()
	{
		$temp   = 'public/temp/';
		$path   = 'public/campaigns/updates/';

		$sizeAllowed = $this->settings->file_size_allowed * 1024;
		$dimensions = explode('x', $this->settings->min_width_height_image);

		$input      = $this->request->all();
		$validator = Validator::make($input, [
			'photo' => 'mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=' . $dimensions[0] . ',min_height=' . $dimensions[1] . '|max:' . $this->settings->file_size_allowed . '',
			'description'  => 'required|min:20',
		]);

		$image = '';

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		} //<-- Validator

		if ($this->request->hasFile('photo')) {
			$extension = $this->request->file('photo')->extension();
			$file = strtolower(auth()->id() . time() . str_random(40) . '.' . $extension);

			if (!$this->request->file('photo')->move($temp, $file)) {
				return response()->json([
					'success' => false,
					'errors' => ['error' => __('misc.image_upload_error')],
				]);
			}

			$image = Image::read($temp . $file);
			$maxWidth = ($image->width() < $image->height()) ? 600 : 800;

			$image->scale(width: $maxWidth)
				->encodeByExtension($extension)
				->save($path . $file);

			\File::delete($temp . $file);

			$image  = $file;
		}

		$sql = new Updates;
		$sql->image  = $image;
		$sql->description = trim(Helper::checkTextDb($this->request->description));
		$sql->campaigns_id = $this->request->id;
		$sql->date = Carbon::now();
		$sql->token_id = str_random(200);
		$sql->save();

		return response()->json([
			'success' => true,
			'msg' => trans('misc.success_add_update'),
			'target' => url('campaign', $this->request->id),
		]);
	} //<---- End Method

	public function edit_update($id)
	{

		$data = Updates::where('id', $id)->firstOrFail();

		if ($data->campaigns()->user_id != auth()->id()) {
			abort('404');
		}

		return view('campaigns.edit-update')->withData($data);
	} //<---- End Method

	public function post_edit_update()
	{
		$sql = Updates::find($this->request->id);

		$temp = 'public/temp/';
		$path = 'public/campaigns/updates/';

		$image = $sql->image;

		$sizeAllowed = $this->settings->file_size_allowed * 1024;
		$dimensions = explode('x', $this->settings->min_width_height_image);

		$input = $this->request->all();
		$validator = Validator::make($input, [
			'photo' => 'nullable|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=' . $dimensions[0] . ',min_height=' . $dimensions[1] . '|max:' . $this->settings->file_size_allowed . '',
			'description'  => 'required|min:20',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		} //<-- Validator

		if ($this->request->hasFile('photo')) {
			$extension = $this->request->file('photo')->extension();
			$file = strtolower(auth()->id() . time() . str_random(40) . '.' . $extension);

			if (!$this->request->file('photo')->move($temp, $file)) {
				return response()->json([
					'success' => false,
					'errors' => ['error' => __('misc.image_upload_error')],
				]);
			}

			$image = Image::read($temp . $file);
			$maxWidth = ($image->width() < $image->height()) ? 600 : 800;

			$image->scale(width: $maxWidth)
				->encodeByExtension($extension)
				->save($path . $file);

			\File::delete($temp . $file);

			// Delete Old Images
			if (\File::exists($path . $sql->image)) {
				\File::delete($path . $sql->image);
			}

			$image = $file;
		}

		$sql->image = $image;
		$sql->description = trim(Helper::checkTextDb($this->request->description));
		$sql->save();

		return response()->json([
			'success' => true,
			'target' => url('campaign', $sql->campaigns_id),
		]);
	} //<---- End Method


	public function delete_image_update()
	{

		$res = Campaigns::where('id', $this->request->id)
			->where('user_id', auth()->id())
			->first();

		$path = 'public/campaigns/updates/';

		$data = Updates::where('id', $this->request->id)->first();

		if (isset($data)) {
			if (\File::exists($path . $data->image)) {
				\File::delete($path . $data->image);
			} //<--- IF FILE EXISTS

			$data->image = '';
			$data->save();
		}
	} //<--- End Method

	// Dashboard
	public function dashboard()
	{
		// Campaigns
		$campaigns = Campaigns::whereUserId(auth()->id())->orderBy('id', 'DESC');

		$donations = Donations::leftJoin('campaigns', function ($join) {
			$join->on('donations.campaigns_id', '=', 'campaigns.id');
		})
			->where('campaigns.user_id', auth()->id())
			->where('donations.approved', '1')
			->select('donations.*')
			->addSelect('campaigns.id')
			->addSelect('campaigns.title')
			->orderBy('donations.id', 'DESC');

		//  Calcule Chart Donations and Funds last 30 days
		for ($i = 0; $i <= 30; ++$i) {

			$date = date('Y-m-d', strtotime('-' . $i . ' day'));

			// Earnings last 30 days
			$fundsRaised = Donations::leftJoin('campaigns', function ($join) {
				$join->on('donations.campaigns_id', '=', 'campaigns.id');
			})
				->where('campaigns.user_id', auth()->id())
				->where('donations.approved', '1')
				->whereDate("donations.date", '=', $date)->sum('donation');

			// Donations Last 30 days
			$donationsLast30 = Donations::leftJoin('campaigns', function ($join) {
				$join->on('donations.campaigns_id', '=', 'campaigns.id');
			})
				->where('campaigns.user_id', auth()->id())
				->where('donations.approved', '1')
				->whereDate("donations.date", '=', $date)->count();

			// Format Date on Chart
			$formatDate = Helper::formatDateChart($date);
			$monthsData[] =  "'$formatDate'";

			// Earnings last 20 days
			$earningNetUserSum[] = $fundsRaised;

			// Earnings last 20 days
			$lastDonations[] = $donationsLast30;
		}

		$label = implode(',', array_reverse($monthsData));
		$data = implode(',', array_reverse($earningNetUserSum));

		$datalastDonations = implode(',', array_reverse($lastDonations));

		return view('users.dashboard', [
			'total_campaigns' => $campaigns->count(),
			'campaigns' => $campaigns->paginate(1),
			'donations' => $donations,
			'label' => $label,
			'data' => $data,
			'datalastDonations' => $datalastDonations
		]);
	} //<--- End Method

	public function withdrawal()
{
    if (
        (Auth::user()->payment_gateway == 'Paypal' && empty(Auth::user()->paypal_account)) ||
        (Auth::user()->payment_gateway == 'Bank' && empty(Auth::user()->bank)) ||
        (Auth::user()->payment_gateway == 'Zelle' && empty(Auth::user()->zelle)) ||
        (Auth::user()->payment_gateway == 'Venmo' && empty(Auth::user()->venmo)) ||
        (Auth::user()->payment_gateway == 'Apple Pay' && empty(Auth::user()->apple_pay)) ||
        (Auth::user()->payment_gateway == 'Crypto' && empty(Auth::user()->crypto_wallet)) ||
        (Auth::user()->payment_gateway == 'international_bank' && empty(Auth::user()->international_bank)) ||
        empty(Auth::user()->payment_gateway)
    ) {
        \Session::flash('notification', trans('misc.configure_withdrawal_method'));
        return redirect('dashboard/campaigns');
    }

    $res = Campaigns::where('id', $this->request->id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $WithdrawalsExists = Withdrawals::where('campaigns_id', $this->request->id)->first();

    if (empty($WithdrawalsExists)) {
        $funds = 0;
        foreach ($res->donations as $key) {
            foreach (PaymentGateways::all() as $payment) {
                $paymentGatewayDonation = strtolower($key->payment_gateway);
                if ($paymentGatewayDonation == strtolower($payment->name)) {
                    $_amountGlobal = $key->donation - ($key->donation * $payment->fee / 100) - $payment->fee_cents;
                    $funds += $_amountGlobal - ($_amountGlobal * $this->settings->fee_donation / 100);
                }
            }
        }

        $_account = match (Auth::user()->payment_gateway) {
            'Paypal' => Auth::user()->paypal_account,
            'Bank' => Auth::user()->bank,
            'Zelle' => Auth::user()->zelle,
            'Venmo' => Auth::user()->venmo,
            'Apple Pay' => Auth::user()->apple_pay,
            'Crypto' => Auth::user()->crypto_wallet,
'international_bank' => Auth::user()->international_bank,
            default => null,
        };

        $sql = new Withdrawals;
        $sql->campaigns_id = $res->id;
        $sql->amount = $this->settings->currency_code == 'JPY' ? round($funds) : number_format($funds, 2);
        $sql->gateway = Auth::user()->payment_gateway;
        $sql->account = $_account;
        $sql->save();

        return redirect('dashboard/withdrawals');
    } else {
        return redirect('dashboard/campaigns');
    }
}

	public function show_withdrawal()
	{


		$withdrawals = Withdrawals::leftJoin('campaigns', function ($join) {
			$join->on('withdrawals.campaigns_id', '=', 'campaigns.id');
		})
			->where('campaigns.user_id', auth()->id())
			->select('withdrawals.*')
			->addSelect('campaigns.title')
			->orderBy('withdrawals.id', 'DESC')
			->paginate(20);

		return view('users.withdrawals')->withWithdrawals($withdrawals);
	} //<--- End Method

    public function withdrawalConfigure()
    {
        $allowedTypes = ['paypal', 'bank', 'zelle', 'venmo', 'applepay', 'crypto','international_bank'];
        if (!in_array($this->request->type, $allowedTypes)) {
            \Session::flash('error', trans('misc.error'));
            return redirect('dashboard/withdrawals/configure');
        }

        $rules = match ($this->request->type) {
            'paypal' => ['email_paypal' => 'required|email|confirmed'],
            'bank' => ['bank' => 'required'],
            'zelle' => [
                        'zelle_name' => 'required',
                        'zelle_contact' => 'required'
                    ],
            'venmo' => [
                'venmo_name' => 'required',
                'venmo_phone' => 'required'
            ],
            'applepay' => [
                'apple_name' => 'required',
                'apple_id' => 'required'
            ],
            'crypto' => [
                'crypto_type' => 'required',
                'crypto_network' => 'required',
                'crypto_wallet' => 'required',
            ],
            'international_bank ' => [
                'international_bank' => 'required',
            ],
            default => []
        };

        $this->validate($this->request, $rules);

        $user = User::find(auth()->id());

        match ($this->request->type) {
            'paypal' => $user->paypal_account = $this->request->email_paypal,
            'bank' => $user->bank = $this->request->bank,
            'international_bank ' => $user->international_bank  = $this->request->international_bank ,
            'zelle' => [
                 $user->zelle = json_encode([
                 'zelle_name' => $this->request->zelle_name,
                'zelle_contact' => $this->request->zelle_contact,
            ])],
            'venmo' => [
                $user->venmo = json_encode([
                    'name' => $this->request->venmo_name,
                    'phone' => $this->request->venmo_phone
                ])
            ],
            'applepay' =>  [
                $user->apple_pay = json_encode([
                    'apple_name' => $this->request->apple_name,
                    'apple_id' =>  $this->request->apple_id,
                ])],
            'crypto' => [
                $user->crypto_wallet= json_encode([
                    'crypto_type' => $this->request->crypto_type,
                    'crypto_network' => $this->request->crypto_network,
                    'crypto_wallet' => $this->request->crypto_wallet,
                ])],
            default => null
        };

        $user->payment_gateway = ucfirst($this->request->type);
        $user->save();

        \Session::flash('success', trans('admin.success_update'));
        return redirect('dashboard/withdrawals/configure');
    }



	public function withdrawalDelete()
	{

		$withdrawal = Withdrawals::find($this->request->id);

		if (isset($withdrawal)) {

			$Campaigns = Campaigns::where('id', $withdrawal->campaigns_id)
				->where('user_id', auth()->id())
				->first();

			if (isset($Campaigns)) {

				$withdrawal->delete();
				return redirect('dashboard/withdrawals');
			}
		} // Isset withdrawal

	} //<--- End Method

	public function report()
	{

		if ($this->request->user == auth()->id()) {
			return redirect('/');
		}

		$data = CampaignsReported::firstOrNew(['user_id' => auth()->id(), 'campaigns_id' => $this->request->id]);

		if ($data->exists) {
			\Session::flash('noty_error', 'error');
			return redirect()->back();
		} else {

			$data->save();
			\Session::flash('noty_success', 'success');
			return redirect()->back();
		}
	} //<--- End Method

	public function donations()
	{
		$data = auth()->user()->donationsReceived()
			->orderBy('donations.id', 'DESC')
			->paginate(20);

		return view('users.donations')->withData($data);
	} //<--- End Method

	public function campaigns()
	{
		$_data = Campaigns::where('user_id', auth()->id())
			->orderBy('id', 'DESC')
			->paginate(20);

		// Deadline
		$timeNow = strtotime(Carbon::now());

		foreach ($_data as $key) {
			if ($key->deadline != '') {
				$_deadline = strtotime($key->deadline);

				if ($_deadline < $timeNow && $key->finalized == '0') {
					$sql = Campaigns::find($key->id);
					$sql->finalized = '1';
					$sql->save();
				}
			}
		}

		$data = Campaigns::where('user_id', auth()->id())
			->orderBy('id', 'DESC')
			->paginate(20);

		return view('users.campaigns')->withData($data);
	} //<--- End Method


}
