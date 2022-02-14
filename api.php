<?php
require_once 'core/autoload.php';

if(!User::auth()){
    Helper::redirect('login.php');
}
//like process
if(isset($_GET['like'])){
    $like_exists = DB::table('articles_likes')->where('user_id',$_GET['user_id'])->andWhere('article_id',$_GET['article_id'])->getOne();
    $like_status = null;
    if(!$like_exists){
        DB::create('articles_likes',[
            'user_id' => $_GET['user_id'],
            'article_id' => $_GET['article_id'],
        ]);
        $like_status = 'Liked';
    }else{
        DB::delete('articles_likes', $like_exists['id']);
        $like_status = 'Unliked';
    }

    $like_count = DB::table('articles_likes')->where('article_id',$_GET['article_id'])->getCount();

    $res = array(
        'status' => true,
        'like_count' => $like_count,
        'like_status' => $like_status
    );

    echo json_encode($res);
}


//comment process
if(isset($_GET['comment'])){
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    $user_id = $data['user_id'];
    $article_id = $data['article_id'];
    $comment = $data['comment'];

    DB::create('articles_comment',[
        'user_id' =>  $user_id,
        'article_id' => $article_id ,
        'comment' => $comment
    ]);


    $res = array(
        'status' =>  true,
    );

    echo json_encode($res);
}

//delete process
if(isset($_GET['delete_article_slug'])){
    $user_id = User::auth()['id'];
    $article_exist = DB::table('articles')->where('slug',$_GET['delete_article_slug'])->andWhere('user_id',$user_id)->getOne(); 
    

    
    if($article_exist){
        //delete local image
        $file_pointer = 'assets/article_images/'.$article_exist['image'];
        unlink($file_pointer);

        //delete article languages
        $article_languages = DB::table('articles_languages')->where('article_id',$article_exist['id'])->get();
        foreach($article_languages as $article_language){
            DB::delete('articles_languages',$article_language['id']);
        }

         //delete article comments
         $article_comments = DB::table('articles_comment')->where('article_id',$article_exist['id'])->get();
         foreach($article_comments as $article_comment){
             DB::delete('articles_comment',$article_comment['id']);
         }
        

        //delete article
        DB::delete('articles',$article_exist['id']);
        
        $res = array(
            'status' => true,
        );
    
        echo json_encode($res);
    } else{
       $res = array(
            'status' => false,
        );
    
        echo json_encode($res);
    }
}


?>