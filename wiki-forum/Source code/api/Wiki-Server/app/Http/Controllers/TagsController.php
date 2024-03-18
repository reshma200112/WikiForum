<?php

namespace App\Http\Controllers;

use App\Models\IgnoreTag;
use App\Models\QuestionTag;
use App\Models\Tag;
use App\Models\UserTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TagsController extends Controller
{
    public $validation = 'formValidator.validations.3';
    public function showAllTags()
    {

        return Tag::where('status', 1)->get();

    }
    // Add new tag
    public function addTag(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tag_name' => 'required|string|max:20',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $getFormTagName = $validator->validated()['tag_name'];
        $tagName = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $getFormTagName);
        if (Tag::where('tag_name', '=', $tagName)->exists()) {
            return response()->json([
                "message" => "Already Exists",
                "error_code" => 1038
            ], 400);
        }
        $tag = new Tag();
        $tag->tag_name = $tagName;
        $tag->status = 1;
        $tag->save();
        return response()->json([
            "message" => "New Tag Added Successfully"
        ]);
    }


    public function searchTag(Request $request)
    {
        $data = $request->get('data');
        $tags = Tag::query();
        $tags->where('tag_name', 'LIKE', '%' . $data . '%');
        return $tags->get();

    }

    // to get tags with out ignore tags
    public function getToAddUserTags()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $igTags = IgnoreTag::where('user_id', $userId)->pluck('tag_id')->toArray();
        $tags = Tag::pluck('id')->toArray();
        $userTags = array_diff($tags, $igTags);
        foreach ($userTags as $t) {
            $tagname[] = Tag::where('id', $t)->select('tag_name', 'id')->first();
        }
        return $tagname;
    }

    // to add user interested tags
    public function addUserTags(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'tag_id' => Config::get($this->validation),
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $user = auth('api')->user();
        $userId = $user->id;
        $value = $validator->validated();
        $requestTagId = array_unique($value['tag_id']);
        
        $userTag = UserTag::where('user_id', $userId)->first();
        if ($userTag) {
            return response()->json([
                "message" => "Tag Already Added",
                "error_code" => 1039
            ], 400);
        }
        return $this->tagUser($request, $requestTagId, $userId, $value);

    }

    public function tagUser($request, $requestTagId, $userId, $value)
    {
        $count = 0;
        foreach ($requestTagId as $t) {
            if (Tag::where('id', $t)->doesntExist()) {
                $count += 1;
            }
        }
        if ($count == 0) {
            foreach ($requestTagId as $tId) {
                $userTag = new UserTag;
                $userTag->user_id = $userId;
                $userTag->tag_id = $tId;
                $userTag->save();
            }
            return response()->json([
                "message" => "user tag added successfully",
                "hasTag" => 'true'
            ]);
        } else {
            return response()->json([
                "message" => "Invalid tag id",
                "error_code" => 1040
            ], 400);
        }
    }


    // to get user interested tags
    public function GetUserTags()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        return UserTag::join('tags', 'tags.id', '=', 'user_tags.tag_id')
            ->where('user_id', $userId)
            ->select('tag_name', 'tag_id')
            ->get();
    }

    // to get user interested tag id
    public function GetUserTagsId()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        return UserTag::where('user_id', $userId)->pluck('tag_id')->all();

    }

    // edit user interested tags
    public function editUserTags(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $validator = Validator::make(
            $request->all(),
            [
                'user_tags' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $value = $validator->validated();
        $userTags = array_unique($value['user_tags']);

        $count = 0;
        foreach ($userTags as $t) {
            if (Tag::where('id', $t)->doesntExist()) {
                $count += 1;
            }
        }
        if ($count == 0) {
            return $this->userTagEdit($userTags, $userId);
        } else {
            return response()->json([
                "message" => "Invalid tag id",
                "error_code" => 1048
            ], 400);
        }

    }

    public function userTagEdit($userTags, $userId)
    {
        if (!empty($userTags) && count($userTags) <= 5) {
            UserTag::where('user_id', $userId)->delete();
            foreach ($userTags as $tId) {
                $qTag = new UserTag;
                $qTag->user_id = $userId;
                $qTag->tag_id = $tId;
                $qTag->save();
            }
            return response()->json([
                "Message" => "user tag updated successfully",
            ]);
        } else {
            return response()->json([
                "message" => "Min 1 tag and maximum 5 tags can be selected",
                "error_code" => 1041
            ], 400);
        }
    }

    public function popularTags()
    {
        $allTagId = QuestionTag::get('tag_id');
        foreach ($allTagId as $userTagsCounts) {
            $userTagsIds[] = $userTagsCounts->tag_id;
        }
        $tagsCountArray = array_count_values($userTagsIds);
        arsort($tagsCountArray);
        foreach ($tagsCountArray as $tagIds => $value) {
            $arrayTag[] = Tag::where('id', $tagIds)->get()->toArray();
        }
        $arrayTag = call_user_func_array('array_merge', $arrayTag);
        return array_slice($arrayTag, 0, 8);
    }

    // add user ignore tag
    public function ignoreTags(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $validator = Validator::make(
            $request->all(),
            [
                'ignore_tags' => Config::get($this->validation),
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $value = $validator->validated();
        $ignoreTags = $value['ignore_tags'];
        foreach ($ignoreTags as $tId) {
            $qTag = new IgnoreTag;
            $qTag->user_id = $userId;
            $qTag->tag_id = $tId;
            $qTag->save();
        }
        return response()->json([
            "message" => "Success"
        ]);

    }

    // edit user ignore tags
    public function editIgnoreTags(Request $request)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $ignoreTags = $request->ignore_tags;
        if (!empty($ignoreTags) && count($ignoreTags) <= 5) {
            IgnoreTag::where('user_id', $userId)->delete();
            foreach ($ignoreTags as $tId) {
                $qTag = new IgnoreTag;
                $qTag->user_id = $userId;
                $qTag->tag_id = $tId;
                $qTag->save();
            }
            return response()->json([
                "Message" => "ignore tag updated successfully",
            ]);
        } else {
            return response()->json([
                "message" => "Min 1 tag and maximum 5 tags can be selected",
            ], 400);
        }
    }

    // user ignored tags
    public function getIgnoreTags()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        return IgnoreTag::join('tags', 'tags.id', '=', 'ignore_tags.tag_id')
            ->where('user_id', $userId)
            ->get('tag_name');
    }

    // to get tags other than user interested tags
    public function getToAddIgnoreTags()
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $userTags = UserTag::where('user_id', $userId)->pluck('tag_id')->toArray();
        $tags = Tag::pluck('id')->toArray();
        $ignoreTags = array_diff($tags, $userTags);
        foreach ($ignoreTags as $t) {
            $tagname[] = Tag::where('id', $t)->select('tag_name', 'id')->first();
        }
        return $tagname;
    }

    // public api to check whether build is success or not.
    public function sampleTest()
    {
        return "Phase 2 sprint 2 build completed.";
    }

    public function callSAmple()
    {
        echo phpinfo();
    }
}
