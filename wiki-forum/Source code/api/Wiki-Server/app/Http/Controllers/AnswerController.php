<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Notifications;
use App\Models\Question;
use App\Models\ReputationScore;
use App\Models\Tag;
use App\Models\UserVote;
use App\Models\Comments;
use App\Models\QuestionTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;



class AnswerController extends Controller
{
    public $invalidId = 'constants.errorMessages.2';
    public $badRequest = 'constants.errorMessages.6';
    // Add answer to question
    public function addAnswer(Request $request, $questionId)
    {

        $user = auth('api')->user();
        $userId = $user->id;
        $validator = Validator::make(
            $request->all(),
            [
                'answer' => 'required|string|max:4103',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        if (is_numeric($questionId)) {
            $question = Question::find($questionId);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1025
            ], 400);
        }
        $value = $validator->validated();


        return $this->answerAdd($question, $userId, $questionId, $value);

    }

    public function answerAdd($question, $userId, $questionId, $value)
    {
        if ($question) {

            $answer = new Answers;
            $answer->user_id = $userId;
            $answer->question_id = $questionId;
            $answer->answer = $value['answer'];
            $answer->status = 1;
            Question::where('id', $questionId)->increment('answer_count', +1);

            $answer->save();
            if ($question->user_id != $userId) {
                $notification = new Notifications();
                $notification->type = 1;
                $notification->user_id = $question->user_id;
                $notification->posted_user_id = $userId;
                $notification->question_id = $questionId;
                $notification->answer_id = $answer->id;
                $notification->save();
            }
            return response()->json([
                'message' => 'answer added successfully',
            ]);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1025
            ], 400);
        }
    }

    // update answer
    public function updateAnswer(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'answer' => 'required|string|max:4103',
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
        if (is_numeric($id)) {
            $answer = Answers::where('id', $id)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1027
            ], 400);
        }
        return $this->updateAnswerSave($answer, $userId, $request);
    }

    public function updateAnswerSave($answer, $userId, $request)
    {
        if ($answer) {
            $userAnsId = $answer->user_id;
            if ($userId == $userAnsId) {
                $answer->update($request->all());
                return response()->json(['answer' => $answer]);
            } else {
                return response()->json([
                    "message" => Config::get($this->badRequest),
                    "error_code" => 1028
                ], 400);
            }
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1027
            ], 400);
        }
    }

    // View Answer to a question with total upVotes & downVotes
    public function getAnswer(Request $request, $questionId)
    {
        if (is_numeric($questionId)) {
            $checkId = Question::where('id', $questionId)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1026
            ], 400);
        }
        $tags = Tag::select('tag_name')
            ->join('question_tags', 'question_tags.tag_id', '=', 'tags.id')
            ->where('question_tags.question_id', $questionId)
            ->get();
        if ($checkId) {
            $user = auth('api')->user();
            $userId = $user->id;
            if ($checkId->status == '0' && $checkId->user_id != $userId) {
                return response()->json([
                    "message" => "Question is no longer available!!",
                    "error_code" => 1047
                ], 400);
            }
            $matchThese = ['question_id' => $questionId, 'status' => 1];
            if ($request->sort == 1) {
                $answers = Answers::orderBy('created_at', 'desc')->where($matchThese)->get();
            } else {
                $answers = Answers::orderBy('upvote', 'desc')->where($matchThese)->get();
            }
            return $this->viewAnswers($answers, $questionId, $userId, $checkId, $tags);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1026
            ], 400);
        }
    }
    public function viewAnswers($answers, $questionId, $userId, $checkId, $tags)
    {
        foreach ($answers as $a) {
            $matchedItemForVote = ['user_id' => $userId, 'answer_id' => $a->id];
            $checkUserVote = UserVote::where($matchedItemForVote)->first();
            $a->like = 2;
            if ($checkUserVote) {
                $a->like = $checkUserVote->status;
            }
            $answerId = $a->id;
            $checkAnswer = Comments::where('answer_id', $answerId)->get();
            if ($checkAnswer) {
                $default = 3;
                $matchThese = ['answer_id' => $answerId, 'status' => 1];
                $comment = Comments::where($matchThese)->orderByDesc('id')->paginate($default);
                foreach ($comment as $c) {
                    $uid = $c->user_id;
                    $user = User::find($uid);
                    $c->user_name = $user->name;
                    $c->profile_picture = $user->profile_picture;
                }
            }
            $a->comment = $comment;
            $uid = $a->user_id;
            $user = User::find($uid);
            $a->answer_added_user = $user->name;
            $a->answer_added_user_picture = $user->profile_picture;
        }
        $questionPostedUser = User::where('id', $checkId->user_id)->first();
        $this->answerNotification($userId, $checkId, $questionId);
        return response()->json([
            "Title" => $checkId->title,
            "Description" => $checkId->description,
            "Question_status" => $checkId->status,
            "date" => $checkId->created_at,
            "Tags" => $tags,
            "Verified" => $checkId->verified,
            "user_id" => $checkId->user_id,
            "name" => $questionPostedUser->name,
            "profile_picture" => $questionPostedUser->profile_picture,
            "points" => $questionPostedUser->points,
            "Answers" => $answers,
        ]);
    }

    public function answerNotification($userId, $checkId, $questionId)
    {
        $checkWeather = ['user_id' => $userId, 'question_id' => $questionId];
        $answerNotification = Notifications::where($checkWeather)->get();
        if ($answerNotification) {
            Notifications::where($checkWeather)->update([
                'status' => 0
            ]);
        }
    }


    // view Answer based on answer Id
    public function viewAnswerOnId($answerId)
    {
        if (is_numeric($answerId)) {
            $answer = Answers::where('id', $answerId)->first();
            if ($answer) {
                return response()->json([
                    'Answer' => $answer
                ]);
            } else {
                return response()->json([
                    "message" => Config::get($this->invalidId),
                    "error_code" => 1028
                ], 400);
            }
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1028
            ], 400);
        }
    }
    // Verify answer
    public function verifyAnswer($answerId)
    {
        if (is_numeric($answerId)) {
            $answer = Answers::find($answerId);
            if (!$answer) {
                return response()->json([
                    "message" => Config::get($this->invalidId),
                    "error_code" => 1029
                ], 400);
            }
            $questionId = $answer->question_id;
            $que = Question::where('id', $questionId)->value('status');
            if ($que == 1) {
                $question = Question::find($questionId);
                $question->verified = 1;
                $question->save();
                $userId = $question->user_id;
                $user = auth('api')->user();
                return $this->verifySave($userId, $user, $answer);
            } else {
                throw new BadRequestHttpException("Question is no longer available");
            }
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1029
            ], 400);
        }
    }

    public function verifySave($userId, $user, $answer)
    {
        $score = ReputationScore::where('id', 2)->value('score');
        if ($userId == $user->id) {
            if ($answer->verified != 1) {
                if ($answer->user_id != $user->id) {
                    $answeredUser = User::find($answer->user_id);
                    $answeredUser->points += $score;
                    $answeredUser->save();
                }
                $answer->verified = 1;
                $answer->save();
                return response()->json([
                    "message" => "answer verified successfully"
                ]);
            } else {
                return response()->json([
                    "message" => "Answer already verified",
                    "error_code" => 1050
                ], 400);
            }
        } else {
            return response()->json([
                "message" => Config::get($this->badRequest),
                "error_code" => 1030
            ], 400);
        }
    }


    public function addVote(Request $request, $answerId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'status' => 'required|in:0,1|digits:1'
            ]
        );
        $voteScore = ReputationScore::where('id', 3)->value('score');
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        if (!Answers::where('id', $answerId)->exists()) {
            throw new BadRequestHttpException("Invalid Id");
        }
        $user = auth('api')->user();
        $userId = $user->id;
        if (is_numeric($answerId)) {
            $answer = Answers::where('id', $answerId)->first();
        } else {
            throw new BadRequestHttpException("Invalid Id");
        }
        $questionId = $answer->question_id;
        $que = Question::where('id', $questionId)->value('status');
        if ($que == 0) {
            throw new BadRequestHttpException("Question is no longer available");
        }
        $answeredUser = Answers::where('id', $answerId)->value('user_id');
        if ($userId == $answeredUser) {
            throw new BadRequestHttpException("Cannot Do Self Vote");
        }
        $userPoints = User::where('id', $userId)->first('points');
        $points = $userPoints->points;
        if ($points < 10) {
            throw new BadRequestHttpException("Minimum 10 points required");
        }
        $data = $validator->validated();
        $vote = new UserVote;
        $vote->user_id = $userId;
        $vote->answer_id = $answerId;
        $vote->status = $data['status'];
        $matchValue = UserVote::where('user_id', $userId)->where('answer_id', $answerId)->get();
        foreach ($matchValue as $u) {
            $statusValue = $u->status;
        }
        $userAnswered = Answers::select('user_id')->where('id', $answerId)->get();
        foreach ($userAnswered as $u) {
            $answeredUser = $u->user_id;
        }
        if (!UserVote::where('user_id', $userId)->where('answer_id', $answerId)->exists()) {
            return $this->newVoteChecking($userId, $answerId, $data, $answeredUser, $questionId);
        }
        if ($vote->status == $statusValue) {
            if ($statusValue == 0) {
                Answers::where('id', $answerId)->increment('downvote', -1);
                UserVote::where('user_id', $userId)->where('answer_id', $answerId)->delete();
            }
            if ($statusValue == 1) {
                User::where('id', $answeredUser)->increment('points', -$voteScore);
                Answers::where('id', $answerId)->increment('upvote', -1);
                UserVote::where('user_id', $userId)->where('answer_id', $answerId)->delete();
            }
            return response()->json(
                [
                    'Already Voted'
                ]
            );
        }
        $input = $request->all();
        UserVote::where('user_id', $userId)->where('answer_id', $answerId)->update($input);
        if ($vote->status == 1) {
            Answers::where('id', $answerId)->increment('upvote', +1);
            User::where('id', $answeredUser)->increment('points', +$voteScore);
            Answers::where('id', $answerId)->increment('downvote', -1);
            $notification = new Notifications;
            $notification->user_id = $answeredUser;
            $notification->posted_user_id = $userId;
            $notification->type = 2;
            $notification->question_id = $questionId;
            $notification->answer_id = $answerId;
            $notification->save();

        } elseif ($vote->status == 0) {
            Answers::where('id', $answerId)->increment('downvote', +1);
            User::where('id', $answeredUser)->increment('points', -$voteScore);
            Answers::where('id', $answerId)->increment('upvote', -1);
        }
        return response()->json(
            [
                'Status Updated'
            ]
        );
    }

    public function newVoteChecking($userId, $answerId, $data, $answeredUser, $questionId)
    {
        $voteScore = ReputationScore::where('id', 3)->value('score');
        $vote = new UserVote;
        $vote->user_id = $userId;
        $vote->answer_id = $answerId;
        $vote->status = $data['status'];
        $vote->save();
        if ($vote->status == 1) {
            Answers::where('id', $answerId)->increment('upvote', +1);
            User::where('id', $answeredUser)->increment('points', +$voteScore);
            $notification = new Notifications;
            $notification->user_id = $answeredUser;
            $notification->posted_user_id = $userId;
            $notification->type = 2;
            $notification->question_id = $questionId;
            $notification->answer_id = $answerId;
            $notification->save();
        } elseif ($vote->status == 0) {
            Answers::where('id', $answerId)->increment('downvote', +1);
        }
        return response()->json(
            [
                'Status Added'
            ]
        );
    }

    //Clear Notification
    public function clearNotification()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $matchThese = ['type' => '0', 'status' => '0'];
        $checkUserNotification = Notifications::where('user_id', $userId)->update($matchThese);
        if ($checkUserNotification) {
            return response()->json([
                "Message" => "notification cleared successfully"
            ]);
        }
    }


    // view notification
    public function showNotification()
    {
        $data = [];
        $user = auth('api')->user();
        $userId = $user->id;
        $getCount = Notifications::where('user_id', $userId)->whereNot('status', 0)->get();
        $wordCount = $getCount->count();
        $getNotification = Notifications::where('user_id', $userId)->whereNot('type', 0)->orderby('id', 'desc')->get();
        if (sizeof($getNotification) != 0) {
            foreach ($getNotification as $c) {
                $pId = $c->posted_user_id;
                $checkUser = User::where('id', $pId)->first();
                $c->posted_user_name = $checkUser->name;
                $c->photo = $checkUser->profile_picture;
                array_push($data, $c);
            }
            return response()->json([
                "Success" => $data,
                "count" => $wordCount
            ]);
        } else {
            return null;
        }
    }

    // answer delete
    public function answerDelete($answerId)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        if (is_numeric($answerId)) {
            $matchThese = ['id' => $answerId, 'status' => 1];
            $answer = Answers::where($matchThese)->first();
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1044
            ], 400);
        }

        if (!$answer) {
            return response()->json([
                "message" => Config::get($this->badRequest)
            ], 400);
        }
        $answerUserId = $answer->user_id;
        //To check whether the current user is same as answer added user.
        if ($userId == $answerUserId) {
            $qid = $answer->question_id;
            $question = Question::where('id', $qid)->first();
            if ($answer->verified == 1) {
                $answer->verified = 0;
                $question->verified = 0;
                $question->save();
            }
            $question->answer_count = $question->answer_count -1;
            $question->save();
            $answer->status = 0;
            $answer->save();
            return response()->json([
                "Message" => "answer deleted successfully"
            ]);
        }
    }

}
