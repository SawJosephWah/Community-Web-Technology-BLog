<?php
    require_once 'inc/header.php';
    if(!User::auth()){
        Helper::redirect('login.php');
    }

    if(!isset($_GET['slug'])){
        Helper::redirect('404.php');
    } else{
        $article = Article::details($_GET['slug']);
        if(!$article['id']){
            Helper::redirect('404.php');
        }

    }

?>
                           
                           <div class="col-md-8">

<div class="card card-dark">
        <div class="card-body">
                <div class="row">
                        <div class="col-md-12">
                                <div class="card card-dark">
                                        <div class="card-body">
                                                <div class="row">
                                                        <!-- icons -->
                                                        <div class="col-md-3">
                                                                <div class="row">
                                                                        <div
                                                                                class="col-md-4 text-center">
                                                                                
                                                                                <i class="fas fa-heart text-warning" id="like_btn" user_id="<?php echo User::auth()['id']?>" article_id="<?php echo $article['id']?>" style="cursor :pointer">
                                                                                </i>
                                                                               
                                                                               
                                                                                <small class="text-muted">
                                                                                        <span id="like_count_status">
                                                                                                <?php echo $article['like_count'];?>   
                                                                                        </span>
                                                                                </small>
                                                                        </div>
                                                                        <div
                                                                                class="col-md-4 text-center">
                                                                                <i
                                                                                        class="far fa-comment text-dark"></i>
                                                                                <small class="text-muted">
                                                                                        <?php echo $article['comment_count'];?> 
                                                                                </small>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- Icons -->

                                                        <!-- Category -->
                                                        <div class="col-md-3">
                                                                <div class="row">
                                                                        <div
                                                                                class="col-md-12">
                                                                                <span  class="badge badge-primary">
                                                                                <?php echo $article['category']['name'];?>
                                                                                </span>

                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <!-- Category -->


                                                        <!-- Language -->
                                                        <div class="col-md-3">
                                                                <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?php 
                                                                            foreach($article['languages'] as $language){
                                                                                ?>
                                                                                <span class="badge badge-success">
                                                                                    <?php echo $language['name']?>
                                                                                </span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <!-- Language -->

                                                         <!-- Edit btn -->
                                                         <?php 
                                                         if(User::auth()['id'] == $article['user_id']){
                                                         ?>
                                                        <div class="col-md-3">
                                                                <a href="edit.php?slug=<?php echo $article['slug']?>" class="badge badge-warning">
                                                                                   EDIT
                                                                </a>
                                                                <span id="delete_btn" slug="<?php echo $article['slug']?>"  style="cursor:pointer" class="badge badge-secondary">
                                                                                   DELETE
                                                                </span>
                                                        </div>
                                                        <?php }?>
                                                        <!-- Edit btn-->

                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <br>
                <div class="col-md-12">
                        <h3><?php echo $article['title'];?></h3>
                        <p class="text-muted">
                        <?php echo DB::table('users')->where('id',$article['user_id'])->getOne()['name']; ?>
                        </p>
                        <p>
                        <?php echo $article['description'];?>
                        </p>
                </div>

                <!-- Comments -->
                <div class="card card-dark">
                        <div class="card-header">
                                <h4>Comments</h4>
                        </div>
                        <div class="card-body">
                         <div class="row">
                                <div class="col-md-11">
                        <input type="text" class="form-control" placeholder="Enter Comment" id="comment_box" 
                        user_id = <?php echo User::auth()['id'];?>
                        article_id = <?php echo $article['id'];?>>
                                </div>
                                <div class="col-md-1">
                                <i class="fas fa-paper-plane float-right m-1" style="cursor: pointer;" id="comment_send_icon"></i>
                                </div>
                        </div>
                                
                        </div>
                        <div class="card-body">
                                <div></div>
                                <!-- Loop Comment -->
                                <?php
                                foreach($article['comments'] as $comment){
                                    $user = DB::table('users')->where('id',$comment['user_id'])->getOne();
                                    ?>
                                    <div class="card-dark mt-1">
                                        <div class="card-body">
                                                <div class="row">
                                                        <div class="col-md-2">
                                                           
                                                                <?php
                                                                if( !$user['image']){
                                                                    ?>
                                                                <div>
                                <img src="assets/img/user_demo.png"  alt="" style="  width: 50px;height: 50px;border-radius: 50%;">
                                                                </div>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <img src="assets/profile_images/<?php echo $user['image'];?>" alt="" style="  width: 50px;height: 45px;border-radius: 50%;">
                                                                    <?php
                                                                }
                                                                ?>
                                                               
                                                                
                                                        
                                                            </div>
                                                            
                                                        <div class="col-md-4 d-flex align-items-center">
                                                        <?php echo $user['name'];?>
                                                        </div>
                                                </div>
                                                <hr>
                                                <p><?php echo $comment['comment'];?></p>
                                        </div>
                                </div>
                                    <?php
                                }
                                ?>

                        </div>
                </div>
        </div>
</div>
</div>                               

<?php
    require_once 'inc/footer.php';
?>
 