<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Question;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    const DATE_TIME_FORMAT = 'd-m-Y h-i-s A';

    public function __construct()
    {
        date_default_timezone_set("Asia/Kolkata");
    }

    // get current user details

    public function getUserDetails()
    {
        $user = Auth::user();

        return response()->json([
            'message' => "Current user details",
            'user' => $user,

        ]);

    }

    // get current user points
    public function getPoints()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        if ($userId) {
            $points = User::where('id', $userId)->value('points');
            return response()->json([
                'Points' => $points,
            ]);
        } else {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
    }

    public function editUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:30',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $user = auth('api')->user();
        $userId = $user->id;
        $userProfile = User::find($userId);
        $getTrimmedUser = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $request->name);
        $userProfile->name = $getTrimmedUser;
        $userProfile->save();
        return response()->json(
            [
                'User Updated'
            ]
        );
    }

    //get all users
    public function getAllUsers(Request $request)
    {
        $numbers = 12;
        // if sort =1 -> descending
        // if sort =2 -> ascending
        $sort = $request->sort;
        if ($sort == 2) {
            $users = User::where('status', 1)
                ->where('type', '<>', 2)
                ->orderBy('points')
                ->orderBy('id')->paginate($numbers);
        } else {
            $users = User::where('status', 1)
                ->where('type', '<>', 2)
                ->orderByDesc('points')
                ->orderBy('id')->paginate($numbers);
        }
        return response()->json([
            'users' => $users
        ]);
    }

    // Search user
    public function searchUser(Request $request)
    {
        $numbers = 12;
        $data = $request->get('data');
        $sort = $request->sort;
        if ($sort == 2) {
            $users = User::orderBy('points')
                ->orderBy('id')
                ->where('status', 1)
                ->where('type', '<>', 2)
                ->where('name', 'LIKE', '%' . $data . '%')
                ->paginate($numbers);
        } else {
            $users = User::orderByDesc('points')
                ->orderBy('id')
                ->where('status', 1)
                ->where('type', '<>', 2)
                ->where('name', 'LIKE', '%' . $data . '%')
                ->paginate($numbers);
        }
        return response()->json([
            'users' => $users
        ]);

    }

    protected function respondedWithToken($token)
    {
        return response()->json([
            'Success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    // refresh-token api method
    public function refreshToken()
    {
        if (auth()->user()) {
            return $this->respondedWithToken(auth()->refreshToken());
        } else {
            return response()->json([
                "Success" => false,
                "Message" => "User is not Authenticated"
            ]);
        }

    }

    public function popularUsers()
    {
        return User::where('status', 1)
            ->orderBy('points', 'desc')
            ->select('name', 'profile_picture', 'points')
            ->get()->take(4);

    }

    // change password

    public function changePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => 'required',
                'new_password' =>
                'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'password_confirm' => 'required|same:new_password'
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $user = auth('api')->user();
        if ($user->type != 0) {
            return response()->json([
                'message' => 'User is unable to change password',
                'error_code' => 1009
            ], 400);
        } elseif (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect password',
                'error_code' => 1011
            ], 400);
        } elseif (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'message' => 'Old password and new password should not be same',
                'error_code' => 1010
            ], 400);

        } else {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                "Message" => "Password changed successfully"
            ]);
        }

    }



    // Verify forgot password token
    public function verifyForgotPasswordToken(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'token' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }

        $matchRequest = ['email' => $request->email, 'type' => 0, 'verify_token' => $request->token];
        $user = User::where($matchRequest)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Link is no longer available.',
                'error_code' => 1008
            ], 400);
        }

        $date = date('H:i:s', time());
        $endTime = new Carbon($user->updated_at);
        $getDiff = $endTime->diffInMinutes($date);
        if ($getDiff > 2) {
            return response()->json([
                'message' => 'Link expired',
                'error_code' => 1007
            ], 400);
        } else {
            return response()->json([
                "Success" => "true",
                // "Message" => "Password reset successfully"
            ]);
        }

    }

    // Reset password

    public function resetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'token' => 'required',
                'new_password' =>
                'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'password_confirm' => 'required|same:new_password'
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }

        $matchRequest = ['email' => $request->email, 'type' => 0, 'verify_token' => $request->token];
        $user = User::where($matchRequest)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Link is no longer available.',
                'error_code' => 1008
            ], 400);
        }
        $date = date('H:i:s', time());
        $endTime = new Carbon($user->updated_at);
        $getDiff = $endTime->diffInMinutes($date);
        if ($getDiff > 2) {
            return response()->json([
                'message' => 'Link expired',
                'error_code' => 1007
            ], 400);
        } else {
            $user->verify_token = null;
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                "Success" => "true",
                "Message" => "Password reset successfully"
            ]);
        }

    }

    // Upload image for normal users
    public function uploadImage(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpeg,jpg,png,gif|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $validator->errors()
                ], 400);
            } else {
                $extention = $request->file('image')->extension();

                $uniqueId = date(self::DATE_TIME_FORMAT);
                $fileName = $uniqueId . '.' . $extention;
                $path = $request->file('image')
                    ->storeAs('profile_photos', $fileName, 'fileUploadToPublic');
                $user = auth('api')->user();
                $user->profile_picture = env('FILE_UPLOAD_PATH') . $path;
                $user->save();
                return response()->json([
                    'message' => 'profile photo uploaded successfully'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => 'false',
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function getUserImpact()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $totalUpvoteForAnswers = Answers::where('user_id', $userId)->sum('upvote');
        $totalPostedQuestionViews = Question::where('user_id', $userId)->sum('views');
        return response()->json([
            'total_upvote_for_answers' => $totalUpvoteForAnswers,
            'total_posted_question_views' => $totalPostedQuestionViews
        ]);

    }


}