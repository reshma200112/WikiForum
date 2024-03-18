<?php

namespace App\Console\Commands;

use App\Models\Notifications;
use App\Models\Question;
use App\Models\QuestionTag;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $matchContent = ['answer_count' => 0, 'status' => 1];
        $questions = Question::where($matchContent)->get();
        Notifications::where('type', 3)->delete();
        foreach ($questions as $q) {
            $date = date('H:i:s', time());
            $endTime = new Carbon($q->created_at);
            $getDiff = $endTime->diffInDays($date);
            if ($getDiff >= 5) {
                $tagBasedUserId = QuestionTag::where('question_id', $q->id)
                    ->distinct()
                    ->join('user_tags', 'user_tags.tag_id', '=', 'question_tags.tag_id')
                    ->join('users', 'users.id', '=', 'user_tags.user_id')
                    ->where('users.points', '>', 1500)
                    ->get(['user_tags.user_id']);
                foreach ($tagBasedUserId as $users) {
                    if ($q->user_id != $users->user_id) {
                        $notification = new Notifications();
                        $notification->type = 3;
                        $notification->user_id = $users->user_id;
                        $notification->posted_user_id = $q->user_id;
                        $notification->question_id = $q->id;
                        $notification->save();
                    }
                }
            }
        }
        return Command::SUCCESS;
    }

}