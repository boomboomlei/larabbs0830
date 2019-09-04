<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use  Auth;
use App\Handlers\SlugTranslateHandler;

use App\Handlers\ImageUploadHandler;

use App\Models\User;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Topic  $topic,Request $request,User $user)
	{
		// $topics = Topic::paginate(30);
		// return view('topics.index', compact('topics'));
		


		// $topics=Topic::with('user','category')->paginate(30);
		// return view('topics.index',compact('topics'));
		

		$topics=$topic->withOrder($request->order)->paginate(20);

		$active_users=$user->getActiveUsers();

		



		return view('topics.index',compact('topics','active_users'));
	}

    public function show(Topic $topic,Request  $request)
    {
    	if(!empty($topic->slug)&& $topic->slug!=$request->slug){
    		return redirect($topic->link(),301);
    	}
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic)
	{
		// $topic = Topic::create($request->all());

		$topic->fill($request->all());
		$topic->user_id=Auth::id();
		$topic->save();

		// return redirect()->route('topics.show', $topic->id)->with('success', '创建成功.');
		return redirect()->to($topic->link())->with('success','成功创建话题！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);

		// dd($request->title.','.$topic->title);
		// if($request->title!=$topic->title){
		// 	$request->slug=app(SlugTranslateHandler::class)->translate($request->title);
		// }

		$topic->update($request->all());

		// return redirect()->route('topics.show', $topic->id)->with('success', '修改成功.');
		return redirect()->to($topic->link())->with('success','修改成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功.');
	}

	public  function uploadImage(Request $request,ImageUploadHandler $uploader){
		$data=[
			'success'=>false,
			'msg'=>'上传失败',
			'file_path'=>''
		];

		if($file=$request->upload_file){
			$result=$uploader->save($request->upload_file,'topics',\Auth::id(),1024);
			if($result){
				$data['file_path']=$result['path'];
				$data['msg']='上传成功';
				$data['success']=true;
			}
		}

		return $data;
	}
}