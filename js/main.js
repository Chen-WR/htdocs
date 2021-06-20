// Toggle new post textarea 
function newpostToggle() {
    var newPost = document.getElementById("new-post");
    if (newPost.style.display === "none") {
      newPost.style.display = "block";
    } 
    else{
      newPost.style.display = "none";
    }
  }