<?php
    require_once 'inc/header.php';
    if(User::auth()){
        Helper::redirect('index.php');
    }

    if(isset($_POST['submit'])) {
        $user = new User();
        $auth_user = $user->login($_POST);
    
        if(!is_array($auth_user)){
                Helper::redirect('index.php');
            }
    }
?>
                             <div class="card card-dark">
                                        <div class="card-header bg-warning">
                                                <h3>Login</h3>
                                        </div>
                                        <div class="card-body">
                                        <?php
                                            if(isset($auth_user) && is_array($auth_user)){
                                                foreach($auth_user as $error){
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
                                                <form action="" method="post">
                                                        <div class="form-group">
                                                                <label for="" class="text-white">Enter Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                        placeholder="enter username">
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="" class="text-white">Enter Password</label>
                                                                <input type="password" class="form-control"
                                                                        name="password" placeholder="enter Password">
                                                        </div>
                                                        <input type="submit" value="Login" name="submit"
                                                                class="btn  btn-outline-warning">
                                                </form>
                                        </div>
                                </div>

<?php
    require_once 'inc/footer.php';
?>
 