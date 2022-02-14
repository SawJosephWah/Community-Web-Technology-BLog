<?php

use JetBrains\PhpStorm\Language;

use function PHPSTORM_META\type;

class Article{
    static function allPosts(){
        $articles = DB::table('articles')->orderBy('id','desc')->paginate(6);
     
        foreach($articles['data'] as $key => $value){
            $article_comment_count = DB::table('articles_comment')->where('article_id',$value['id'])->getCount();
            $article_like_count = DB::table('articles_likes')->where('article_id',$value['id'])->getCount();

        
            $articles['data'][$key]['article_comment_count'] =  $article_comment_count;
            $articles['data'][$key]['article_like_count'] =  $article_like_count;
    
        }

        return $articles;
    }

    static function details($slug){
        $article = DB::table('articles')->where('slug',$slug)->getOne();

        $comment_count = DB::table('articles_comment')->where('article_id',$article['id'])->getCount();
        $comments = DB::table('articles_comment')->where('article_id',$article['id'])->orderBy('id','desc')->get();

        $like_count = DB::table('articles_likes')->where('article_id',$article['id'])->getCount();

        $category = DB::table('categories')->where('id',$article['category_id'])->getOne();

        $languages = DB::raw('SELECT languages.id ,languages.name , languages.slug FROM articles_languages 
        LEFT JOIN 
        languages ON
        languages.id = articles_languages.language_id
        WHERE
        article_id ='.$article['id'])->get();

        $article['comment_count'] = $comment_count;
        $article['comments'] = $comments;
        $article['like_count'] = $like_count;
        $article['category'] = $category;
        $article['languages'] = $languages;

        return $article;
    }

    static function articlesByCategory($slug){
        $category= DB::table('categories')->where('slug',$slug)->getOne();
        $articles = DB::table('articles')->where('category_id',$category['id'])->orderBy('id','desc')->paginate(6);
     
        foreach($articles['data'] as $key => $value){
            $article_comment_count = DB::table('articles_comment')->where('article_id',$value['id'])->getCount();
            $article_like_count = DB::table('articles_likes')->where('article_id',$value['id'])->getCount();

        
            $articles['data'][$key]['article_comment_count'] =  $article_comment_count;
            $articles['data'][$key]['article_like_count'] =  $article_like_count;
    
        }

        return $articles;
    }


    static function articlesByLanguage($slug){
        

        $language = DB::table('languages')->where('slug',$slug)->getOne();
        $articles = DB::raw('SELECT articles.* FROM articles_languages LEFT JOIN articles ON articles_languages.article_id = articles.id WHERE articles_languages.language_id ='.$language['id'])->orderBy('id','desc')->paginate(6);
     
        foreach($articles['data'] as $key => $value){
            $article_comment_count = DB::table('articles_comment')->where('article_id',$value['id'])->getCount();
            $article_like_count = DB::table('articles_likes')->where('article_id',$value['id'])->getCount();

        
            $articles['data'][$key]['article_comment_count'] =  $article_comment_count;
            $articles['data'][$key]['article_like_count'] =  $article_like_count;
    
        }

        return $articles;

    }

    static function create($request){
        
        if(isset($request)){
            $errors = [];
            if(!$request['title']){
                $errors[] = 'Please Fill Title';
            }

            if(!$request['category']){
                $errors[] = 'Please Choose Category';
            }
            
            if(!isset($request['languages'])){
                $errors[] = 'Please Select language(s)';
            } 

            if($_FILES["article_img"]['error'] == 4){
                $errors[] = 'Please Select Image';
            } 

            if(!$request['description']){
                $errors[] = 'Please Enter Description';
            } 

            if(!count($errors) == 0){
                return $errors;
            }else{
                $filename = rand(1,999).$_FILES["article_img"]["name"];
                $tempname = $_FILES["article_img"]["tmp_name"];    
                $path = "assets/article_images/".$filename;
                

                if (move_uploaded_file($tempname, $path))  {
                    $new_article_id = DB::create('articles',[
                        'slug' => rand(1,999).str_replace(" ","_",$request['title']),
                        'title' => $request['title'],
                        'image' =>  $filename,
                        'category_id' => $request['category'],
                        'user_id' => User::auth()['id'],
                        'description' =>$request['description']
                    ]);

                    foreach ($request['languages'] as $language) {
                       DB::create('articles_languages',[
                            'article_id' => $new_article_id,
                            'language_id' => $language
                       ]);
                    }

                    return 'success';
                }
            }
            
        }
    }

    static function my_article(){
        $user_id = User::auth()['id'];
        $articles = DB::table('articles')->where('user_id', $user_id)->orderBy('id','desc')->paginate(6);
 
        foreach($articles['data'] as $key => $value){
            $article_comment_count = DB::table('articles_comment')->where('article_id',$value['id'])->getCount();
            $article_like_count = DB::table('articles_likes')->where('article_id',$value['id'])->getCount();

        
            $articles['data'][$key]['article_comment_count'] =  $article_comment_count;
            $articles['data'][$key]['article_like_count'] =  $article_like_count;
    
        }

        return $articles;
    }

    static function update($request){
        // echo "<pre>";
        // print_r($request);

        if(isset($request)){
            $errors = [];
            if(!$request['title']){
                $errors[] = 'Please Fill Title';
            }

            if(!$request['category']){
                $errors[] = 'Please Choose Category';
            }
            
            if(!isset($request['languages'])){
                $errors[] = 'Please Select language(s)';
            } 

            

            if(!$request['description']){
                $errors[] = 'Please Enter Description';
            } 

            

            if(!count($errors) == 0){
                return $errors;
            }else{
                $old_article = DB::table('articles')->where('id',$request['article_id'])->getOne();

                if($_FILES["article_img"]['error'] != 4){
                //delete local image

                $file_pointer = 'assets/article_images/'.$old_article['image'];
                unlink($file_pointer);
                
                //insert new image
                $filename = rand(1,999).$_FILES["article_img"]["name"];
                $tempname = $_FILES["article_img"]["tmp_name"];    
                $path = "assets/article_images/".$filename;

                move_uploaded_file($tempname, $path);
                } 
                
                //delete article_languages
                // DB::delete()
                $article_languages = DB::table('articles_languages')->where('article_id',$request['article_id'])->get();
                foreach($article_languages as $article_language){
                    DB::delete('articles_languages',$article_language['id']);
                }
               

                // $new_article_id = DB::create('articles',[
                //     'slug' => rand(1,999).str_replace(" ","_",$request['title']),
                //     'title' => $request['title'],
                //     'image' =>  $filename,
                //     'category_id' => $request['category'],
                //     'user_id' => User::auth()['id'],
                //     'description' =>$request['description']
                // ]);

                $updated_article_id = DB::update('articles',[
                    'slug' => rand(1,999).str_replace(" ","_",$request['title']),
                    'title' => $request['title'],
                    'image' =>  $filename  ? $filename :  $old_article['image'],
                    'category_id' => $request['category'],
                    'user_id' => User::auth()['id'],
                    'description' =>$request['description']
                ], $request['article_id']);

                foreach ($request['languages'] as $language) {
                   DB::create('articles_languages',[
                        'article_id' =>$request['article_id'],
                        'language_id' => $language
                   ]);
                }

                $updated_slug = DB::table('articles')->where('id',$updated_article_id)->getOne()['slug'];

                return $updated_slug;
         
                

            }
            
        }
    }
}


?>

