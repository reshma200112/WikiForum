<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use App\Models\UserTag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    public $validation = 'formValidator.validations.1';
    // google user sign in
    public function googleLogin(Request $request)
    {
        try {
            $provider = "google";
            $clientToken = $request->input('access_token');
            $providerUser = Socialite::driver($provider)->userFromToken($clientToken);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }

        if (!User::where('email', '=', $providerUser['email'])->exists()) {
            $user = new User;
            $user->name = $providerUser['name'];
            $user->email = $providerUser['email'];
            $user->profile_picture = $providerUser['picture'];
            $user->type = 1;
            $user->status = 1;
            $user->save();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json([
                'message' => "user registered",
                'type' => $user->type,
                'token' => $token,
                'hasTag' => 'false'

            ]);
        }

        $user = User::where('email', $providerUser['email'])->first();
        if ($user->status == 2) {
            return response()->json([
                'message' => 'Account disabled',
                'error_code' => 1100
            ], 400);
        }
        $token = $user->createToken('MyApp')->accessToken;
        if ($providerUser['picture'] != $user->profile_picture) {
            $user->profile_picture = $providerUser['picture'];
            $user->save();
        }
        return response()->json([
            'message' => "user login successfully",
            'type' => $user->type,
            'token' => $token,
            'hasTag' => 'true'

        ]);


    }

    // Normal user login

    public function normalLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => Config::get($this->validation),
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $matchType = ['email' => $request->email];
        $user = User::where($matchType)->where('type', 0)->first();
        if (!$user) {
            return response()->json([
                'message' => 'user not registered',
                'error_code' => 1002
            ], 400);

        }
        if ($user->status == 2) {
            return response()->json([
                'message' => 'Account disabled',
                'error_code' => 1100
            ], 400);
        }
        $userTags = UserTag::where('user_id', $user->id)->first();
        if ($userTags) {
            $tagValue = 'true';
        } else {
            $tagValue = 'false';

        }
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'message' => "user login successfully",
                'type' => $user->type,
                'token' => $token,
                'status' => $user->status,
                'hasTag' => $tagValue
            ]);
        } else {
            return response()->json([
                'message' => 'incorrect password',
                'error_code' => 1003
            ], 400);

        }
    }

    // User registration
    public function registration(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|string|max:30',
                'email' => Config::get($this->validation),
                'password' =>
                'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'password_confirm' => 'required|same:password'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $user = User::where('email', $request->email)->first();
        if ($user && $user->status == 0) {
            $message = "email verification is pending";
            return response()->json([
                "message" => $message,
                "error_code" => 1001
            ], 400);
        } elseif ($user && $user->status == 1) {
            $message = "already registered";
            return response()->json([
                "message" => $message,
                "error_code" => 1111
            ], 400);
        } else {
            $user = new User;
            $userName = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $request->username);
            $user->name = $userName;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->type = 0;
            $user->status = 0;

            $user->save();
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json([
                "User" => $user,
                "token" => $token
            ]);
        }

    }

    public function confirmUser(Request $request)
    {

        $user = auth('api')->user();
        $randomString = Str::random(30);
        $user->verify_token = $randomString;
        $user->save();
        $subject = "User Registration";
        $testMailData = [
            'title' => 'Confirm Your Email Address',
            'body' => 'Click on the link below to confirm your email address.',
            'highlight' => 'Note:It’s a One Time Link, can’t reuse.',
            'generatedString' =>
            env('UI_BASE_URL').'/registration?token=' . $randomString . '/email=' . $user->email
        ];
        $this->index($user->email, $testMailData, $subject);
        return response()->json([
            "Message" => "Please find the confirmation link on registered email.Valid for 2 minutes "

        ]);
    }

    public function index($email, $testMailData, $subject)
    {
        Mail::to($email)->send(new SendMail($testMailData, $subject));
    }

    // User's Email token verification
    public function verifyToken(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => Config::get($this->validation),
                'token' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $matchRequest = ['email' => $request->email, 'type' => 0, 'status' => 0, 'verify_token' => $request->token];
        $user = User::where($matchRequest)->first();
        if (!$user) {
            return response()->json([
                'message' => 'link is no longer available',
                'error_code' => 1004
            ], 400);
        }
        $date = date('H:i:s', time());
        $endTime = new Carbon($user->updated_at);
        $getDiff = $endTime->diffInMinutes($date);
        if ($getDiff > 2) {
            return response()->json([
                'message' => 'link expired',
                'error_code' => 1005
            ], 400);
        } else {
            $user->status = 1;
            $user->verify_token = null;
            $user->save();
            return response()->json([
                "Message" => "user verified successfully"
            ]);
        }

    }

    // forgot Password
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $matchType = ['email' => $request->email, 'type' => 0];
        $user = User::where($matchType)->first();
        if (!$user) {
            return response()->json([
                'message' => 'invalid request',
                'error_code' => 1006
            ], 400);
        } else {
            $randomString = Str::random(30);
            $user->verify_token = $randomString;
            $user->save();
            $subject = "Request for reset password";
            $testMailData = [
                'title' => 'Request for reset password',
                'body' => 'Click on the below link to reset your password.
                            If you didn’t make this request, then you can ignore this email 🙂. ',

                'highlight' => 'Note:It’s a One Time Link, can’t reuse.',
                'generatedString' =>
                env('UI_BASE_URL').'/forgot-password?token=' . $randomString . '/email=' . $user->email,

            ];
            $this->index($user->email, $testMailData, $subject);
            return response()->json([
                "Message" => "Please find the reset link on registered email.Valid for 2 minutes"
            ]);
        }
    }
    public function checkDevToken(Request $request)
    {
        $response = Http::asForm()->post('https://wikiforum-api.innovaturelabs.com/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);
        return $response->json();
    }
}