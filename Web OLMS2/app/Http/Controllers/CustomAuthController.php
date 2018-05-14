<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use SoapClient;
use Auth;
use App\User;
use App\Role;
use App\News;

class CustomAuthController extends Controller
{
    public function login (Request $request){
        $this->validate($request,[
            'username' => 'required|string',
            'password' => 'required|string'
            ]);
            
            $params = array('username' => $request->username, 'password' => $request->password);
            $now = new \DateTime();
            $soap_client = new \SoapClient('https://passport.psu.ac.th/authentication/authentication.asmx?WSDL');
            $soap_auth = $soap_client->Authenticate($params);
            
            if(Auth::attempt($params)){
                $subject = User::find(Auth::user()->id)->subject;
                return view('home')->with('subject',$subject);
            } 
            else if ($soap_auth->AuthenticateResult) {
                
                if(Auth::attempt($params)){
                    $subject = User::find(Auth::user()->id)->subject;
                    return view('home')->with('subject',$subject);
                } else {
                    $soapResult = $soap_client->GetUserDetails($params);
                    $firstName = $soapResult->GetUserDetailsResult->string[1];
                    $lastName = $soapResult->GetUserDetailsResult->string[2];
                    $studentId = $soapResult->GetUserDetailsResult->string[3];
                    $gender = $soapResult->GetUserDetailsResult->string[4];
                    $personalId = $soapResult->GetUserDetailsResult->string[5];
                    $faculty = $soapResult->GetUserDetailsResult->string[8];
                    $campus = $soapResult->GetUserDetailsResult->string[10];
                    $prefix = $soapResult->GetUserDetailsResult->string[12];
                    $email = $soapResult->GetUserDetailsResult->string[13];
                    $detail = $soapResult->GetUserDetailsResult;

                    if((int)$request->username){
                        $user = new User;
                        $user->name = $prefix.' '.$firstName.' '.$lastName;
                        $user->username = $studentId;
                        $user->password = bcrypt($request->password);
                        $user->email = $email;
                        $user->save();
                        $user->assignRole('student');
                    } else {
                        $user = new User;
                        $user->name = $prefix.' '.$firstName.' '.$lastName;
                        $user->username = $studentId;
                        $user->password = bcrypt($request->password);
                        $user->email = $email;
                        $user->save();
                        $user->assignRole('teacher');
                    }
                    
                    if(Auth::attempt($params)){
                        $subject = User::find(Auth::user()->id)->subject;
                        return view('home')->with('subject',$subject);
                    }
                }
                // $userDetail = array(
                //     'prefix' => $prefix,
                //     'firstName' => $firstName,
                //     'lastName' => $lastName,
                //     'studentId' => $studentId,
                //     'email' => $email,
                //     'gender' => $gender == 'M' ? "Male" : "Female",
                //     'idNumber' => $personalId,
                //     'faculty' => $faculty,
                //     'campus' => $campus,
                //     'timeStamp' => $now->format('Y-m-d H:i:s')
                // );
                    
                //echo $user;
                //echo $userDetail;
            } 
            else{
                return view('auth.login')->with('alert', 'Login Fail !!');
            }
        
        
    }
    public function mobileLogin (Request $request){
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
            ]);
            
            $params = array('username' => $request->username, 'password' => $request->password);
            $now = new \DateTime();
            $soap_client = new \SoapClient('https://passport.psu.ac.th/authentication/authentication.asmx?WSDL');
            $soap_auth = $soap_client->Authenticate($params);
            
            if(Auth::attempt($params)){

                $data = Auth::user()->roles[0]->slug;
                return response()->json(['subjects'=>$data]);                
            } 
            else if ($soap_auth->AuthenticateResult) {
                
                if(Auth::attempt($params)){

                    $data = Auth::user()->roles[0]->slug;
                    return response()->json(['subjects'=>$data]);             
                } else {
                    $soapResult = $soap_client->GetUserDetails($params);
                    $firstName = $soapResult->GetUserDetailsResult->string[1];
                    $lastName = $soapResult->GetUserDetailsResult->string[2];
                    $studentId = $soapResult->GetUserDetailsResult->string[3];
                    $gender = $soapResult->GetUserDetailsResult->string[4];
                    $personalId = $soapResult->GetUserDetailsResult->string[5];
                    $faculty = $soapResult->GetUserDetailsResult->string[8];
                    $campus = $soapResult->GetUserDetailsResult->string[10];
                    $prefix = $soapResult->GetUserDetailsResult->string[12];
                    $email = $soapResult->GetUserDetailsResult->string[13];
                    $detail = $soapResult->GetUserDetailsResult;

                    if((int)$request->username){
                        $user = new User;
                        $user->name = $prefix.' '.$firstName.' '.$lastName;
                        $user->username = $studentId;
                        $user->password = bcrypt($request->password);
                        $user->email = $email;
                        $user->save();
                        $user->assignRole('student');
                        
                    } else {
                        $user = new User;
                        $user->name = $prefix.' '.$firstName.' '.$lastName;
                        $user->username = $studentId;
                        $user->password = bcrypt($request->password);
                        $user->email = $email;
                        $user->save();
                        $user->assignRole('teacher');
                        
                    }
                    
                    if(Auth::attempt($params)){
                        
                        $data = Auth::user()->roles[0]->slug;
                        return response()->json(['subjects'=>$data]);             
                    }
                }
            } 
            else{
                return response()->json(['subjects'=> 'Fail']);
            };
    }
}
