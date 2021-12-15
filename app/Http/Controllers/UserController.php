<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function regist(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $return->status = "500";
        $return->msg = "관리자에게 문의";
        $return->data = $request->user_id;

        /* 중복 체크 - start*/
        $email_cnt = User::where('email',$request->email)->count();
        $phone_cnt = User::where('phone',$request->phone)->count();

        /*if($email_cnt){
            $return->status = "602";
            $return->msg = "사용중인 이메일";
            $return->data = $request->email;
        }else if ($phone_cnt){
            $return->status = "603";
            $return->msg = "사용중인 폰 번호";
            $return->data = $request->phone;
        //중복 체크 - end
        }else{*/
            $result = User::insertGetId([
                'name'=> $request->name ,
                'nickname'=> $request->nickname ,
                'email' => $request->email, 
                'password' => $request->password, 
                'user_id' => $request->user_id,
                'phone' => $request->phone, 
                'user_type' => $request->user_type,
                'push' => $request->push,
                'push_event' => $request->push_event,
                'created_at' => Carbon::now(),
                'password' => Hash::make($request->password)
            ]);

            if($result){

                Auth::loginUsingId($result);
                $login_user = Auth::user();

                $token = $login_user->createToken('user');

                $return->status = "200";
                $return->msg = "success";
                $return->data = $request->name;
                $return->token = $token->plainTextToken;
            }
        //}
        

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

        //return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    public function login(Request $request){
        $user = User::where('email' , $request->email)->where('leave','N')->first();

        $return = new \stdClass;

        if(!$user){
            $return->status = "501";
            $return->msg = "존재하지 않는 아이디 입니다.";
            $return->email = $request->email;
        }else if (Hash::check($request->password, $user->password)) {
            //echo("로그인 확인");
            Auth::loginUsingId($user->id);
            $login_user = Auth::user();

            $token = $login_user->createToken('user');

            $return->status = "200";
            $return->msg = "성공";
            $return->dormant = $login_user->dormant;
            $return->token = $token->plainTextToken;
            
            //dd($token->plainTextToken);    
        }else{
            $return->status = "500";
            $return->msg = "아이디 또는 패스워드가 일치하지 않습니다.";
            $return->email = $request->email;
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function su(Request $request){
        
        $return = new \stdClass;
        Auth::loginUsingId($request->id);
        $login_user = Auth::user();

        $token = $login_user->createToken('user');

        $return->status = "200";
        $return->msg = "성공";
        $return->dormant = $login_user->dormant;
        $return->token = $token->plainTextToken;

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function logout(Request $request){
        $user_info = Auth::user();
        $user = User::where('id', $user_info->id)->first();
        $user->tokens()->delete();

        $return = new \stdClass;
        $return->status = "200";
        $return->msg = "success";

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function login_check(Request $request){
        
        $return = new \stdClass;
        //$login_user = Auth::user();
        //$user_id = $login_user->getId();

        if(Auth::check()){
            $return->status = "200";
            $return->login_status = "Y";
        }    

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        
    }
    

    public function find_user_id(Request $request){
        $user = User::where('phone' , $request->phone)->first();
        
        if (isset($user->id)) {
            echo("사용자 아이디는 ".$user->user_id." 입니다.");       
        }else{
            echo("등록되지 않은 연락처 입니다.");       
        }
    }

    public function list(Request $request){
        $start_no = $request->start_no;
        $row = $request->row;
        
        $rows = User::where('id' ,">=", $start_no)->where('user_type','0')->orderBy('id', 'desc')->orderBy('id')->limit($row)->get();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        $list->cnt = count($rows);
        $list->data = $rows;
        
        
        return response()->json($list, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        
    }

    public function check_email(Request $request){
        
        //dd($request);
        $return = new \stdClass;

        /* 중복 체크 - start*/
        $email_cnt = User::where('email',$request->email)->count();

        if($email_cnt){
            $return->usable = "N";
            $return->msg = "사용중인 이메일";
            $return->email = $request->email;
        }else{
            $return->usable = "Y";
            $return->msg = "사용가능 이메일";
            $return->email = $request->email;            
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }  
    
    public function check_nickname(Request $request){
        
        //dd($request);
        $return = new \stdClass;

        /* 중복 체크 - start*/
        $nickname_cnt = User::where('nickname',$request->nickname)->count();

        if($nickname_cnt){
            $return->usable = "N";
            $return->msg = "사용중인 닉네임";
            $return->nickname = $request->nickname;
        }else{
            $return->usable = "Y";
            $return->msg = "사용가능 닉네임";
            $return->nickname = $request->nickname;            
        }

        echo(json_encode($return));

    }  

    public function info(){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->data = $login_user;

        if($login_user->user_type == 1){
            $hotel_info = Hotel::where('partner_id',$login_user->id)->first();
            if($hotel_info){
                $return->hotel_id = $hotel_info->id;
            }
            
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update(Request $request){
        //dd($request);
        $return = new \stdClass;


        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "변경 완료";
        $return->key = $request->key;
        $return->value = $request->value;

        $key = $request->key;
        $value = $request->value;
        $user_id = $login_user->id;

        if($key == "password"){
            $value = Hash::make($request->value);
        }

        $result = User::where('id', $user_id)->update([$key => $value]);

        if(!$result){
            $return->status = "500";
            $return->msg = "변경 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function update_info(Request $request){
        //dd($request);
        $return = new \stdClass;

        $return->status = "200";
        $return->msg = "변경 완료";
        
        $result = User::where('id', $request->user_id)->update([
            'name'=> $request->name ,
            'nickname'=> $request->nickname ,
            'email' => $request->email, 
            'phone' => $request->phone, 
            'user_type' => $request->user_type,
            'push' => $request->push,
            'push_event' => $request->push_event,
        ]);

        if(!$result){
            $return->status = "500";
            $return->msg = "변경 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function leave(Request $request){
        //dd($request);
        $return = new \stdClass;
        $login_user = Auth::user();

        $return->status = "200";
        $return->msg = "탈퇴처리 완료";

        $user_id = $login_user->id;

        $result = User::where('id', $user_id)->update(['leave' => 'Y']);

        if(!$result){
            $return->status = "500";
            $return->msg = "탈퇴처리 실패";
        }

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;

    }

    public function not_login(){
        header("Access-Control-Allow-Origin: *");
        $return = new \stdClass;
    
        $return->status = "500";
        $return->msg = "Not Login";

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
    }

    public function certifications(Request $request){ // 본인인증 후 정보 리턴
        $imp_uid = $request->imp_uid;
    
        $_api_url = 'https://api.iamport.kr/users/getToken';     // 본인인증 후 access_token 리턴
        
        $_param['imp_key'] = '5545083858481518';           // 아임포트 키
        $_param['imp_secret'] = '48bf35dafb0f4c339dc5d3bc24238d6ecb1d03ba88c53857eb261535abeec5bb4c63c390b173dfad';    // 아임포트 시크릿
       
        $_curl = curl_init();
        curl_setopt($_curl,CURLOPT_URL,$_api_url);
        curl_setopt($_curl,CURLOPT_POST,true);
        curl_setopt($_curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($_curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($_curl,CURLOPT_POSTFIELDS,$_param);
        $_result = curl_exec($_curl);
        //dd($_result);
        curl_close($_curl);

        $_result = json_decode($_result);
        //dd($_result);
        $access_token = $_result->response->access_token;

        $headers = [
            'Authorization:'.$access_token
        ];
        
        $url = "https://api.iamport.kr/certifications/".$imp_uid; // 정보 요청 url - access_token 추가
        $_curl2 = curl_init();
        curl_setopt($_curl2,CURLOPT_URL,$url);
        curl_setopt($_curl2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($_curl2,CURLOPT_RETURNTRANSFER,true);
        $_result2 = curl_exec($_curl2);
        $_result2 = json_decode($_result2);
            
        $user_infos= $_result2->response;
    
        $return = new \stdClass;
        $return->name = $user_infos->name;
        $return->birthday = $user_infos->birthday;
        //$return->phone = $user_infos->phone;
        $return->phone = "010-000-0000";
        curl_close($_curl2);

        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        

    }

    


}
