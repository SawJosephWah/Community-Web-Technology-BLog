<?php
    require_once 'inc/header.php';
    $articles = Article::allPosts();

    if(isset($_GET['category'])){
            $articles = Article::articlesByCategory($_GET['category']);
            $pagination_links = '&category='.$_GET['category'];
      
    }elseif(isset($_GET['language'])){
        $articles = Article::articlesByLanguage($_GET['language']);
        $pagination_links = '&language='.$_GET['language'];
        
    }elseif(isset($_GET['my_article'])){
        if(!User::auth()){
                Helper::redirect('login.php');
        }
        $articles = Article::my_article();
        $pagination_links = '&my_article';
        
    }


?>
                                <div class="card card-dark">
                                        <div class="card-body">
                                                  <a href="<?php echo $articles['prev'];if(isset($_GET['category']) || isset($_GET['language'])){echo $pagination_links;}?>" >
                                                  <button class="btn btn-danger text-danger" <?php if(!isset($articles['prev'])){ ?> disabled <?php }?> >Prev Posts</button>
                                       </a>
                                        
                                               
                                       <a href="<?php ;echo $articles['next'];if(isset($_GET['category']) ||  isset($_GET['language'])){echo $pagination_links;}?>" >
                                                  <button class="btn btn-danger text-danger float-right" <?php if(!isset($articles['next'])){ ?> disabled <?php }?> >Next Posts</button>
                                       </a>
                                        </div>
                                </div>
                                <div class="card card-dark">
                                        <div class="card-body">
                                                 <!-- comment for no articles -->
                                                 <?php 
                                                        if(count($articles['data']) == 0){
                                                        ?>
                                                <div class="card-header bg-warning d-flex  align-items-center">
                                                        <h3 class="mr-5">No Article Yet</h3>
                                                        <a href="index.php" class="badge badge-success badge" style="cursor: pointer;">HOME</a>
                                                </div>
                                                        <?php
                                                        }
                                                        ?>
                                                <!-- comment for no articles end-->   

                                                <div class="row">
                                                       
                                                        <!-- Loop this -->
                                                        <?php  
                                                        
                                                        foreach($articles['data'] as $article){
                                                                
                                                                ?>
                                                         <div class="col-md-4 mt-3">
                                                                <div class="card" >
                                                                        <img class="card-img-top"
                                                                                src="assets/article_images/<?php echo $article['image']?>"
                                                                                alt="Card image cap"
                                                                                style="width:100%; height:50%">
                                                                        <div class="card-body">
                                                                                <h5 class="text-dark">
                                                                                        <?php echo $article['title']?>
                                                                                </h5>
                                                                                
                                                                                <p class="text-muted mb-0`">
                                                                                <?php 
                                                                                // $user = DB::raw('select * from users where id='.$article['user_id'])->getOne();
                                                                                $user=DB::table('users')->where('id',$article['user_id'])->getOne();
                                                                                echo $user['name'];
                                                                                ?>
                                                                                </p>
                                                                        </div>
                                                                        <div class="card-footer">
                                                                                <div class="row">
                                                                                        <div
                                                                                                class="col-md-4 text-center">
                                                                                                <i
                                                                                                        class="fas fa-heart text-warning">
                                                                                                </i>
                                                                                                <small class="text-muted">
                                                                                                <?php echo $article['article_like_count'];?>
                                                                                                
                                                                                                </small>
                                                                                        </div>
                                                                                        <div
                                                                                                class="col-md-4 text-center">
                                                                                                <i
                                                                                                        class="far fa-comment text-dark"></i>
                                                                                                <small class="text-muted">
                                                                                                <?php echo $article['article_comment_count'];?>
                                                                                                </small>
                                                                                        </div>
                                                                                        <div
                                                                                                class="col-md-4 text-center">
                                                                                                <a href="details.php?slug=<?php echo $article['slug'];?>" class="badge badge-warning p-1">View</a>
                                                                                        </div>
                                                                                </div>

                                                                        </div>
                                                                </div>
                                                        </div>
                                                                <?php
                                                        }
                                                        ?>
                                                </div>
                                        </div>
                                </div>

<?php
    require_once 'inc/footer.php';
?>
 