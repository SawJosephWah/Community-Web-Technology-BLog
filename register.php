<?php
    require_once 'inc/header.php';

    if(User::auth()){
        Helper::redirect('index.php');
    }

    if(isset($_POST['submit'])) {
        $user = new User();
        $new_user = $user->register($_POST);
        if(!is_array($new_user)){
            Helper::redirect('index.php');
        }
    }

?>
                              <div class="card card-dark">
                                        <div class="card-header bg-warning">
                                                <h3>Register</h3>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            if(isset($new_user) && is_array($new_user)){
                                                foreach($new_user as $error){
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
                                                                <label for="" class="text-white">Enter Username</label>
                                                                <input type="text" name="name" class="form-control"
                                                                        placeholder="enter username">
                                                        </div>

                                                        <div class="form-group">
                                                                <label for="" class="text-white">Enter Email</label>
                                                                <input type="email" class="form-control"
                                                                name="email"   placeholder="enter email">
                                                        </div>

                                                        <div class="form-group">
                                                                <label for="" class="text-white">Enter Password</label>
                                                                <input type="password" class="form-control"
                                                                name="password" placeholder="enter password">
                                                        </div>
                                                        <input type="submit" name="submit"value="Register"
                                                                class="btn  btn-outline-warning">
                                                </form>
                                        </div>
                                </div>

<?php
    require_once 'inc/footer.php';
?>
 