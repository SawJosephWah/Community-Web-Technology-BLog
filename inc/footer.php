</div>

</div>



</div>

</body>

</html>







<!-- Optional JavaScript --> 

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js"
integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U"
crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js"
integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9"
crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.25.0/axios.min.js"></script>
<script>$(document).ready(function () {
    //for article create success message
    const success =  new URLSearchParams(window.location.search).get('success');
 
    if(success == 'article_create'){
        toastr.success('Article Created Successfully!');
    }else if(success == 'updated_profile'){
        toastr.success('Profile Updated Successfully!');
    }else if(success =='deleted_article'){
        toastr.success('Deleted Successfully!');
    }else if(success == 'updated_article'){
        toastr.success('Updated Article Successfully!');
    }

    
  
   //for like action
    let like_action = document.getElementById("like_btn");
    
    if(like_action){
        like_action.addEventListener("click", function(){
        
        const like_data = document.getElementById('like_btn');
        let user_id = like_data.getAttribute('user_id');
        let article_id = like_data.getAttribute('article_id');
        axios.get(`api.php?like&user_id=${user_id}&article_id=${article_id}`)
        .then(function (response) {
            let result = JSON.parse(JSON.stringify(response)).data;
            document.getElementById("like_count_status").innerHTML = result.like_count;
            toastr.success(result.like_status, 'Like Status');

            comment_send_icon
        });
        
    });
    }
    
    

    //for comment action
    let comment_action = document.getElementById("comment_send_icon");
    
    if(comment_action){
        comment_action.addEventListener("click", function(){
      
      const comment_data = document.getElementById('comment_box');
      let user_id = comment_data.getAttribute('user_id');
      let article_id = comment_data.getAttribute('article_id');
      let comment = comment_data.value.replace(/^[ ]+/g, "");
      if(comment){
          axios.post('api.php?comment', {
          user_id,
          article_id,
          comment,
      })
      .then(function (response) {
   
          let result = JSON.parse(JSON.stringify(response)).data;
          if(result.status){
                location.reload();
             
          }
      })
      }else{
          toastr.error('Comment something');
      }
      
      
      
  });
    }

    //delete article
    // delete_btn
    let delete_action = document.getElementById("delete_btn");
    

    if(delete_action){

         delete_action.addEventListener("click", function(){

        if(confirm('Do you want to delete?')){
        const delete_data = document.getElementById('delete_btn');
        let article_slug = delete_data.getAttribute('slug');
 

        axios.get(`api.php?delete_article_slug=${article_slug}`)
        .then(function (response) {
            let result = JSON.parse(JSON.stringify(response)).data;
            if(result.status){

                window.location.href = "index.php?success=deleted_article";
            }else{
                toastr.error('Something wrong!');
            }
       
            
        });
        };
      
     
      
  });
    }


});</script>
</body>

</html>