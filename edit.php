<?php
    require_once 'inc/header.php';
    if(!User::auth()){
        Helper::redirect('login.php');
    }

    if(isset($_GET['slug'])){
        $user_id = User::auth()['id'];
        $old_article = DB::table('articles')->where('slug',$_GET['slug'])->andWhere('user_id',$user_id)->getOne();

        if(!$old_article){
                Helper::redirect('404.php');
        }

    }else{
        Helper::redirect('index.php');
    }
    


    

    if(isset($_POST['submit'])) {
     
        $article = Article::update($_POST);
        if(!is_array($article)){
            Helper::redirect('details.php?success=updated_article&slug='.$article);
        }
    }
?>
<div class="col-md-8">

<div class="card card-dark">
        <div class="card-header">
                <h3>Edit Article</h3>
        </div>
        <div class="card-body">
        <?php
            if(isset($article) && is_array($article)){
            foreach($article as $error){
        ?>
            <div class="alert alert-danger">
        <?php
            echo $error;
        ?>
            </div>
                <?php 
                    }
                }
            ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="article_id" value="<?php echo  $old_article['id']?>">
                        <div class="form-group">
                                <label for="" class="text-white">Enter Title</label>
                                <input type="name" class="form-control" name="title" placeholder="enter title" value="<?php echo $old_article['title']?>">
                        </div>
                        <div class="form-group">
                                <label for="" class="text-white">Choose Category</label>
                                <select name="category" id="" class="form-control">
                                    <?php 
                                    $categories = DB::table('categories')->get();

                                    foreach($categories as $category){
                                    ?>
                                        <option value="<?php echo $category['id'];?>" <?php if($category['id'] == $old_article['category_id']){?> selected <?php }?>>
                                            <?php echo $category['name'];?>
                                        </option>
                                    <?php }?>
                                </select>
                        </div>
                        <div class="form-check form-check-inline">
                           
                        <?php 
                            $languages = DB::table('languages')->get();

                            $old_article_languages = DB::table('articles_languages')->where('article_id',$old_article['id'])->get();
                            
                            $old_languages = [];
                            foreach($old_article_languages as $old_language){
                                $old_languages[] = $old_language['language_id'];
                            }
                           


                            foreach($languages as $language){
                        ?>
                        <span class="mr-2">
                                        <input class="form-check-input" type="checkbox" name="languages[]" <?php if(in_array($language['id'],$old_languages)){?> checked <?php }?> value="<?php echo $language['id'];?>">
                                        <label class="form-check-label" for="inlineCheckbox1">
                                            <?php echo $language['name'];?>    
                                        </label>
                        </span>
                        <?php }?>
                           
                        </div>
                        <br><br>
                        <div class="form-group">
                        <img src="assets/article_images/<?php echo $old_article['image'];?>" class="mb-2" alt="" src style="width: 200px;height:50%">
                        <br>

                                <label for="">Choose Image</label>
                                <input type="file" class="form-control" name="article_img">
                        </div>
                        
                        <div class="form-group">
                                <label for="" class="text-white">Enter Articles</label>
                            <textarea name="description" class="form-control" id=""
                                cols="30" rows="10"><?php echo $old_article['description']?></textarea>
                        </div>
                        <input type="submit" value="Update" name="submit" class="btn  btn-outline-warning">
                </form>
        </div>
</div>
</div>

<?php
    require_once 'inc/footer.php';
?>
 