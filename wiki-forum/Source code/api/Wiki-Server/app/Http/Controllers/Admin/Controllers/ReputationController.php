<?php

namespace App\Http\Controllers\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReputationScore;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ReputationController extends Controller
{
    public $illegalRequest = 'constants.errorMessages.3';
    public function addReputationScore(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;
        if ($adminStatus == 2) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'score' => 'required|integer|max:25'
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    $validator->errors()
                ], 400);
            }
            $value = $validator->validated();
            $reputationScore = new ReputationScore();
            $reputationScore->name = $value['name'];
            $reputationScore->score = $value['score'];
            $reputationScore->status = 1;
            $reputationScore->save();

            return response()->json([
                'success' => $reputationScore
            ]);
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }

    }

    public function getAllReputations()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;
        if ($adminStatus == 2) {
            return ReputationScore::where('status', 1)->get();
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }
    }

    public function getReputationById($id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;
        if ($adminStatus == 2) {
            $matchThese = ['id' => $id, 'status' => 1];
            return ReputationScore::where($matchThese)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }
    }

    public function editReputationScore(Request $request, $id)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $admin = User::where('id', $userId)->first();
        $adminStatus = $admin->type;
        if ($adminStatus == 2) {
            $validator = Validator::make(
                $request->all(),
                [
                    'score' => 'required|integer|max:25'
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    $validator->errors()
                ], 400);
            }
            $value = $validator->validated();
            $reputationScore = ReputationScore::find($id);
            return $this->reputationEdit($reputationScore, $value, $id);
        } else {
            return response()->json([
                "message" => Config::get($this->illegalRequest)
            ], 400);
        }
    }

    public function reputationEdit($reputationScore, $value, $id)
    {
        if ($reputationScore) {
            $message = 0;
            if ($id == 2) {
                $score = $value['score'];
                if ($score < 10) {
                    $message = "Minimum score must be 10";
                }
            } else {
                $score = $value['score'];
                if ($score < 5) {
                    $message = "Minimum score must be 5";
                }
            }
            if ($message != 0) {
                return response()->json([
                    "message" => $message
                ], 400);
            }
            $reputationScore->update($value);
            return response()->json([
                'message' => "Score updated successfully",
                'success' => $reputationScore
            ]);
        } else {
            return response()->json([
                'error' => 'Invalid Id'
            ], 404);
        }
    }
}
