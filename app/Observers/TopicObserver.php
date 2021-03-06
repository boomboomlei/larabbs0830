<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic){

    	$topic->body=clean($topic->body,'user_topic_body');

    	$topic->excerpt=make_excerpt($topic->body);

    	// if(!$topic->slug){
    	// 	// $topic->slug=app(SlugTranslateHandler::class)->translate($topic->title);
    	//      // 推送任务到队列
     //        dispatch(new TranslateSlug($topic));
     //    }

    }

    public  function saved(Topic $topic){
        if(!$topic->slug){
            
             // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }


    public function deleted(Topic $topic){
        \DB::table('replies')->where('topic_id',$topic->id)->delete();
    }

}