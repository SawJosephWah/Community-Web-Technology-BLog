<?php
    require_once 'inc/header.php';
    if(!User::auth()){
        Helper::redirect('login.php');
    }

    if(isset($_POST['submit'])) {
        $article = Article::create($_POST);
        if($article == 'success'){
            Helper::redirect('index.php?success=article_create');
        }
    }
?>
<div class="col-md-8">

<div class="card card-dark">
        <div class="card-header">
                <h3>Create New Article</h3>
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
                        <div class="form-group">
                                <label for="" class="text-white">Enter Title</label>
                                <input type="name" class="form-control" name="title" placeholder="enter title">
                        </div>
                        <div class="form-group">
                                <label for="" class="text-white">Choose Category</label>
                                <select name="category" id="" class="form-control">
                                    <?php 
                                    $categories = DB::table('categories')->get();

                                    foreach($categories as $category){
                                    ?>
                                        <option value="<?php echo $category['id'];?>">
                                            <?php echo $category['name'];?>
                                        </option>
                                    <?php }?>
                                </select>
                        </div>
                        <div class="form-check form-check-inline">
                           
                        <?php 
                                    $languages = DB::table('languages')->get();

                            foreach($languages as $language){
                        ?>
                        <span class="mr-2">
                                        <input class="form-check-input" type="checkbox" name="languages[]" value="<?php echo $language['id'];?>">
                                        <label class="form-check-label" for="inlineCheckbox1">
                                            <?php echo $language['name'];?>    
                                        </label>
                        </span>
                        <?php }?>
                           
                        </div>
                        <br><br>
                        <div class="form-group">
                                <label for="">Choose Image</label>
                                <input type="file" class="form-control" name="article_img">
                        </div>
                        
                        <div class="form-group">
                                <label for="" class="text-white">Enter Articles</label>
                                <textarea name="description" class="form-control" id=""
                                cols="30" rows="10"></textarea>
                        </div>
                        <input type="submit" value="Create" name="submit" class="btn  btn-outline-warning">
                </form>
        </div>
</div>
</div>

<?php
    require_once 'inc/footer.php';
?>
 