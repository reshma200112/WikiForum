<?php

namespace App\Http\Controllers\Admin\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public $validation = 'formValidator.validations.1';
    public $illegalRequest = 'constants.errorMessages.3';

    public function loggedUsers(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;


        $numbers = 12;
        // if sort =1 -> descending
        // if sort =2 -> ascending
        $sort = $request->sort;

        if ($adminStatus == 2) {
            if ($sort == 2) {
                $users = User::where('type', '<>', 2)
                    ->orderBy('points')
                    ->orderBy('id')->paginate($numbers);
            } else {
                $users = User::where('type', '<>', 2)
                    ->orderByDesc('points')
                    ->orderBy('id')->paginate($numbers);
            }
            foreach ($users as $user) {
                $user->join_date = $user->created_at->format('d-m-Y');
            }
            return response()->json([
                'users' => $users
            ]);
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }
    }


    // Search user
    public function userSearch(Request $request)
    {
        $numbers = 12;
        $data = $request->get('data');
        $sort = $request->sort;
        if ($sort == 2) {
            $users = User::where('type', '<>', 2)
                ->orderBy('points')
                ->orderBy('id')
                ->where(function ($q) use ($data) {
                    $q->where('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('email', 'LIKE', '%' . $data . '%');
                })
                ->paginate($numbers);
        } else {
            $users = User::where('type', '<>', 2)
                ->orderByDesc('points')
                ->orderBy('id')
                ->where(function ($q) use ($data) {
                    $q->where('name', 'LIKE', '%' . $data . '%')
                        ->orWhere('email', 'LIKE', '%' . $data . '%');
                })
                ->paginate($numbers);
        }
        foreach ($users as $user) {
            $user->join_date = $user->created_at->format('d-m-Y');
        }
        return response()->json([
            'users' => $users
        ]);

    }

    // to manage(enable/disable) users
    public function manageUser(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $validator = Validator::make(
            $request->all(),
            [
                'status' => 'required|numeric|in:1,2'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $data = $validator->validated();
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;
        $status = $data['status'];
        // if status =0 -> disabled
        // if status =1 -> enabled
        if ($adminStatus == 2) {
            return $this->saveUsers($status, $id);
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }
    }

    public function saveUsers($status, $id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            User::where('id', $id)->update(['status' => $status]);
            return response()->json([
                'message' => 'Status updated successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function getCount()
    {
        $total = User::where('type', '<>', 2)->count();

        $matchThese1 = ['type' => 1];
        $googleUsers = User::where($matchThese1)->count();

        $matchThese2 = ['type' => 0];
        $normalUsers = User::where($matchThese2)->count();

        $highUsers = User::where('points', '>', 1500)->count();

        return response()->json([
            "Total Users" => $total,
            "Google Users" => $googleUsers,
            "Normal Users" => $normalUsers,
            "High Reputation Users" => $highUsers
        ]);
    }
    public function adminLogin(Request $request)
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
        $matchType = ['email' => $request->email, 'type' => 2];
        $user = User::where($matchType)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'error_code' => 1002
            ], 400);

        }

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'message' => "Admin login successfully",
                'type' => $user->type,
                'token' => $token

            ]);
        } else {
            return response()->json([
                'message' => 'incorrect password',
                'error_code' => 1003
            ], 400);

        }
    }

    // To get count for pie chart in admin dashboard
    public function getAdminPieChartCount()
    {
        $totalNumberOfQuestions = Question::count();
        $totalAnsweredQuestions = Question::where('answer_count', '<>', 0)->count();
        $totalDeletedQuestions = Question::where('status', 0)->count();
        $totalClosedQuestions = Question::where('status', 2)->count();
        $totalUnAnsweredQuestions = Question::where('answer_count', 0)->count();
        $totalVerifiedAnswerQuestions = Question::where('answer_count', 0)->count();
        return response()->json([
            'total_question' => $totalNumberOfQuestions,
            'total_deleted_questions' => $totalDeletedQuestions,
            'total_closed_questions' => $totalClosedQuestions,
            'total_answered_questions' => $totalAnsweredQuestions,
            'total_unanswered_questions' => $totalUnAnsweredQuestions,
            'total_question_has_verified_answer' => $totalVerifiedAnswerQuestions
        ]);
    }
}