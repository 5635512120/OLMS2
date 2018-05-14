<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\User;
use App\Subject;
use Auth;
use App\News;
use Mail;
use App\Role;
use App\Activity;
use App\Notifications\InvoicePaid;
use App\Notifications\NewsNotifications;
use PDF;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MailSend;

class MobileController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //รายวิชาที่ติดตาม
    public function index(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = User::find(Auth::user()->id)->subject;
            //$data['user'] = User::find(Auth::user()->id)

            return response()->json(['subjects'=>$data]);  
        }

        //return $subjects;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //แสดงข่าวหน้าโชว์รายวิชา
    public function news(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
           // $data = Subject::find($request->id)->orderBy('created_at', 'desc')->news;
            $data = News::where('subject_id',$request->id)->orderBy('created_at', 'desc')->get();
            //$data['user'] = User::find(Auth::user()->id)

            return response()->json(['subjects'=>$data]);  
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //กดติดตามรายวิชา
    public function followsubject(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $subject = Subject::find($request->id);
            $users = Auth::user()->id;
            $subject->user()->toggle($users);  
            
            $student = $subject->user->find($users);

            return  response()->json(['follow' => $student]);
        } 
    }

    //เช็คสถาณะติดตามรายวิชา
    public function statusfollow(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $subject = Subject::find($request->id);
            $users = Auth::user()->id;
            $student = $subject->user->find($users);

            return  response()->json(['follow' => $student]);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feedNews(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = array();
            $subjects = Auth::user()->subject;
            foreach ($subjects as $subject) {
                //$news = News::where('user_id' ,$subject->owner_id)->get();
                foreach ($subject->news as $new) {
                    $new->name = $subject->name;
                    $new->code = $subject->code;
                    $new->id = $new->subject_id;
                    array_push($data, $new);
                }
            }
            return response()->json(['subjects'=>$data]); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mobileSearch(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $req = $request->search;
            $data = Subject::where('name','LIKE','%'.$req.'%')->get();

            return response()->json(['subjects'=>$data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //ขอลา
    public function leave(Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);

        if (Auth::attempt($params)) {
            $count = Activity::where('user_id',Auth::user()->id)->where('subject_id',$request->id)->where('status',1)->count();
            
            if($count == 0){
                
                $this->leavesubject($request->username, $request->password, $request->id, $request->format, $request->Start, $request->description);
                return response()->json(['subjects'=>true]);

            } else if($count >= 3){

                return  response()->json(['subjects'=>false]);
            }  else if($count > 0) {

                $this->leavesubject($request->username, $request->password, $request->id, $request->format, $request->Start, $request->description);
                return response()->json(['subjects'=>true]);
            }
        }
    }

    public function notifications(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = Auth::user()->unreadnotifications;
            return response()->json(['subjects'=>$data]);  
        } 
    }

    public function readallnotifications(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {

            Auth::user()->unreadNotifications->markAsRead();
            $data = Auth::user()->unreadnotifications;

            return response()->json(['subjects'=>$data]);  
        } 
    }

    public function statusactivitys(Request $request)
    {
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {

            $datas = array();
            $data = User::find(Auth::user()->id)->activity;

            foreach ($data as  $value) {
                $value->nameSubject = Subject::find($value->subject_id)->name;
                $value->code = Subject::find($value->subject_id)->code;
                array_push($datas, $value);
            }

            return response()->json(['subjects'=>$datas]);  
        } 
    }

    public function leavesubject($username, $password, $id, $format, $start, $description){
        $params = array('username' => $username, 'password' => $password);

        if (Auth::attempt($params)) {
            $result = (string)$start;

            $count = Activity::where('user_id',Auth::user()->id)->where('subject_id',$id)->count();
            $user = Auth::user();
            $activitys = new Activity;
            $activitys->formats = $format;
            $activitys->description = $description;
            $activitys->Start = $result;
            $activitys->count = $count++;
            $activitys->user_id = Auth::user()->id;
            $activitys->subject_id = $id;
            $activitys->save();

            $subjects = Subject::find($id);
            $toEmail = $subjects->owner->email;
            $teacher = (object) array('toEmail' => $toEmail, 
                                    'toName' => $subjects->owner->name, 	    						
                                    'fromEmail' => 'olms2.psu@gmail.com',
                                    'fromName' => 'ขออนุญาตลาเรียน',
                                    'fromSubject' => $subjects->name,
                                    'fromTitle' => $format,
                                    'fromMessage' => $user->name,
                                    'formats' => $format,
                                    'description' => $description,
                                    'Start' => $result
                                );
             $pdf = PDF::loadView( 'mail.email',  ['user' => $teacher]);
             $pdf = $pdf->download('invoice.pdf');
             Mail::send('mail.email', ['user' => $teacher],
             function ($m) use ($teacher, $pdf) {
                 $m->from( 'olms2.psu@gmail.com',  $teacher->fromName );
                 $m->to($teacher->toEmail, $teacher->toName)->subject( $teacher->fromSubject );
                 $m->attachData($pdf, 'olms.pdf');
             });
             User::find($subjects->owner->id)->notify(new InvoicePaid($activitys->id, $subjects->name));
        }
    }

    //กระดานข่าวTeacher
    public function teacherNews (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $news = News::where('user_id' ,Auth::user()->id)->orderBy('created_at', 'desc')->get();
    
            foreach($news as $new) {
                $new->name = $new->subject->name;
                $new->code = $new->subject->code;
                $new->id = $new->subject->id;
            }
            return response()->json(['subjects'=>$news]); 
            
        }
    }

    public function teacherComment (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $news = new News;
            $news->comment = $request->comment;
            $news->user_id = Auth::user()->id;
            $news->subject_id = $request->id;
            $news->save();

            $subject = Subject::find($request->id);
            foreach ($subject->user as $key => $users) {
                User::find($users->id)->notify(new NewsNotifications($subject->id, $subject->name));
            }

            $data = News::where('subject_id',$request->id)->orderBy('created_at', 'desc')->get();

            return response()->json(['subjects'=>$data]); 
            
        }
    }

    public function teacherSubject (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $subjects = Auth::user()->owner;

            return response()->json(['subjects'=>$subjects]); 
            
        }
    }

    public function teacherRequest (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = array();
            $subjects = Subject::where('owner_id', Auth::user()->id)->get();

            foreach ($subjects as $subject) {
                foreach ($subject->activity as $activity) {
                    if($activity->status != 1 && $activity->status != 3){
                        array_push($data, $activity);
                    }
                }
            }
            foreach ($data as $datas) {
                $datas->nameSubject = Subject::find($datas->subject_id)->name;
                $datas->code = Subject::find($datas->subject_id)->code;
            }
            $datas =  $data;
            return response()->json(['subjects'=>$datas]); 
        }
    }

    public function teacherEject (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = array();
            $subjects = Subject::where('owner_id', Auth::user()->id)->get();

            $activity = Activity::find($request->id);
            $activity->status = 3;
            $activity->save();

            foreach ($subjects as $subject) {
               foreach($subject->activity as $activity){
                    if($activity->status != 1 && $activity->status != 3)
                        array_push($data, $activity);
               }
            }
            foreach ($data as $datas) {
                $datas->nameSubject = Subject::find($datas->subject_id)->name;
                $datas->code = Subject::find($datas->subject_id)->code;
            }
            $datas =  $data;
            return response()->json(['subjects'=>$datas]); 
        }
    }

    public function teacherAccept (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = array();
            $subjects = Subject::where('owner_id', Auth::user()->id)->get();

            $activity = Activity::find($request->id);
            $activity->status = 1;
            $activity->save();

            foreach ($subjects as $subject) {
               foreach($subject->activity as $activity){
                    if($activity->status != 1 && $activity->status != 3)
                        array_push($data, $activity);
               }
            }
            foreach ($data as $datas) {
                $datas->nameSubject = Subject::find($datas->subject_id)->name;
                $datas->code = Subject::find($datas->subject_id)->code;
            }
            $datas =  $data;
            return response()->json(['subjects'=>$datas]); 
        }
    }
    
    public function mobileDelete (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $check = Activity::find($request->id);

            if(($check->user_id == Auth::user()->id) && ($check->status == 0)){
                Activity::destroy($request->id);
            } 
            
            $datas = array();
            $data = User::find(Auth::user()->id)->activity;

            foreach ($data as  $value) {
                $value->nameSubject = Subject::find($value->subject_id)->name;
                $value->code = Subject::find($value->subject_id)->code;
                array_push($datas, $value);
            }

            return response()->json(['subjects'=>$datas]); 
        }
    }

    public function mobilecodeSubject (Request $request){
        $params = array('username' => $request->username, 'password' => $request->password);
        
        if (Auth::attempt($params)) {
            $data = Subject::find($request->id)->code;
            return response()->json(['subjects'=>$data]); 
        }
    }
}
