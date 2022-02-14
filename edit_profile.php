<?php
    require_once 'inc/header.php';

    if(!User::auth()){
        Helper::redirect('login.php');
    }

    if(isset($_POST['submit'])) {
        $user = new User();
        $user = $user->update_profile($_POST);
        if($user == 'success'){
            Helper::redirect('index.php?success=updated_profile');
        }
    }

?>
                              <div class="card card-dark">
                                        <div class="card-header bg-warning">
                                                <h3>Edit Profile</h3>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            if(isset($user) && is_array($user)){
                                                foreach($user as $error){
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
                                                <label for="" class="text-white">
                                                <?php echo User::auth()['email']?>
                                                </label>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="text-white">Enter Username</label>
                                                    <input type="text" name="name" class="form-control" placeholder="enter username"
                                                    value="<?php echo User::auth()['name']?>">
                                                </div>



                                                <div class="form-group">
                                                                <label for="" class="text-white">Enter Password</label>
                                                                <input type="password" class="form-control"
                                                                name="password" placeholder="enter password">
                                                </div>

                                                <?php 
                                                if(!User::auth()['image']){
                                                ?>
                                                <img src="assets/img/user_demo.png" alt="" src style="width: 200px;height:50%">
                                                <?php
                                                }else{
                                                ?>
                                                <img src="assets/profile_images/<?php echo User::auth()['image'];?>" class="mb-2" alt="" src style="width: 200px;height:50%">
                                                <?php
                                                }
                                                ?>
                                               

                                                <div class="form-group">
                                                <label for="" class="text-white">Choose Profile Picture</label>
                                                <input type="file" class="form-control"
                                                                name="profile_img" >
                                                </div>

                                                <input type="submit" name="submit"value="Update Profile"
                                                                class="btn  btn-outline-warning">
                                                </form>
                                        </div>
                                </div>

<?php
    require_once 'inc/footer.php';
?>
 