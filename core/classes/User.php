<?php


class User{
    public static function auth(){
        if(isset($_SESSION["vlog_user_session"])){
            return DB::table('users')->where('id',$_SESSION["vlog_user_session"]['id'])->getOne();
        }
        return false;

    }
    public function register($request){
     
        if(isset($request)){
            $errors = [];
            if(!$request['name']){
                $errors[] = 'Please Fill Name';
            }
            if(!$request['email']){
                $errors[] = 'Please Fill email';
            }else{
                if(!filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
                    $errors[] = 'Please Fill Valid Email';
                }
            }
            
            if(!$request['password']){
                $errors[] = 'Please Fill Password';
            }

            //email already exist checks
            $userExist = DB::table('users')->where('email',$request['email'])->getOne();
    
            if($userExist){
                $errors[] = 'Email is already exists';
            }

            if(count($errors) != 0){
                return $errors;
            }else{
              
                $user_id = DB::create('users',[
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'passward' =>  password_hash($request['password'],PASSWORD_BCRYPT) 
                ]);

                $new_user = DB::table('users')->where('id',$user_id)->getOne();

               
                $_SESSION["vlog_user_session"]=$new_user;

                return true ;
              
            }
        }
        
    }

    public function login($request){
        // print_r($request);
        if(isset($request)){
            $errors = [];
            
            if(!$request['email']){
                $errors[] = 'Please Fill email';
            }else{
                if(!filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
                    $errors[] = 'Please Fill Valid Email';
                }else{
                     //email exist checks
                    $userExist = DB::table('users')->where('email',$request['email'])->getOne();
                    if(!$userExist){
                        $errors[] = 'Email not found';
                    }else{
                        if(!password_verify($request['password'], $userExist['passward'])){
                            $errors[] = 'Invalid password';
                        }
                    }
                }
            }
            
            if(!$request['password']){
                $errors[] = 'Please Fill Password';
            }


            if(count($errors) != 0){
                return $errors;
            }else{
              
                $user = DB::table('users')->where('email',$request['email'])->getOne();
                //  session_start();
                $_SESSION["vlog_user_session"]=$user;
                // print_r($user);

                return true ;
              
            }
        }
    }

    public function update_profile($request){
        if(isset($request)){
            $errors = [];
            if(!$request['name']){
                $errors[]= 'Please Enter Name'; 
            }

            if(!count($errors) == 0){
                return $errors;
            }else{
                if(!($_FILES["profile_img"]['error'] == 4)){
                    if($this->auth()['image']){
                        $file_pointer = 'assets/profile_images/'.$this->auth()['image'];
                        unlink($file_pointer);
                    }

                    $filename = rand(1,999).$_FILES["profile_img"]["name"];
                    $tempname = $_FILES["profile_img"]["tmp_name"];    
                    $path = "assets/profile_images/".$filename;

                    move_uploaded_file($tempname, $path);
                }
                
              
                DB::update('users',[
                    'name' => $request['name'],
                    'passward' => $request['password'] ? password_hash($request['password'],PASSWORD_BCRYPT) : $this->auth()['passward'],
                    'image' =>!($_FILES["profile_img"]['error'] == 4) ? $filename : $this->auth()['image']
                ], $this->auth()['id']);
                 return 'success';
            }
        }
    }
}

?>