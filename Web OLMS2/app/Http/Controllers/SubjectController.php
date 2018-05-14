<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Subject;
use App\News;
use App\User;
use Mail;
use App\Activity;
use App\Notifications\InvoicePaid;
use App\Notifications\NewsNotifications;
use PDF;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function index()
    {
        // User::find(1)->notify(new InvoicePaid);
        // $user = User::find(1);

        // foreach ($user->notifications as $notification) {
        //     echo $notification->type;
        // }
       
        $subjects = Subject::paginate(10);
        return view('subject.index', ['subjects' => $subjects]);
    }

    public function create()
    {
        return view('subject.create');
    }

    public function store(Request $request)
    {
        $success = "เพิ่มรายวิชาสำเร็จ";
        $validation = Validator::make($request->all(), $this->rules());

        if($validation->passes()) {
            $subject = new Subject;
            $subject->name = $request->name;
            $subject->code = $request->code;
            $subject->owner_id = Auth::user()->id;
            $subject->save();
            
            $subject = Subject::where('name', $request->name)->first();
        
            return redirect()->action('SubjectController@show', ['subject' => $subject->id]);
        } else {
             return redirect()->route('subject.create')->withErrors($validator)->withInput();
        }
    }

    public function show($id)
    {
        $subject = Subject::find($id);
        $student = $subject->user->find(Auth::user()->id);
        //echo $student;
        return view('subject.show')->with('subject',$subject)->with('students', $student);
    }

    public function edit($id)
    {
        $subject = Subject::find($id);
        return view('subject.edit', ['subject' => $subject]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if($validation->passes()) {
            $subject = Subject::find($id);
            $subject->name = $request->name;
            $subject->code = $request->code;
            $subject->owner_id = Auth::user()->id;
            $subject->save();
            return redirect()->route('subject.create')->with('sussess', 'อัพเดทสำเร็จ');
        } else {
             return redirect()->route('subject.create')->withErrors($validator)->withInput();
        }
    }

    public function destroy($id)
    {
        Subject::destroy($id);
        return redirect()->route('subject.index');
    }
    public function follow ($id){       
        $subject = Subject::find($id);
        $users = Auth::user()->id;
        $subject->user()->toggle($users);  
        
        return redirect()->back();
    }
    public function postNews(Request $request,$id){
        $subject = Subject::find($id);
        $news = new News;
        $news->comment = $request->name;
        $news->user_id = Auth::user()->id;
        $news->subject_id = $id;
        $news->save();
        
        foreach ($subject->user as $key => $users) {
                User::find($users->id)->notify(new NewsNotifications($subject->id, $subject->name));
        }

        //  foreach (User::find(2)->unreadnotifications as $key => $value) {
        //      //foreach ($value->data['news'] as $key => $data) {
        //          echo $value->data['data'];
        //      //}
        //  }
        return redirect()->action(
            'SubjectController@show', ['subject' => $subject->id])
            ->with('subject',$subject)
            ->with('sussess', 'ประกาศสำเร็จ');     
    } 
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ];
    }
    public function status(){
        //User::find(Auth::user()->id)->unreadNotifications->markAsRead();
        $activity = User::find(Auth::user()->id)->activity;
        return view('activity.index')->with('activity',$activity);
        //echo $activity[0];      
    }
    public function adminStatus(){
        $activity = Activity::all();
        return view('activity.index')->with('activity',$activity);
        //echo $activity;
    }
    public function leave(Request $request, $id){
        $warning = "จำนวนการลาครบแล้ว";
        $validation = Validator::make($request->all(), $this->rules());
        $count = Activity::where('user_id',Auth::user()->id)->where('subject_id',$id)->count();
        if($count == 0){
            $activity = new Activity;
            $activity->formats = $request->format;
            $activity->description = $request->description;
            $activity->Start = $request->Start;
            $activity->count ++;
            $activity->user_id = Auth::user()->id;
            $activity->subject_id = $id;
            $activity->save();
            
            $subjects = Subject::find($id);
            $toEmail = $subjects->owner->email;
            $teacher = (object) array('toEmail' => $toEmail, 
                                    'toName' => $subjects->owner->name, 	    						
                                    'fromEmail' => 'olms2.psu@gmail.com',
                                    'fromName' => 'ขออนุญาตลาเรียน',
                                    'fromSubject' => $subjects->name,
                                    'fromTitle' => $request->format,
                                    'fromMessage' => Auth::user()->name,
                                    'formats' => $activity->formats,
                                    'description' => $activity->description,
                                    'Start' => $activity->Start);
            
            $pdf = PDF::loadView( 'mail.email',  ['user' => $teacher]);
            $pdf = $pdf->download('invoice.pdf');
            Mail::send('mail.email', ['user' => $teacher],
            function ($m) use ($teacher, $pdf) {
                $m->from( 'olms2.psu@gmail.com',  $teacher->fromName );
                $m->to($teacher->toEmail, $teacher->toName)->subject( $teacher->fromSubject );
                $m->attachData($pdf, 'olms.pdf');
            });

           
            User::find($subjects->owner->id)->notify(new InvoicePaid($activity->id, $subjects->name));
            return  redirect()->action('SubjectController@status');

        } else if($count >= 3){
            $subject = Subject::find($id);
            return view('activity.create')->with('warning',$warning)->with('subject',$subject);
        
        }  else if($count > 0) {
            $count = $count + 1;
            $activity = new Activity;
            $activity->formats = $request->format;
            $activity->description = $request->description;
            $activity->Start = $request->Start;
            $activity->count = $count;
            $activity->user_id = Auth::user()->id;
            $activity->subject_id = $id;
            $activity->save();
            
            $subjects = Subject::find($id);
            $toEmail = $subjects->owner->email;
            $teacher = (object) array('toEmail' => $toEmail, 
                                    'toName' => $subjects->owner->name, 	    						
                                    'fromEmail' => 'olms2.psu@gmail.com',
                                    'fromName' => 'ขออนุญาตลาเรียน',
                                    'fromSubject' => $subjects->name,
                                    'fromTitle' => $request->format,
                                    'fromMessage' => Auth::user()->name,
                                    'formats' => $activity->formats,
                                    'description' => $activity->description,
                                    'Start' => $activity->Start);
            //$name = $subjects->name.' '.
            $pdf = PDF::loadView( 'mail.email',  ['user' => $teacher]);
            $pdf = $pdf->download('invoice.pdf');
            Mail::send('mail.email', ['user' => $teacher], 
            function ($m) use ($teacher, $pdf) {
                $m->from( 'olms2.psu@gmail.com',  $teacher->fromName );
                $m->to($teacher->toEmail, $teacher->toName)->subject( $teacher->fromSubject );
                $m->attachData($pdf, 'olms.pdf');
            }); 

            User::find($subjects->owner->id)->notify(new InvoicePaid($activity->id, $subjects->name));
            return  redirect()->action('SubjectController@status');
        }      
     }
    public function createLeave($id){
        $subject = Subject::find($id);
        
        return view('activity.create')->with('subject',$subject);
    }
    public function acceptLeave($id){
        $activity = Activity::find($id);
        $activity->status = true;
        $activity->save();
        $activitys = User::find(Auth::user()->id)->activity;
        User::find($activity->user->id)->notify(new InvoicePaid($activity->id, $activity->subject->name));
        
        $email = $activity->subject->owner->email;
        $toEmail = $email;
	    $teacher = (object) array('toEmail' => $toEmail, 
                                    'toName' => $activity->subject->owner->name, 	    						
                                    'fromEmail' => 'olms2.psu@gmail.com',
                                    'fromName' => 'ขออนุญาตลาเรียน',
                                    'fromSubject' => $activity->subject->name,
                                    'fromTitle' => $activity->format,
                                    'fromMessage' => Auth::user()->name,
                                    'formats' => $activity->formats,
                                    'description' => $activity->description,
                                    'Start' => $activity->Start);
                                
        $student = (object) array('toEmail' => $toEmail, 
                                    'toName' => $activity->subject->owner->name, 	    						
                                    'fromEmail' => 'olms2.psu@gmail.com',
                                    'fromName' => 'ขออนุญาตลาเรียน',
                                    'fromSubject' => $activity->subject->name,
                                    'fromTitle' => $activity->format,
                                    'fromMessage' => Auth::user()->name,
                                    'formats' => $activity->formats,
                                    'description' => $activity->description,
                                    'Start' => $activity->Start);
                                  
		Mail::send('mail.email', ['user' => $teacher], 
			function ($m) use ($teacher) {
		        $m->from( 'olms2.psu@gmail.com',  $teacher->fromName );
		        $m->to($teacher->toEmail, $teacher->toName)->subject( $teacher->fromSubject );
	    	});
        Mail::send('mail.email', ['user' => $student], 
			function ($m) use ($student) {
		        $m->from( 'olms2.psu@gmail.com',  $student->fromName );
		        $m->to($student->toEmail, $student->toName)->subject( $student->fromSubject );
            });
        
        return redirect()->back();
    }
    public function ejectLeave($id){
        $activity = Activity::find($id);
        $activity->status = 3;
        $activity->save();

        return redirect()->back();
    }
    public function mark(){
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function search(Request $request){
        $req = $request->search;
        $subjects = Subject::where('name','LIKE','%'.$req.'%')->paginate(10);
        //$code = Subject::where('code','LIKE','%'.$req.'%')->paginate(10);
        return view('subject.index', ['subjects' => $subjects]);
    }

    public function deletes($id){
        $check = Activity::find($id);
        // if($check->user_id == Auth::user()->id){
        //     Activity::destroy($id);
        // }else 
        if($check->status){
            if($check->subject->owner->id == Auth::user()->id){
                Activity::destroy($id);
            }
        }else if($check->status == 3 || !$check->status){
            Activity::destroy($id);
        }

        return redirect()->back();
    }

    public function deleteAll(Request $req){
        $id = $req->input('chk');
        $checks = Activity::find($id);
        foreach ($checks as $key => $check) {
            //echo $check->status;
            if ($check->status == 0) {
                if($check->subject->owner->id == Auth::user()->id){
                    Activity::destroy($id);
                }else if($check->user_id == Auth::user()->id){
                    Activity::destroy($id);
                }
            }
        }
        
        return redirect()->back();
    }
    public function acceptAll(Request $req){
        $id = $req->input('chk');
        $activitys = Activity::find($id);
        foreach ($activitys as $key => $activity) {
            if(!$activity->status){
                if($activity->subject->owner->id == Auth::user()->id){
                    $activity->status = 1;
                    $activity->save();
                    //echo $activity->status;
                }
            }
            //echo $activity;
        }
        
        return redirect()->back();
    }
    public function ejectAll(Request $req){
        $id = $req->input('chk');
        $activitys = Activity::find($id);
        foreach ($activitys as $key => $activity) {
            if(!$activity->status){
                if($activity->subject->owner->id == Auth::user()->id){
                    $activity->status = 3;
                    $activity->save();
                    //echo $activity;
                }
            }
        }
        
        return redirect()->back();
    }
    public function editActivity($id){
        $activity = Activity::find($id);
        return view('activity.create')->with('activity', $activity)->with('subject', $activity->subject);
    }

    public function deleteNews($id){
        $news = News::find($id);
        if($news->subject->owner->id == Auth::user()->id){
            News::destroy($id);
        }

        return redirect()->back();
    }
    public function reqs(){
        $subject = Auth::user()->owner;
        // foreach ($subject as $key => $subjects) {
        //     echo $subjects->activity;
        // }
        return view('activity.status')->with('subject',$subject);
    }
    public function subjectFollow(){
        $subject = Auth::user()->subject()->paginate(10);
        //$subject = $subject->paginate(10);
        //echo $subject;
        return view('subject.index', ['subjects' => $subject]);
    }
    public function ownerSubject() {
        $subject = Auth::user()->owner()->paginate(10);
        return view('subject.index', ['subjects' => $subject]);
    }

    // public function showStatus($id){
    //     $activity = Activity::find($id);

    //     return view('activity.show')->with('activity', $activity);
    // }
}
