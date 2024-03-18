<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\Controllers\AdminController;
use App\Http\Controllers\Admin\Controllers\ReputationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('call', [TagsController::class, 'callSAmple']);
Route::post('user/check/token', [AuthController::class, 'checkDevToken']);
Route::post('login', [AuthController::class, 'googleLogin']);
Route::post('register', [AuthController::class, 'registration']);
Route::get('sample', [TagsController::class, 'sampleTest']);
Route::post('user/login', [AuthController::class, 'normalLogin']);
Route::post('user/verify', [AuthController::class, 'verifyToken']);
Route::post('user/forgot-password', [AuthController::class, 'forgotPassword']);
Route::put('user/reset/password', [UserController::class, 'resetPassword']);
Route::post('user/verify/token/reset-password', [UserController::class, 'verifyForgotPasswordToken']);
Route::post('admin/login', [AdminController::class, 'adminLogin']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('user/confirm', [AuthController::class, 'confirmUser']);
    Route::group(
        ['middleware' => 'status'],
        function () {
            Route::group(
                ['middleware' => 'admin'],
                function () {

                        // User Controllers
                        Route::get('getuser', [UserController::class, 'getUserDetails']);
                        Route::get('points', [UserController::class, 'getPoints']);
                        Route::get('getAllUsers', [UserController::class, 'getAllUsers']);
                        Route::put('users/edit', [UserController::class, 'editUser']);
                        Route::get('users/search', [UserController::class, 'searchUser']);
                        Route::get('user/refresh-token', [UserController::class, 'refreshToken']);
                        Route::get('user/get-popular', [UserController::class, 'popularUsers']);
                        Route::put('user/change/password', [UserController::class, 'changePassword']);
                        Route::post('user/upload/image', [UserController::class, 'uploadImage']);
                        Route::get('user/impact', [UserController::class, 'getUserImpact']);
                        

                        // Question Controllers
                        Route::post('question', [QuestionController::class, 'addQuestion']);
                        Route::get('questionProfile', [QuestionController::class, 'getQuestionUser']);
                        Route::put('question/{questionid}', [QuestionController::class, 'editQuestion']);
                        Route::get('answeredQuestions', [QuestionController::class, 'answeredQuestion']);
                        Route::get('users/question/count', [QuestionController::class, 'getQuestionCount']);
                        Route::post('questionviews/{questionId}', [QuestionController::class, 'QuestionViewCount']);
                        Route::get('questions', [QuestionController::class, 'showQuestions']);
                        Route::get('question/view/{questionId}', [QuestionController::class, 'viewQuestionOnId']);
                        Route::get('question/searchtext', [QuestionController::class, 'searchQuestion']);
                        Route::get('questions-onTags/{tagId}', [QuestionController::class, 'getQuestionsByTag']);
                    Route::get('questions-onTags/search/{tagId}', [QuestionController::class, 'searchQuestionsByTag']);
                        Route::get('questions-onUser/search', [QuestionController::class, 'searchQuestionUser']);
                        Route::get('suggestQuestion', [QuestionController::class, 'suggestQuestion']);
                        Route::put('deleteQuestion/{questionId}', [QuestionController::class, 'deleteQuestion']);
                        Route::get('deleteCount', [QuestionController::class, 'deleteCount']);
                        Route::get('searchAnsweredQuestions', [QuestionController::class, 'searchAnsweredQuestions']);

                        //Answer Controllers
                        Route::get('user/notification', [AnswerController::class, 'showNotification']);
                        Route::get('answer/{questionid}', [AnswerController::class, 'getAnswer']);
                        Route::post('answer/{questionid}', [AnswerController::class, 'addAnswer']);
                        Route::put('answer/{id}', [AnswerController::class, 'updateAnswer']);
                        Route::put('answer/verify/{answerid}', [AnswerController::class, 'verifyAnswer']);
                        Route::post('add-vote/{answerId}', [AnswerController::class, 'addVote']);
                        Route::get('answer/view/{answerId}', [AnswerController::class, 'viewAnswerOnId']);
                        Route::put('user/notification/delete', [AnswerController::class, 'clearNotification']);
                        Route::put('deleteAnswer/{answerId}', [AnswerController::class, 'answerDelete']);

                        // Comments Controllers
                        Route::post('comment', [CommentController::class, 'addComment']);
                        Route::get('commentsquestion/{id}', [CommentController::class, 'viewCommentsQuestion']);
                        Route::get('answer/comments/{answerid}', [CommentController::class, 'viewComments']);
                        Route::put('comments/delete/{commentid}', [CommentController::class, 'deleteComments']);
                        Route::put('editComment/{commentId}', [CommentController::class, 'editComment']);
                        Route::get('comment/{id}', [CommentController::class, 'GetCommentForEdit']);

                        //Tags Controllers
                        Route::get('tags', [TagsController::class, 'showAllTags']);
                        Route::post('newtag', [TagsController::class, 'addTag']);
                        Route::get('tag/searchtext', [TagsController::class, 'searchTag']);
                        Route::post('userTag', [TagsController::class, 'addUserTags']);
                        Route::put('edit-tag', [TagsController::class, 'editUserTags']);
                        Route::get('getUserTags', [TagsController::class, 'GetUserTags']);
                        Route::get('tag/view/{tagid}', [QuestionController::class, 'getTagOnVerified']);
                        Route::get('tag/get-tagid', [TagsController::class, 'GetUserTagsId']);
                        Route::get('popular-tags', [TagsController::class, 'popularTags']);
                        Route::post('ignoreTags', [TagsController::class, 'ignoreTags']);
                        Route::put('editIgnoreTags', [TagsController::class, 'editIgnoreTags']);
                        Route::get('getIgnoreTags', [TagsController::class, 'getIgnoreTags']);
                        Route::get('getToAddIgnoreTags', [TagsController::class, 'getToAddIgnoreTags']);
                        Route::get('getToAddUserTags', [TagsController::class, 'getToAddUserTags']);

                    }
            );
            //Admin Side
            Route::get('loggedInUser', [AdminController::class, 'loggedUsers']);
            Route::get('loggedInUser/search', [AdminController::class, 'userSearch']);
            Route::put('manageUser/{userid}', [AdminController::class, 'manageUser']);
            Route::post('addReputation', [ReputationController::class, 'addReputationScore']);
            Route::get('getReputation', [ReputationController::class, 'getAllReputations']);
            Route::put('reputation/{reputationId}', [ReputationController::class, 'editReputationScore']);
            Route::get('getCount', [AdminController::class, 'getCount']);
            Route::get('getReputationId/{id}', [ReputationController::class, 'getReputationById']);
            Route::get('admin/pie/count', [AdminController::class, 'getAdminPieChartCount']);


        }
    );

});
