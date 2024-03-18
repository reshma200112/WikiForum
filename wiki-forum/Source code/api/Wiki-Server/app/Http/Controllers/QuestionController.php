<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\ReputationScore;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Question;
use App\Models\Answers;
use App\Traits\PaginationTrait;
use App\Models\QuestionTag;
use App\Models\QuestionView;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class QuestionController extends Controller
{
    use PaginationTrait;
    public $invalidId = 'constants.errorMessages.2';
    public $qTagId = 'question_tags.tag_id';
    public $publicQuestionId = 'questions.id';
    public $tagQuestionId = 'question_tags.question_id';
    public $regex = 'formValidator.validations.2';
    public $publicTagId = 'tags.id';
    public function addQuestion(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:100',
                'description' => 'required|string|max:4103',
                'tag_id' => 'required|max:5'
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }

        $questionScore = ReputationScore::where('id', 1)->value('score');
        $user = auth('api')->user();
        $userId = $user->id;

        $value = $validator->validated();


        $tagId = $value['tag_id'];
        $count = 0;
        foreach ($tagId as $tId) {
            if (Tag::where('id', $tId)->doesntExist()) {
                $count += 1;
            }
        }

        if ($count == 0) {
            $question = new Question;
            $question->user_id = $userId;
            $question->title = preg_replace(Config::get($this->regex), ' ', $value['title']);
            $question->description = $value['description'];
            $question->status = 1;
            $question->save();
            $qid = $question->id;
            foreach ($tagId as $tId) {
                $qTag = new QuestionTag;
                $qTag->question_id = $qid;
                $qTag->tag_id = $tId;
                $qTag->save();
            }

            User::where('id', $userId)->increment('points', $questionScore);

            return response()->json([
                'message' => 'question added successfully',
                'question' => $question
            ]);
        } else {
            return response()->json([
                "message" => "enter valid tag",
                "error_code" => 1015
            ], 400);
        }

    }

    // to get questions posted by current user
    public function getQuestionUser()
    {
        $user = auth('api')->user();
        $id = $user->id;
        $questions = Question::where('user_id', $id)->orderBy('id', 'desc')->paginate(10);
        foreach ($questions as $q) {
            $questionId = $q->id;
            $q->tags = Tag::select('tag_name')
                ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                ->where($this->tagQuestionId, $questionId)
                ->get();
            $uid = $q->user_id;
            $user = User::where('id', $uid)->first();
            $q->name = $user->name;
        }
        return response()->json([
            'question' => $questions
        ]);
    }

    public function searchQuestion(Request $request)
    {
        $data = $request->get('s');
        $htmlEncodedData = htmlspecialchars($data, ENT_NOQUOTES);
        $query = Question::query();
        $query->where(function ($q) use ($data, $htmlEncodedData) {
            $q->where('title', 'LIKE', '%' . $data . '%')
                ->orWhere('description', 'LIKE', '%' . $htmlEncodedData . '%');
        })
            ->where('status', 1)
            ->distinct('id');
        $questions = $query->paginate(10);
        foreach ($questions as $question) {
            $uid = $question['user_id'];
            $user = User::find($uid);
            $qId = $question['id'];
            $question['tags'] = Tag::select('tag_name')
                ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                ->where($this->tagQuestionId, $qId)
                ->get();
            $question['name'] = $user->name;
        }
        return $questions;
    }


    // Edit Question
    public function editQuestion(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:100',
                'description' => 'required|max:4103',
                'tag_id' => 'required|max:5'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                400
            );
        }
        $value = $validator->validated();
        if (is_numeric($id)) {
            $question = Question::find($id);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1012
            ], 400);
        }
        if (!$question) {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1012
            ], 400);
        }
        $userId = $question->user_id;
        $user = auth('api')->user();
        // to avoid questions with same title
        if ($user->id == $userId) {
            return $this->editQuestionChecking($question, $value, $id);
        } else {
            return response()->json([
                "message" => Config::get('constants.errorMessages.6'),
                "error_code" => 1013
            ], 400);
        }
    }


    public function editQuestionChecking($question, $value, $id)
    {
        $forChecking = preg_replace(Config::get($this->regex), ' ', $value['title']);
        $questionTitle = Question::where('id', $id)->first('title');
        if ($questionTitle->title != $forChecking) {
            $matchRequest = ['status' => 1];
            $query = Question::where('title', 'LIKE', '%' . $forChecking . '%')
                ->where($matchRequest)
                ->where('id', '<>', $id)
                ->get();
            $count = 0;
            foreach ($query as $q) {
                $title = $q->title;
                if ($title == $forChecking) {
                    $count++;
                }
            }
            if ($count > 0) {
                return response()->json([
                    "message" => "title already exists!!",
                    "error_code" => 1014
                ], 400);
            } else {
                return $this->editQuestionSave($question, $value, $id);
            }
        } else {
            return $this->editQuestionSave($question, $value, $id);
        }
    }

    public function editQuestionSave($question, $value, $id)
    {
        $tagId = $value['tag_id'];
        $count = 0;
        foreach ($tagId as $tId) {
            if (Tag::where('id', $tId)->doesntExist()) {
                $count += 1;
            }
        }
        if ($count == 0) {
            $question->title = preg_replace(Config::get($this->regex), ' ', $value['title']);
            $question->description = $value['description'];
            $question->save();
            QuestionTag::where('question_id', $id)->delete();

            foreach ($tagId as $tId) {
                $qTag = new QuestionTag;
                $qTag->question_id = $id;
                $qTag->tag_id = $tId;
                $qTag->save();
            }
            return response()->json([
                "Message" => "question updated successfully",
                "Question" => $question
            ]);
        } else {
            return response()->json([
                "message" => "enter valid tag",
                "error_code" => 1015
            ], 400);
        }
    }

    // current user answered questions
    public function answeredQuestion()
    {
        $user = auth('api')->user();
        $currentUser = $user->id;
        $answeredQuestions = Question::select(
            $this->publicQuestionId,
            'questions.title',
            'questions.description',
            'questions.user_id',
            'questions.answer_count',
            'questions.views',
            'questions.status',
            'questions.verified',
            'questions.created_at',
            'questions.updated_at',
            'users.name',
            'answers.id as answer_id'
        )
            ->join('users', 'users.id', '=', 'questions.user_id')
            ->join('answers', 'questions.id', '=', 'answers.question_id')
            ->where('answers.user_id', $currentUser)
            ->where('questions.status', 1)
            ->where('answers.status', 1)
            ->orderByDesc('answers.id')
            ->groupBy($this->publicQuestionId)
            ->paginate(10);
        foreach ($answeredQuestions as $question) {
            $qId = $question->id;
            $question->tags = Tag::select('tag_name')
                ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                ->where($this->tagQuestionId, $qId)
                ->get();
        }
        return $answeredQuestions;
    }

    // search user answeres questions
    public function searchAnsweredQuestions(Request $request)
    {
        $user = auth('api')->user();
        $currentUser = $user->id;
        $data = $request->get('data');
        $htmlEncodedData = htmlspecialchars($data, ENT_NOQUOTES);
        $questions = Question::select(
            $this->publicQuestionId,
            'questions.title',
            'questions.description',
            'questions.user_id',
            'questions.answer_count',
            'questions.views',
            'questions.status',
            'questions.verified',
            'questions.created_at',
            'questions.updated_at',
            'users.name',
            'answers.id as answer_id'
        )
            ->join('users', 'users.id', '=', 'questions.user_id')
            ->join('answers', 'questions.id', '=', 'answers.question_id')
            ->where('answers.user_id', $currentUser)
            ->where('questions.status', 1)
            ->where('answers.status', 1)
            ->orderByDesc('answers.id')
            ->groupBy($this->publicQuestionId)
            ->where(function ($q) use ($data, $htmlEncodedData) {
                $q->where('title', 'LIKE', '%' . $data . '%')
                    ->orWhere('description', 'LIKE', '%' . $htmlEncodedData . '%');
            })
            ->paginate(10);
        foreach ($questions as $question) {
            $qId = $question->id;
            $question->tags = Tag::select('tag_name')
                ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                ->where($this->tagQuestionId, $qId)
                ->get();
        }
        return $questions;
    }

    // get question, last 5 posted question & last 5 answered question
    public function getQuestionCount()
    {
        $user = auth('api')->user();
        $currentUser = $user->id;
        $matchThese = ['user_id' => $currentUser, 'status' => 1];
        if ($currentUser) {
            $wordCount = Question::where($matchThese)->count();
            $lastFivePostedQuestions = Question::where('user_id', $currentUser)
                ->latest()->take(5)->select('id', 'title')->get();
            $lastFiveAnsweredQuestionId = Answers::where($matchThese)
                ->latest()->take(5)->select('id', 'question_id')->get();
            $lastFiveAnsweredQuestions = [];
            foreach ($lastFiveAnsweredQuestionId as $id) {
                $question = Question::where('id', $id->question_id)->where('status', 1)->select('id', 'title')->first();
                $question->answer_id = $id->id;
                array_push($lastFiveAnsweredQuestions, $question);
            }
            return response()->json([
                'Count' => $wordCount,
                'last_five_posted_questions' => $lastFivePostedQuestions,
                'last_five_answered_questions' => $lastFiveAnsweredQuestions
            ]);
        } else {
            throw new UnauthorizedHttpException("Invalid User");
        }
    }


    public function showQuestions()
    {
        $user = auth('api')->user();
        $currentUser = $user->id;
        $allQuestionId = [];
        // Get User-selected-tags based question_id
        $userQuestions = QuestionTag::select('question_id')
            ->distinct()
            ->join('user_tags', 'user_tags.tag_id', '=', $this->qTagId)
            ->where('user_tags.user_id', $currentUser)
            ->get();

        // Get All question_id
        $allQuestions = QuestionTag::select('question_id')
            ->distinct()
            ->get();

        $collection = collect($userQuestions);
        $mergeQuestions = $collection->merge($allQuestions);


        foreach ($mergeQuestions as $qIds) {
            $storeAllQuestionIds = $qIds->question_id;
            array_push($allQuestionId, $storeAllQuestionIds); //allQuestions array
        }

        $allQuestionId = array_unique($allQuestionId);
        $questionDetails = [];
        foreach ($allQuestionId as $qId) {
            $matchThese = ['id' => $qId, 'status' => 1];
            $question = Question::where($matchThese)->get();
            foreach ($question as $que) {
                $pId = $que['id'];
                $uid = $que['user_id'];
                $user = User::where('id', $uid)->first();
                $que['name'] = $user->name;
                $que['tags'] = Tag::select('tag_name')
                    ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                    ->where($this->tagQuestionId, $pId)
                    ->get();
                array_push($questionDetails, $question->toArray());
            }
        }
        $qDetailsNames = call_user_func_array('array_merge', $questionDetails);
        $qDetailsNames = $this->paginate($qDetailsNames, 10);
        return $qDetailsNames;
    }


    public function QuestionViewCount($questionId)
    {
        $user = auth('api')->user();
        $currentUser = $user->id;
        if (is_numeric($questionId)) {
            if (!QuestionView::where('user_id', $currentUser)->where('question_id', $questionId)->exists()) {
                $views = new QuestionView;
                $views->user_id = $currentUser;
                $views->question_id = $questionId;
                $views->save();
                $question = Question::find($questionId);
                $question->views += 1;
                $question->save();
            }
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1045
            ], 400);
        }
    }

    // get question based on question id
    public function viewQuestionOnId($questionId)
    {
        if (is_numeric($questionId)) {
            $question = Question::find($questionId);
        } else {
            return response()->json([
                "message" => Config::get($this->invalidId),
                "error_code" => 1046
            ], 400);
        }
        if (!$question) {
            return response()->json([
                "message" => Config::get($this->invalidId)
            ], 400);
        } else {
            $questionTags = QuestionTag::where('question_id', $question->id)->pluck('tag_id')->all();
            return response()->json([
                "question" => $question,
                "question_tags" => $questionTags
            ]);
        }

    }


    //to get questions based on tags clicked on front end
    public function getQuestionsByTag($tagId)
    {
        if (is_numeric($tagId)) {
            $toCheck = Tag::where('id', $tagId)->exists();
            if ($toCheck) {
                return $this->questionsTag($tagId);
            } else {
                $message = Config::get($this->invalidId);
            }
        } else {
            $message = Config::get($this->invalidId);
        }
        return response()->json([
            "message" => $message
        ], 400);
    }

    public function questionsTag($tagId)
    {
        $toCheckQuestions = QuestionTag::where('tag_id', $tagId)->exists();
        if ($toCheckQuestions) {
            $questionss = [];
            $questionId = QuestionTag::where('tag_id', $tagId)
                ->orderBy('question_id', 'desc')
                ->get('question_id');
            foreach ($questionId as $q) {
                $qId = $q->question_id;
                $matchThese = ['id' => $qId, 'status' => 1];
                $question = Question::where($matchThese)->get();
                foreach ($question as $que) {
                    $que['tags'] = Tag::select('tag_name')
                        ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                        ->where($this->tagQuestionId, $qId)
                        ->get();
                    $uid = $que['user_id'];
                    $user = User::where('id', $uid)->first();
                    $que['name'] = $user->name;
                    $questionss[] = $que;
                }
            }
            $qTag = Tag::where('id', $tagId)->first();
            $tagName = $qTag->tag_name;
            $questions = $this->paginate($questionss, 10);
            return response()->json([
                "question" => $questions,
                "tagName" => $tagName
            ]);
        } else {
            $qTag = Tag::where('id', $tagId)->first();
            $tagName = $qTag->tag_name;
            return response()->json([
                "question" => [],
                "tagName" => $tagName
            ]);
        }
    }


    //to search questions based on tags
    public function searchQuestionsByTag(Request $request, $tagId)
    {
        if (is_numeric($tagId)) {
            $tag = Tag::where('id', $tagId)->exists();
            if ($tag) {
                $data = $request->get('data');
                $htmlEncodedData = htmlspecialchars($data, ENT_NOQUOTES);
                $questionsTagBased = Question::select(
                    $this->publicQuestionId,
                    'title',
                    'description',
                    'user_id',
                    'answer_count',
                    'views',
                    'questions.status',
                    'verified',
                    'questions.created_at',
                    'questions.updated_at',
                    $this->qTagId,
                    'users.name',
                )
                    ->join('question_tags', $this->tagQuestionId, '=', $this->publicQuestionId)
                    ->join('users', 'users.id', '=', 'questions.user_id')
                    ->where(function ($q) use ($data, $htmlEncodedData) {
                        $q->where('title', 'LIKE', '%' . $data . '%')
                            ->orWhere('description', 'LIKE', '%' . $htmlEncodedData . '%');
                    })
                    ->where($this->qTagId, $tagId)
                    ->where('questions.status', 1)
                    ->orderByDesc($this->publicQuestionId)
                    ->paginate(10);
                foreach ($questionsTagBased as $question) {
                    $qId = $question->id;
                    $question->tags = Tag::select('tag_name')
                        ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                        ->where($this->tagQuestionId, $qId)
                        ->get();
                }

                return $questionsTagBased;
            } else {
                return response()->json([
                    "message" => "Invalid id"
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "Invalid id"
            ], 400);
        }
    }


    public function getTagOnVerified($questionId)
    {
        $question = Question::find($questionId);
        if (!$question) {
            return response()->json([
                "message" => Config::get($this->invalidId)
            ], 400);
        } else {
            $questionTags = QuestionTag::where('question_id', $question->id)->pluck('tag_id')->all();
            $tags = Tag::all();
            $toView = [];
            foreach ($tags as $a) {
                $tId = $a->id;
                $a->checked = "false";
                foreach ($questionTags as $v) {
                    if ($tId == $v) {
                        $a->checked = "true";
                    }
                }
                array_push($toView, $a);
            }
            return $toView;
        }
    }

    // to search questions posted by current user
    public function searchQuestionUser(Request $request)
    {
        $user = auth('api')->user();
        $id = $user->id;
        $data = $request->get('data');
        $htmlEncodedData = htmlspecialchars($data, ENT_NOQUOTES);
        $query = Question::query();
        $query->where(function ($q) use ($data, $htmlEncodedData) {
            $q->where('title', 'LIKE', '%' . $data . '%')
                ->orWhere('description', 'LIKE', '%' . $htmlEncodedData . '%');
        })
            ->where('user_id', $id)
            ->orderBy('id', 'desc');
        $questions = $query->paginate(10);
        foreach ($questions as $question) {
            $qId = $question['id'];
            $question['tags'] = Tag::select('tag_name')
                ->join('question_tags', $this->qTagId, '=', $this->publicTagId)
                ->where($this->tagQuestionId, $qId)
                ->get();
        }
        return $questions;

    }

    //suggestions while adding a question
    public function suggestQuestion(Request $request)
    {
        $data = $request->get('data');
        $query = Question::query();
        $query->whereFullText('title', array($data));
        return $query->get();
    }

    // delete question
    public function deleteQuestion($questionId)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $score = ReputationScore::where('id', 1)->value('score');
        $decrementScore = ($score * 3) + 5;
        $questionPostUser = Question::where('id', $questionId)
            ->value('user_id'); // user_id of the question going to be deleted

        $matchThese = ['user_id' => $questionPostUser, 'status' => 0];
        $getAllUserQuestions = Question::where($matchThese)->get(); // get all questions deleted by the user

        $toCheck = ['id' => $questionId, 'status' => 1];
        $question = Question::where($toCheck)->first();

        $userPoints = User::where('id', $userId)->value('points'); //to get the points of the user
        $qCount = count($getAllUserQuestions);

        if ($userId == $questionPostUser) {
            if (($qCount + 1) % 3 != 0) {
                $this->notidelete($question);
                $question->status = 0; // deleted question status
                $question->save();
            } elseif (($qCount + 1) % 3 == 0) {
                $this->notidelete($question);
                User::where('id', $questionPostUser)->increment('points', -$decrementScore);
                $question->status = 0;
                $question->save();
            }
            return response()->json([
                "message" => Config::get('constants.errorMessages.4')
            ]);
        } elseif ($userPoints >= 1500) {
            $this->notidelete($question);
            User::where('id', $questionPostUser)->increment('points', -$score);
            $question->status = 2; // inactive question status
            $question->save();
            return response()->json([
                "message" => Config::get('constants.errorMessages.5')
            ]);
        } else {
            return response()->json([
                "message" => Config::get('constants.errorMessages.3')
            ]);
        }
    }

    public function deleteCount()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $matchThese = ['user_id' => $userId, 'status' => 0];
        $getAllUserQuestions = Question::where($matchThese)->get(); // get all questions deleted by the user
        $qCount = count($getAllUserQuestions);
        if (($qCount + 1) % 3 == 0) {
            $flag = true;
        } else {
            $flag = false;
        }
        return response()->json([
            "flag" => $flag,
        ]);
    }

    public function notidelete($question)
    {
        $find = ['question_id' => $question->id, 'type' => 1];
        $orFind = ['question_id' => $question->id, 'type' => 2];
        $notification = Notifications::where(function ($q) use ($find, $orFind) {
            $q->where($find)
                ->orWhere($orFind);
        })->get();
        foreach ($notification as $noti) {
            $noti->status = 0;
            $noti->type = 0;
            $noti->save();
        }
    }

}
