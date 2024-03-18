<?php
namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Notifications;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comments;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommentController extends Controller
{
    public $invalidId = 'constants.errorMessages.2';
    public function addComment(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'integer',
                'question_id' => 'required_without:answer_id|integer',
                'answer_id' => 'required_without:question_id|integer',
                'comment' => 'required|string|max:600',
                'status' => 'integer'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }

        $userPoints = User::where('id', $userId)->first('points');
        $points = $userPoints->points;
        $value = $validator->validated();
        if ($request->filled('question_id')) {
            $toCheck = Question::where('id', $value['question_id'])->exists();
            $que = Question::where('id', $value['question_id'])->first();
            if ($que->status == 1) {
                $uid = $que->user_id;
                $flag = 0;
                return $this->comment($toCheck, $points, $value, $userId, $flag, $uid);
            } else {
                throw new BadRequestHttpException("Question is no longer available");
            }
        } elseif ($request->filled('answer_id')) {
            $toCheck = Answers::where('id', $value['answer_id'])->first();
            $qid = $toCheck->question_id;
            $que = Question::where('id', $qid)->value('status');
            if ($que == 1) {
                $uid = $toCheck->user_id;
                $flag = 1;
                return $this->comment($toCheck, $points, $value, $userId, $flag, $uid);
            } else {
                throw new BadRequestHttpException("Question is no longer available");
            }
        }

    }


    public function comment($toCheck, $points, $value, $userId, $flag, $uid)
    {
        if ($toCheck) {
            if ($points >= 25) {
                $value['user_id'] = $userId;
                $value['status'] = 1;
                $commentQuestion = Comments::create($value);
                if ($uid != $userId) {
                    $notification = new Notifications;
                    $notification->type = 4;
                    if ($flag == 1) {
                        $notification->answer_id = $value['answer_id'];
                        $notification->question_id = $toCheck->question_id;
                    } else {
                        $notification->question_id = $value['question_id'];
                    }
                    $notification->user_id = $uid;
                    $notification->posted_user_id = $userId;
                    $notification->status = 1;
                    $notification->save();
                }
                return response()->json([
                    'message' => 'Comment added successfully',
                    'comment' => $commentQuestion
                ]);
            } else {
                return response()->json(
                    [
                        "message" => "minimum 25 points required",
                        "error_code" => 1042
                    ],
                    400
                );
            }
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1031
            ], 400);
        }

    }

    public function viewCommentsQuestion(Request $request, $id)
    {
        if (is_numeric($id)) {
            $toCheck = Question::where('id', $id)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1033
            ], 400);
        }

        if ($toCheck) {
            return $this->viewQueComments($request, $id);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1033
            ], 400);
        }
    }
    public function viewQueComments($request, $id)
    {
        if ($request->has('perPage')) {
            $default = $request->perPage;
            $default = $default + 5;
            $conditions = ['question_id' => $id, 'status' => 1];
            $comment = Comments::where($conditions)->orderByDesc('id')->paginate($default);
            foreach ($comment as $c) {
                $uid = $c->user_id;
                $user = User::find($uid);
                $c->user_name = $user->name;
                $c->profile_picture = $user->profile_picture;
            }
            return response()->json(['comments' => $comment]);
        } else {
            $default = 3;
            $conditions = ['question_id' => $id, 'status' => 1];
            $comment = Comments::where($conditions)->orderByDesc('id')->paginate($default);
            foreach ($comment as $c) {
                $uid = $c->user_id;
                $user = User::find($uid);
                $c->user_name = $user->name;
                $c->profile_picture = $user->profile_picture;
            }
            return response()->json(['comments' => $comment]);
        }
    }

    public function GetCommentForEdit($id)
    {
        if (is_numeric($id)) {
            $conditions = ['id' => $id, 'status' => 1];
            $toCheck = Comments::where($conditions)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1037
            ], 400);
        }
        if ($toCheck) {
            return $toCheck;
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1037
            ], 400);
        }
    }


    // View Comments on answers
    public function viewComments(Request $request)
    {
        $answerId = $request->answerid;
        if (is_numeric($answerId)) {
            $checkId = Answers::where('id', $answerId)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1033
            ], 400);
        }
        if ($checkId) {
            if ($request->has('perPage')) {
                $ans = Answers::where('id', $answerId)->first();
                if ($ans) {
                    $default = $request->perPage;
                    $default = $default + 5;
                    $matchThese = ['answer_id' => $answerId, 'status' => 1];
                    $comment = Comments::where($matchThese)->orderByDesc('id')->paginate($default);
                }
            } else {
                $ans = Answers::where('id', $answerId)->first();
                if ($ans) {
                    $default = 3;
                    $matchThese = ['answer_id' => $answerId, 'status' => 1];
                    $comment = Comments::where($matchThese)->orderByDesc('id')->paginate($default);
                }

            }
            foreach ($comment as $c) {
                $uid = $c->user_id;
                $user = User::find($uid);
                $c->user_name = $user->name;
                $c->points = $user->points;
                $c->profile_picture = $user->profile_picture;
            }
            $ans->comment = $comment;
            return response()->json(['Answers' => $ans]);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1033
            ], 400);
        }
    }



    // Delete Comments
    public function deleteComments($commentId)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        if (is_numeric($commentId)) {
            $matchThese = ['id' => $commentId, 'status' => 1];
            $comment = Comments::where($matchThese)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1036
            ], 400);
        }
        if (!$comment) {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1036
            ], 400);
        }
        return $this->deleteCommentSave($comment, $userId);
    }

    public function deleteCommentSave($comment, $userId)
    {
        $commentUserId = $comment->user_id;
        if ($commentUserId == $userId) {
            $comment->status = 0;
            $comment->save();
            return response()->json([
                "Message" => "comment deleted successfully",
                "Answer_id" => $comment->answer_id
            ]);
        } else {
            return response()->json([
                "message" => Config::get('constants.errorMessages.6'),
                "error_code" => 1049
            ], 400);
        }
    }

    public function editComment(Request $request, $commentId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'comment' => 'required|string|max:600',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }

        $user = auth('api')->user();
        $userId = $user->id;
        if (is_numeric($commentId)) {
            $comment = Comments::where('id', $commentId)->first();
            if ($comment) {
                return $this->commentEditSave($userId, $comment, $request, $commentId);
            } else {
                $message = Config::get($this->invalidId);
            }
        } else {
            $message = Config::get($this->invalidId);
        }
        return response()->json([
            'message' => $message,
            "error_code" => 1043
        ], 400);
    }
    public function commentEditSave($userId, $comment, $request, $commentId)
    {
        if ($userId == $comment->user_id) {
            $input = $request->all();
            Comments::where('id', $commentId)->update($input);
            return response()->json(
                [
                    "Message" => 'comment updated',
                    "Answer_id" => $comment->answer_id
                ]
            );
        } else {
            return response()->json([
                'message' => 'Invalid Request',
                "error_code" => 1035
            ], 400);
        }
    }
}
