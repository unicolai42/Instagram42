function profil_photo() {
    document.getElementById('photo_profil_sub').submit();
}

function put_profil_picture_in_db(img) {
    var request = new XMLHttpRequest();
    request.open('POST', 'check_profil_picture.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('img=' + img);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
            ;
    }
}

function profil_picture_click(e) {
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        new_profil_picture = e.target.files[0];
        var reader = new FileReader();
        if (!new_profil_picture)
            return;
        if (new_profil_picture.type != 'image/jpeg' && new_profil_picture.type != 'image/jpg' && new_profil_picture.type != 'image/png')
            return;
        reader.onload = function(new_elem) {
            var label_profil_picture = document.getElementById('label_profil_picture');
                var label_profil_picture_img = label_profil_picture.childNodes[0];
            
            label_profil_picture_img.src = new_elem.target.result;

            var img = new Image();
            img.onload = function(){
                var height = img.height;
                var width = img.width;

                if (width > height)
                    label_profil_picture_img.setAttribute('id', 'label_profil_picture_width_img');
                else if (width < height)
                    label_profil_picture_img.setAttribute('id', 'label_profil_picture_height_img');
                else
                    label_profil_picture_img.setAttribute('id', 'label_profil_picture_img');
            }
            img.src = new_elem.target.result;

            put_profil_picture_in_db(new_elem.target.result); 
        }
        reader.readAsDataURL(new_profil_picture);
    }
    else {
        alert('The File APIs are not fully supported in this browser.');
    }
}

function getAllUrlParams(url) {

    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
  
    // we'll store the parameters here
    var obj = {};
  
    // if query string exists
    if (queryString) {
  
      // stuff after # is not part of query string, so get rid of it
      queryString = queryString.split('#')[0];
  
      // split our query string into its component parts
      var arr = queryString.split('&');
  
      for (var i=0; i<arr.length; i++) {
        // separate the keys and the values
        var a = arr[i].split('=');
  
        // in case params look like: list[]=thing1&list[]=thing2
        var paramNum = undefined;
        var paramName = a[0].replace(/\[\d*\]/, function(v) {
          paramNum = v.slice(1,-1);
          return '';
        });
  
        // set parameter value (use 'true' if empty)
        var paramValue = typeof(a[1])==='undefined' ? true : a[1];
  
        // (optional) keep case consistent
        paramName = paramName.toLowerCase();
        paramValue = paramValue.toLowerCase();
  
        // if parameter name already exists
        if (obj[paramName]) {
          // convert value to array (if still string)
          if (typeof obj[paramName] === 'string') {
            obj[paramName] = [obj[paramName]];
          }
          // if no array index number specified...
          if (typeof paramNum === 'undefined') {
            // put the value on the end of the array
            obj[paramName].push(paramValue);
          }
          // if array index number specified...
          else {
            // put the value at that index number
            obj[paramName][paramNum] = paramValue;
          }
        }
        // if param name doesn't exist yet, set it
        else {
          obj[paramName] = paramValue;
        }
      }
    }
  
    return obj;
  }

function hover_box() {
    var request = new XMLHttpRequest();
    var url = window.location.href;
    var user_id = getAllUrlParams(url).user_id;
    request.open('POST', 'select_nb_likes_and_comments_posts.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send('user_id=' + user_id);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            var nb_comments_posts = JSON.parse(this.responseText)['comments'];
            var nb_likes_posts = JSON.parse(this.responseText)['likes'];
            const box = document.getElementsByClassName('box');
            console.log(nb_comments_posts);

            Array.from(box).forEach(function(element) {
                element.addEventListener('mouseenter', function(e) {
                    if (document.getElementsByClassName('frame_display_likes_and_comments')[0])
                        document.getElementsByClassName('frame_display_likes_and_comments')[0].remove();
                    var box_selected = e.target;
                    while(box_selected.className != 'box')
                        box_selected = box_selected.parentElement;
                    
                    var post_id = box_selected.dataset.id;

                    var frame_display_likes_and_comments = document.createElement('div');
                    frame_display_likes_and_comments.setAttribute('class', 'frame_display_likes_and_comments');
                    box_selected.appendChild(frame_display_likes_and_comments);

                        var display_likes_and_comments = document.createElement('div');
                        display_likes_and_comments.setAttribute('class', 'display_likes_and_comments');
                        frame_display_likes_and_comments.appendChild(display_likes_and_comments);

                            var likes_box = document.createElement('div');
                            likes_box.setAttribute('class', 'likes_box');
                            display_likes_and_comments.appendChild(likes_box);

                                var nb_likes = document.createElement('class');
                                nb_likes.setAttribute('class', 'nb_likes');
                                nb_likes.textContent = nb_likes_posts[post_id];
                                likes_box.appendChild(nb_likes);

                                var img_likes = document.createElement('img');
                                img_likes.src = 'ressources/like_white.png';
                                likes_box.appendChild(img_likes);

                            var comments_box = document.createElement('div');
                            comments_box.setAttribute('class', 'comments_box');
                            display_likes_and_comments.appendChild(comments_box);

                                var nb_comments = document.createElement('class');
                                nb_comments.setAttribute('class', 'nb_comments');
                                nb_comments.textContent = nb_comments_posts[post_id];
                                comments_box.appendChild(nb_comments);

                                var img_comments = document.createElement('img');
                                img_comments.src = 'ressources/comm_white.png';
                                comments_box.appendChild(img_comments);

                    box_selected.addEventListener('mouseleave', function() {
                            frame_display_likes_and_comments.remove();
                    });
                });
            });
        }
    }
}


if (location.pathname == '/profil.php') {
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementsByClassName('box'))
            hover_box();

        var label_profil_picture = document.getElementById('label_profil_picture');
        if (label_profil_picture) {
                var label_profil_picture_img = document.getElementById('label_profil_picture').childNodes[0];

            if (label_profil_picture_img.naturalWidth > label_profil_picture_img.naturalHeight)
                label_profil_picture_img.setAttribute('id', 'label_profil_picture_width_img');
            else if (label_profil_picture_img.naturalWidth < label_profil_picture_img.naturalHeight)
                label_profil_picture_img.setAttribute('id', 'label_profil_picture_height_img');

            const profil_picture = document.getElementById('profil_picture');
            profil_picture.addEventListener('click', function(e) {
                const label_profil_picture = document.getElementById('label_profil_picture');
                label_profil_picture.click();
                profil_picture.addEventListener('change', function(e) {
                    profil_picture_click(e);
                });
            });

            const input_profil_picture = document.getElementById('input_profil_picture');
            label_profil_picture.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        var profil_picture_others = document.getElementById('profil_picture_others');
        if (profil_picture_others) {
                var profil_picture_others_img = document.getElementById('profil_picture_others').childNodes[0];
                console.log(profil_picture_others_img);

            if (profil_picture_others_img.naturalWidth > profil_picture_others_img.naturalHeight)
                profil_picture_others_img.setAttribute('id', 'profil_picture_others_width_img');
            else if (profil_picture_others_img.naturalWidth < profil_picture_others_img.naturalHeight)
                profil_picture_others_img.setAttribute('id', 'profil_picture_others_height_img');
        }
    });
}

function upload() {
    document.getElementById('submit_form').submit();
}

function upload_retry() {
    document.getElementById('retry').submit();
}

function delete_elem() {
    document.getElementById("nav").remove();
}

function delete_post_index(elem) {
    // console.log(elem.parentElement.parentElement.dataset.id);
    console.log(elem.parentElement.parentElement.dataset.id);
    var request = new XMLHttpRequest();
    request.open('POST', 'delete_post.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('post_id=' + elem.parentElement.parentElement.dataset.id);
    request.onreadystatechange = function() {
        elem.parentElement.parentElement.remove();
    }
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function like_or_dislike_post(elem) {
    
    if (!getCookie('username'))
    {
        document.location.href="connexion.php";
        return ;
    }
    else
    {
        if (location.pathname == '/profil.php')
            var i = 1;
        else
            var i = 0;
        if (elem.childNodes[i].className == 'like')
        {
            var request = new XMLHttpRequest();
            request.open('POST', 'like_post.php', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            var post = elem;
            while (!post.dataset.id)
                post = post.parentElement;
            post_id = post.dataset.id;

            request.send('post_id=' + post_id);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200)
                {
                    elem.childNodes[i].src = "ressources/like_red.png";
                    elem.childNodes[i].className = "like_red";
                    if (location.pathname == '/profil.php')
                    {
                        var one_like_more = parseInt(elem.childNodes[3].textContent) + 1;
                        elem.childNodes[3].textContent = one_like_more;
                        return;
                    }
                    if (!elem.parentElement.parentElement.childNodes[0].childNodes[0] || elem.parentElement.parentElement.childNodes[0].childNodes[0].className == 'comm_chiffre')
                    {
                        var div_like_chiffre = document.createElement("div");
                        var div_chiffre = elem.parentElement.parentElement.childNodes[0];
                        // console.log(div_chiffre.childNodes);
                        if (!div_chiffre.childNodes[0])
                            child = undefined;
                        else if (div_chiffre.childNodes[0].className == 'comm_chiffre')
                            child = div_chiffre.childNodes[0];
                        // console.log(child);
                        div_chiffre.insertBefore(div_like_chiffre, child);
                        div_chiffre.childNodes[0].setAttribute('class', 'like_chiffre');
                        // console.log(div_chiffre.childNodes);
                        div_chiffre.childNodes[0].textContent = "1 likes";
                    }
                    else if (elem.parentElement.parentElement.childNodes[0].childNodes[0].className == 'like_chiffre')
                    {
                        var number_likes = elem.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0].textContent;
                        var new_number_likes = parseInt(number_likes) + 1;
                        elem.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0].textContent = new_number_likes;
                    }
                    var legend = elem;
                        while (legend.className != 'legend')
                            legend = legend.parentElement;
                        var liked_by = legend.childNodes[2];
                        console.log(liked_by.childNodes[1]);

                        var new_like_user = document.createElement('a');
                        new_like_user.setAttribute('class', 'liked_by');
                        new_like_user.setAttribute('href', 'profil.php?user_id=' + getCookie('user_id'));
                        new_like_user.textContent = '@' + getCookie('username');

                        if (!liked_by.childNodes.length) {
                            liked_by.textContent = 'Liked by ';
                            liked_by.appendChild(new_like_user);
                            liked_by.textContent += '.';
                        }
                        else {
                            var comma = document.createTextNode(', ');
                            console.log(liked_by.childNodes[1]);
                            liked_by.insertBefore(comma, liked_by.childNodes[1]);
                            liked_by.insertBefore(new_like_user, comma);
                        }
                        console.log(liked_by.childNodes.length);

                        liked_by.style.display = 'block';
                }
        
            }
        }    
        else if (elem.childNodes[i].className == 'like_red')
        {
            var request = new XMLHttpRequest();
            request.open('POST', 'dislike_post.php', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            var post = elem;
            while (!post.dataset.id)
                post = post.parentElement;
            post_id = post.dataset.id;

            request.send('post_id=' + post_id);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200)
                {
                    elem.childNodes[i].src="ressources/like.png";
                    elem.childNodes[i].className = "like";
                    if (location.pathname == '/profil.php')
                    {
                        var one_like_less = parseInt(elem.childNodes[3].textContent) - 1;
                        elem.childNodes[3].textContent = one_like_less;
                        return;
                    }
                    var number_likes = elem.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0].textContent;
                    var new_number_likes = parseInt(number_likes) - 1;
                    if (new_number_likes == 0)
                        elem.parentElement.parentElement.childNodes[0].childNodes[0].remove();
                    else
                        elem.parentElement.parentElement.childNodes[0].childNodes[0].childNodes[0].textContent = new_number_likes;
                    
                    var legend = elem;
                    while (legend.className != 'legend')
                        legend = legend.parentElement;
                    var liked_by = legend.childNodes[2];

                    Array.from(liked_by.childNodes).forEach(function(elem) {
                        if (elem.textContent == '@' + getCookie('username')) {
                            elem.nextSibling.remove();
                            elem.remove();
                            return;
                        }
                        console.log(elem.textContent);
                    });
                    liked_by.style.display = 'none';
                 }
            }
        }
    }
}

function delete_comment(elem){
    var request = new XMLHttpRequest();
    request.open('POST', 'delete_comment.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    console.log(elem.parentElement.dataset.id);
    request.send('comment_id=' + elem.parentElement.dataset.id);
    console.log(elem.parentElement.dataset.id);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            elem.parentElement.remove();
            var posts = document.getElementsByClassName('post');
            var post_id = JSON.parse(this.responseText)['post_id'];
            var nb_user_comments = JSON.parse(this.responseText)['user_comments'];
            // console.log(document.getElementsByClassName('comm_color_img'));
            var i = -1;
            while (++i < posts.length)
                if (posts[i].dataset.id == post_id)
                    post = posts[i];
            if (location.pathname == '/profil.php')
            {
                document.getElementsByClassName('number_comments')[0].textContent -= 1;
                console.log(nb_user_comments);
                if (nb_user_comments == 0 && document.getElementsByClassName('comm_color_img')[0])
                {
                    document.getElementsByClassName('comm_color_img')[0].src = 'ressources/comm.png';
                    document.getElementsByClassName('comm_color_img')[0].className = 'comm_img';
                }

                return;
            }
            console.log('hjk');

            var like_comment = post.childNodes[2].childNodes[0];
            if (like_comment.childNodes[0].childNodes[0].className == 'comm_chiffre')
                var number_comments = parseInt(like_comment.childNodes[0].childNodes[0].childNodes[0].textContent);
            else
                var number_comments = parseInt(like_comment.childNodes[0].childNodes[1].childNodes[0].textContent);
            if (number_comments == 1)
            {
                if (like_comment.childNodes[0].childNodes[0].className == 'comm_chiffre')
                    var comm_chiffre = like_comment.childNodes[0].childNodes[0];
                else
                    var comm_chiffre = like_comment.childNodes[0].childNodes[1];
                comm_chiffre.remove();
                // console.log(like_comment);
            }
            else if (number_comments > 1)
            {
                number_comments -= 1;
                if (like_comment.childNodes[0].childNodes[0].className == 'comm_chiffre')
                    like_comment.childNodes[0].childNodes[0].childNodes[0].textContent = number_comments;
                else
                    like_comment.childNodes[0].childNodes[1].childNodes[0].textContent = number_comments;
            }
            if (nb_user_comments == 0)
            {
                var comm_icon_img =  like_comment.childNodes[1].childNodes[1].childNodes[0];
                comm_icon_img.src = 'ressources/comm.png';
                console.log(comm_icon_img);
            }
        }
    }
}

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
      if ((new Date().getTime() - start) > milliseconds){
        break;
      }
    }
}

document.addEventListener('DOMContentLoaded', submit_comment);

function submit_comment() {
    var posts = document.getElementsByClassName('comment_answer');
    Array.from(posts).forEach(function(element) {
        element.addEventListener('keypress', new_comment);
    });
}

function create_new_comment_from_scratch(e, dataset_id, all_comments) {
        var comments = document.createElement("div");
        all_comments.appendChild(comments);
        comments.setAttribute('class', 'comments');
        comments.dataset.id = dataset_id;
            var comment_content = document.createElement("div");
            comments.appendChild(comment_content);
            comment_content.setAttribute('class', 'comment_content');
                var user = document.createElement("span");
                comment_content.appendChild(user);
                user.setAttribute('class', 'user');
                var text = document.createElement("span");
                comment_content.appendChild(text);
                text.setAttribute('class', 'text');
            var delete_comment = document.createElement("div");
            comments.appendChild(delete_comment);
            delete_comment.setAttribute('onclick', 'delete_comment(this)');
            delete_comment.setAttribute('class', 'delete_comment');
                var img = document.createElement('img');
                delete_comment.appendChild(img);
                img.src = 'ressources/delete.png';
}

function display_new_comment(e, dataset_id) {
    var post = e.target;
    if (location.pathname == '/profil.php') {
        while (!post.id || post.id != 'box_display')
            post = post.parentElement;
        
        var all_comments = document.getElementById('all_comments');
        var nb_comments = document.getElementsByClassName('comments').length;
        console.log(nb_comments);
        console.log(all_comments);
    }
    else {
        while (!post.className || post.className != 'post')
            post = post.parentElement;

        var all_comments = post.childNodes[2].childNodes[3];
        var nb_comments = post.childNodes[2].childNodes[3].childNodes.length;
    }

    if (nb_comments == 0)
    {
        create_new_comment_from_scratch(e, dataset_id, all_comments);
        new_comment = all_comments.childNodes[0];
    }
    else
    {
        new_comment = all_comments.childNodes[0].cloneNode(true);
        var first_comment = all_comments.childNodes[0];

        all_comments.insertBefore(new_comment, first_comment);
        new_comment.dataset.id = dataset_id;
        
        if (!first_comment.childNodes[1])
        {
            var delete_comment = document.createElement("div");
            new_comment.appendChild(delete_comment);
            delete_comment.setAttribute('onclick', 'delete_comment(this)');
            delete_comment.setAttribute('class', 'delete_comment');
                var img = document.createElement('img');
                delete_comment.appendChild(img);
                img.src = 'ressources/delete.png';
        }
    }
    if (location.pathname != '/profil.php') {
        var legend = e.path[2];
        var liked_by = legend.childNodes[2];
        var all_comments = legend.childNodes[3];

        if (all_comments.parentElement.childNodes[2].childNodes.length > 0)
            liked_by.style.display = 'block';
        
        all_comments.style.display = 'block';
        if (all_comments.childNodes.length > 7) {
            all_comments.style.height = '20vh';
            all_comments.style.overflow = 'auto';
        }
    }
    new_comment.childNodes[0].childNodes[0].textContent = getCookie('username');
    new_comment.childNodes[0].childNodes[1].textContent = e.target.value;
}

function new_comment(e) {
    console.log(e);
    var key = e.which || e.keyCode;
    if (key === 13) {
        // console.log(location.pathname);
        e.preventDefault();
        if (e.target.value == '')
        {
            e.target.placeholder = 'Vous ne pouvez soumettre un commentaire vide...'
            return;
        }
        else if (!getCookie('username'))
        {
            document.location.href="connexion.php";
            e.target.value = '';
            return;
        }
        var request = new XMLHttpRequest();
        request.open("POST", "check_comment.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send('comment=' + e.target.value + '&post_id=' + e.path[1][1].value);
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200)
            {
                var dataset_id = JSON.parse(this.responseText)['dataset_id'];
                // console.log(dataset_id);
                var nb_comments_by_user = JSON.parse(this.responseText)['nb_comments_by_user'];

                if (location.pathname == '/profil.php')
                {
                    var img_comm_display = document.getElementsByClassName('comm_display')[0].childNodes[1];
                    console.log(img_comm_display);
                    if (img_comm_display.className == 'comm_img')
                    {
                        img_comm_display.className = 'comm_color_img';
                        img_comm_display.src = 'ressources/comm_color.png';
                    }
                    var nb_comments = document.getElementsByClassName('number_comments')[0].textContent;
                    var new_nb_comments = parseInt(nb_comments) + 1;
                    document.getElementsByClassName('number_comments')[0].textContent = new_nb_comments; 
                    display_new_comment(e, dataset_id);
                }
                else if (!e.path[2].childNodes[0].childNodes[0].childNodes[0])
                {
                    // console.log(e.path[2].childNodes[0].childNodes[0]);
                    var div_comm_chiffre = document.createElement("div");
                    var div_chiffre = e.path[2].childNodes[0].childNodes[0];
                    div_chiffre.appendChild(div_comm_chiffre);
                    div_chiffre.childNodes[0].setAttribute('class', 'comm_chiffre');
                    var div_comm_number = document.createElement("div");
                    div_comm_chiffre.appendChild(div_comm_number);
                    div_comm_chiffre.childNodes[0].setAttribute('class', 'number_comments');
                    div_comm_number.textContent = "1";
                    var div_comm_text = document.createElement("div");
                    div_comm_chiffre.appendChild(div_comm_text);
                    div_comm_chiffre.childNodes[1].setAttribute('class', 'text_comments');
                    div_comm_text.textContent = "comments";
                    e.path[2].childNodes[0].childNodes[1].childNodes[1].childNodes[0].src = "ressources/comm_color.png";
                    display_new_comment(e, dataset_id);
                }
                else if (e.path[2].childNodes[0].childNodes[0].childNodes[0].className == 'comm_chiffre')
                {
                    var number_comments = e.path[2].childNodes[0].childNodes[0].childNodes[0].childNodes[0].textContent;
                    var new_number_comments = parseInt(number_comments) + 1;
                    e.path[2].childNodes[0].childNodes[0].childNodes[0].childNodes[0].textContent = new_number_comments;
                    if (nb_comments_by_user == 1)
                        e.path[2].childNodes[0].childNodes[1].childNodes[1].childNodes[0].src = "ressources/comm_color.png";
                    display_new_comment(e, dataset_id);
                }
                else if (!e.path[2].childNodes[0].childNodes[0].childNodes[1])
                {
                    var div_comm_chiffre = document.createElement("div");
                    var div_chiffre = e.path[2].childNodes[0].childNodes[0];
                    div_chiffre.appendChild(div_comm_chiffre);
                    div_chiffre.childNodes[1].setAttribute('class', 'comm_chiffre');
                    var div_comm_number = document.createElement("div");
                    div_comm_chiffre.appendChild(div_comm_number);
                    div_comm_chiffre.childNodes[0].setAttribute('class', 'number_comments');
                    div_comm_number.textContent = "1";
                    var div_comm_text = document.createElement("div");
                    div_comm_chiffre.appendChild(div_comm_text);
                    div_comm_chiffre.childNodes[1].setAttribute('class', 'text_comments');
                    div_comm_text.textContent = "comments";
                    e.path[2].childNodes[0].childNodes[1].childNodes[1].childNodes[0].src = "ressources/comm_color.png";
                    display_new_comment(e, dataset_id);
                }
                else if (e.path[2].childNodes[0].childNodes[0].childNodes[1].className == 'comm_chiffre')
                {
                    var number_comments = e.path[2].childNodes[0].childNodes[0].childNodes[1].childNodes[0].textContent;
                    var new_number_comments = parseInt(number_comments) + 1;
                    e.path[2].childNodes[0].childNodes[0].childNodes[1].childNodes[0].textContent = new_number_comments;
                    if (nb_comments_by_user == 1)
                    e.path[2].childNodes[0].childNodes[1].childNodes[1].childNodes[0].src = "ressources/comm_color.png";
                    display_new_comment(e, dataset_id);
                }
                e.target.value = '';
            }
        }       
    }
}

document.addEventListener('DOMContentLoaded', display_comm);

function display_comm() {
    // console.log(document.getElementsByClassName('comm_icon'));    
    var comm_icon = document.getElementsByClassName('comm_icon');
    Array.from(comm_icon).forEach(function(element) {
        element.addEventListener('click', function(e) {
            var legend = e.path[0];
            while (legend.className != 'legend')
                legend = legend.parentElement;

            var liked_by = legend.childNodes[2];
            var all_comments = legend.childNodes[3];

            if (all_comments.style.display == 'none' || !all_comments.style.display) {
                if (all_comments.childNodes.length > 0) {
                    all_comments.style.display = 'block';
                    if (all_comments.childNodes.length > 7) {
                        all_comments.style.height = '20vh';
                        all_comments.style.overflow = 'auto';
                    }
                }
                if (all_comments.parentElement.childNodes[2].childNodes.length > 0)
                    liked_by.style.display = 'block';
            }
            else if (all_comments.style.display == 'block') {
                all_comments.style.display = 'none';
                all_comments.style.height = '';
                all_comments.style.overflow = '';
                liked_by.style.display = 'none';
            }
                // ca marche pas quand on post un new comment. il se rajoute pas a la liste A FAIRE CA
        });
    });

    var chiffre = document.getElementsByClassName('chiffre');
    Array.from(chiffre).forEach(function(element) {
        element.addEventListener('click', function(e) {
            var legend = e.path[0];
            while (legend.className != 'legend')
                legend = legend.parentElement;

            var liked_by = legend.childNodes[2];
            var all_comments = legend.childNodes[3];

            if (all_comments.style.display == 'none' || !all_comments.style.display) {
                if (all_comments.childNodes.length > 0) {
                    all_comments.style.display = 'block';
                    if (all_comments.childNodes.length > 7) {
                        all_comments.style.height = '20vh';
                        all_comments.style.overflow = 'auto';
                    }
                }
                if (all_comments.parentElement.childNodes[2].childNodes.length > 0)
                    liked_by.style.display = 'block';
            }
            else if (all_comments.style.display == 'block') {
                all_comments.style.display = 'none';
                all_comments.style.height = '';
                all_comments.style.overflow = '';
                liked_by.style.display = 'none';
            }
                // ca marche pas quand on post un new comment. il se rajoute pas a la liste A FAIRE CA
        });
    });
}

function start_and_stop_follow(e, following_id) {
    if (!getCookie('user_id'))
    {
        document.location.href="connexion.php";
        return ;
    }
    var request = new XMLHttpRequest();
    request.open('POST', 'start_and_stop_follow.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send('follower_id=' + getCookie('user_id') + '&following_id=' + following_id);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            if (e.textContent == 'following')
            {
                e.textContent = 'follow';
                one_less_follower = parseInt(e.parentElement.parentElement.childNodes[3].childNodes[3].childNodes[0].textContent) - 1;
                e.parentElement.parentElement.childNodes[3].childNodes[3].childNodes[0].textContent = one_less_follower;
            }
            else if (e.textContent == 'follow')
            {
                e.textContent = 'following';
                one_more_follower = parseInt(e.parentElement.parentElement.childNodes[3].childNodes[3].childNodes[0].textContent) + 1;
                e.parentElement.parentElement.childNodes[3].childNodes[3].childNodes[0].textContent = one_more_follower;
            }
        }
    }
}

function putContentInBox(user_id, username)
{
    var request = new XMLHttpRequest();
    request.open('POST', 'recover_img.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send('user_id=' + user_id);
    request.onreadystatechange = function ()
    {
        if (request.readyState == 4 && request.status == 200)
        {
            var img_content = JSON.parse(this.responseText)['img_user'];
            var box_users = document.getElementById('box_users');
            var user_block = document.createElement('a');
            box_users.appendChild(user_block);
            user_block.setAttribute('href', 'profil.php?user_id=' + user_id);
            user_block.setAttribute('class', 'user_block');
                var box_child_img = document.createElement('img');
                user_block.appendChild(box_child_img);
                box_child_img.setAttribute('class', 'box_child_img');
                    box_child_img.src = 'data:image;base64,' + img_content;
                var user_name = document.createElement('div');
                user_block.appendChild(user_name);
                user_name.setAttribute('class', 'user_name');
                    user_name.textContent = username;
        }
    }
}

document.addEventListener('DOMContentLoaded', search);

function search() {
    var request = new XMLHttpRequest();
    request.open('POST', 'recover_users.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            var users = JSON.parse(this.responseText)['users'];
            document.getElementById('search_bar').addEventListener('click', function(e) {
                if (document.getElementById("box_users"))
                {
                    box_users = document.getElementById("box_users");
                    box_users.remove();
                }
            });
            document.getElementById('search_bar').addEventListener('keyup', function(e) {
                var key = e.which || e.keyCode;
                if (document.getElementById("box_users"))
                {
                    box_users = document.getElementById("box_users");
                    box_users.remove();
                }
                var search_input = document.getElementById('search_bar').value;
                var result = [];
                Array.from(users).forEach(function(element) {
                    var i = 0;
                    while (i < search_input.length)
                    {
                        if (i >= element[1].length)
                            return ;
                        else if (search_input[i].toLowerCase() != element[1][i].toLowerCase())
                            return ;
                        i++;
                    }
                    if (search_input.length == 0)
                        return ;
                    result.push(element[1]);
                });
                if (result.length > 0)
                {
                    var search_box = e.path[2];
                    var box_users = document.createElement('div');
                    search_box.appendChild(box_users);
                    box_users.setAttribute('id', 'box_users');
                    box_users = document.getElementById("box_users");
                    while (box_users.firstChild) {
                        box_users.removeChild(box_users.firstChild);
                    }
                    Array.from(result).forEach(function(element) {
                            i = 0;
                            while (i < users.length)
                            {
                                if (users[i][1] == element)
                                    var user_id = users[i][0];
                                i++;
                            }
                            putContentInBox(user_id, element);
                    }); 
                }
            });
        }
    }
}

function push_friends(friends_post, users) {
    var array_friends = friends_post.split(' ');
    console.log(friends_post);
    var push_friends = 'with ';
    Array.from(array_friends).forEach(function(friend) {
        Array.from(users).forEach(function(user){
            if (friend == user[1])
            {
                if (push_friends == 'with ')
                    push_friends += '<a href="profil.php?user_id=' + user[0] + '">@' + user[1] + '</a>';
                else
                    push_friends += ', <a href="profil.php?user_id=' + user[0] + '">@' + user[1] + '</a>'; 
            }
        });
    });
    // console.log(push_friends);
    document.getElementById('friends_display').innerHTML = push_friends;
}

// function find_username_with_user_id(user_id, users) {
//     var i = -1;
//     while (++i < users.length)
//         if (users[i][0] == user_id)
//             return users[i][1];
// }

function find_username_with_user_id(user_id, users) {
    var result;
    Array.from(users).forEach(function(user) {
        if (user[0] == user_id)
            result = user[1]; 
    });
    return result;
}

function push_comments(comments_post, users) {
    var all_comments = document.getElementById('all_comments');
    Array.from(comments_post).forEach(function(comment) {
        // console.log(comment);
        var comments = document.createElement('div');
        all_comments.appendChild(comments);
        comments.setAttribute('class', 'comments');
        comments.dataset.id = comment[0];
            var comment_content = document.createElement('div');
            comments.appendChild(comment_content);
            comment_content.setAttribute('class', 'comment_content');
                var user = document.createElement('span');
                comment_content.appendChild(user);
                user.setAttribute('class', 'user');
                user.textContent = find_username_with_user_id(comment[2], users);
                var text = document.createElement('span');
                comment_content.appendChild(text);
                text.setAttribute('class', 'text');
                text.textContent = comment[3];
            var delete_comment = document.createElement('div');
            comments.appendChild(delete_comment);
            if (getCookie('user_id') == comment[2])
            {
                delete_comment.setAttribute('class', 'delete_comment');
                delete_comment.setAttribute('onclick', 'delete_comment(this)');
                    delete_img = document.createElement('img');
                    delete_comment.appendChild(delete_img);
                    delete_img.src = 'ressources/delete.png';
            }
    });
}

function push_liked(liked_post)
{
    if (!liked_post)
    {
        like_display = document.getElementsByClassName('like_display')[0];
        like_display.childNodes[1].setAttribute('class', 'like');
        document.getElementsByClassName('like')[0].src = 'ressources/like.png';
    }
    else
    {
        like_display = document.getElementsByClassName('like_display')[0];
        console.log(like_display);
        like_display.childNodes[1].setAttribute('class', 'like_red');
        document.getElementsByClassName('like_red')[0].src = 'ressources/like_red.png';
    }
}

function push_commented(commented_post)
{
    if (!commented_post)
    {
        comm_display = document.getElementsByClassName('comm_display')[0];
        comm_display.childNodes[1].setAttribute('class', 'comm_img');
        document.getElementsByClassName('comm_img')[0].src = 'ressources/comm.png';
    }
    else
    {
        comm_display = document.getElementsByClassName('comm_display')[0];
        console.log(comm_display.childNodes);
        comm_display.childNodes[1].setAttribute('class', 'comm_color_img');
        document.getElementsByClassName('comm_color_img')[0].src = 'ressources/comm_color.png';
    }
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    var items = location.search.substr(1).split("&");
    for (var index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    }
    return result;
}

function delete_post_and_close_box(post_id) {
    var request = new XMLHttpRequest();
    request.open('POST', 'delete_post.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send('post_id=' + post_id);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            document.location.href="profil.php?user_id=" + findGetParameter('user_id');
        }
    }

}

function delete_post_display_click(post_id) {
    var delete_post_display = document.getElementById('delete_post_display');
        var img_delete_post_display = document.createElement('img');
    
    delete_post_display.appendChild(img_delete_post_display);
    img_delete_post_display.src = 'ressources/delete.png';

    img_delete_post_display.addEventListener('click', function () {
        delete_post_and_close_box(post_id);
    });
}

if (location.pathname == '/profil.php')
{
    document.addEventListener('DOMContentLoaded', display_box);

    function display_box() {
        document.getElementById('black_opacity').addEventListener('click', function() {
            box_display_id = document.getElementById('box_display').dataset.id;
            if (document.getElementById('all_comments')) {
                var box_middle = document.getElementById('box_middle');
                document.getElementById('all_comments').remove();
                var new_all_comments = document.createElement('div');
                new_all_comments.setAttribute('id', 'all_comments');
                box_middle.appendChild(new_all_comments);
            }
            hover_box();
            close_box();
        });
        
        document.getElementById('box_display').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        if (findGetParameter('post_id')) {
            var post;
            var post_id = findGetParameter('post_id');
            var blocks = document.getElementsByClassName('blocks');
            Array.from(blocks).forEach(function(block) {
                var box = block.childNodes;
                console.log(box);
                Array.from(box).forEach(function(elem) {
                    if (elem.dataset) {
                        if (elem.dataset.id == post_id) {
                            post = elem;
                            return;
                        }
                    }
                });
            });
            console.log(post);

            expand_post(post, findGetParameter('post_id'));
        }
        
        var post = document.getElementsByClassName('box');
        Array.from(post).forEach(function(e) {
            e.addEventListener('click', function(e) {
                var post = e.target;

                while (post.className != 'box')
                    post = post.parentElement;
                
                console.log(post);
                var post_id = post.dataset.id;
                console.log(post_id);
                expand_post(post, post_id);
                if (findGetParameter('user_id') == getCookie('user_id'))
                    delete_post_display_click(post_id);
            });
        });
    }
}

function expand_post(post, post_id) {
    console.log(post_id);
    var request = new XMLHttpRequest();
    request.open('POST', 'black_opacity_box_profil.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // console.log(post_id);
    request.send('post_id=' + post_id);
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200)
        {
            var i = 5;
            var black_opacity = document.getElementById('black_opacity');

            black_opacity.removeAttribute('class');
            var body = document.getElementsByTagName("BODY")[0];
            body.setAttribute('class', 'stop_scroll');
            var box_display = document.getElementById('box_display');
            box_display.dataset.id = post_id;

            var img_post = JSON.parse(this.responseText)['post_img'];
            var title_post = JSON.parse(this.responseText)['post_title'];
            var friends_post = JSON.parse(this.responseText)['post_friends'];
            var users = JSON.parse(this.responseText)['users'];
            var comments_post = JSON.parse(this.responseText)['post_comments'];
            var liked_post = JSON.parse(this.responseText)['post_liked'];
            var post_nb_likes = JSON.parse(this.responseText)['post_nb_likes'];
            var commented_post = JSON.parse(this.responseText)['post_commented'];
            var post_nb_comments = JSON.parse(this.responseText)['post_nb_comments'];

            var push_img = document.getElementById('img_display').src = post.childNodes[1].src;
            var push_title = document.getElementById('title_display').textContent = title_post;
            if (friends_post)
                push_friends(friends_post, users);
            push_comments(comments_post, users);
            push_liked(liked_post);
            var push_nb_likes = document.getElementsByClassName('number_likes')[0].textContent = post_nb_likes;
            push_commented(commented_post);
            // console.log(document.getElementsByClassName('number_comments'));
            var push_nb_comments = document.getElementsByClassName('number_comments')[0].textContent = post_nb_comments;
            console.log(document.getElementsByClassName('post_id_form')[0]);
            var push_post_id_to_form = document.getElementsByClassName('post_id_form')[0].value = post_id;
        }
    }
}


function close_box() {
    var black_opacity = document.getElementById('black_opacity');
    black_opacity.setAttribute('class', 'hide_black_opacity')
    document.body.className = '';
    if (document.getElementById('delete_post_display').childNodes[0])
        document.getElementById('delete_post_display').childNodes[0].remove();
}


function match_search_input_into_tab(search_input, tab) {
    var result = [];
    Array.from(tab).forEach(function(element) {
        var i = 0;
        while (i < search_input.length)
        {
            if (i >= element[1].length)
                return ;
            else if (search_input[i].toLowerCase() != element[1][i].toLowerCase())
                return ;
            i++;
        }
        if (search_input.length == 0)
            return ;
        result.push(element[1]);
    });
    return result;
}

function insert_elem_after_his_brother(first_child, name_new_child, attribute_new_child) {
    var parent = first_child.parentElement;
    var new_child = document.createElement(attribute_new_child);
    new_child.setAttribute('class', name_new_child);
    var new_child = parent.insertBefore(new_child, first_child.nextSibling);
    return new_child;
}

function putContentInBox2(user_id, username, box_child)
{
    var request = new XMLHttpRequest();
    request.open('POST', 'recover_img.php', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send('user_id=' + user_id);
    request.onreadystatechange = function ()
    {
        if (request.readyState == 4 && request.status == 200)
        {
            var img_content = JSON.parse(this.responseText)['img_user'];
                var user_img = document.createElement('img');
                box_child.appendChild(user_img);
                user_img.setAttribute('class', 'user_img');
                    user_img.src = 'data:image;base64,' + img_content;
                var user_name = document.createElement('div');
                box_child.appendChild(user_name);
                user_name.setAttribute('class', 'user_name');
                    user_name.textContent = username;
        }
    }
}

function create_box_when_elem_match(result, box_parent, tab, elem_to_create) {
    if (result.length > 0)
    {  
        Array.from(result).forEach(function(element) {
            var box_child = document.createElement(elem_to_create);
            box_parent.appendChild(box_child);
            box_child.setAttribute('class', 'box_child');
            box_child.setAttribute('onclick', 'display_username(this)');
            i = 0;
            while (i < tab.length)
            {
                if (tab[i][1] == element)
                    var user_id = tab[i][0];
                i++;
            }
            putContentInBox2(user_id, element, box_child);
        }); 
    }
}

function all_users_name(users) {
    var result = [];
    Array.from(users).forEach(function(e) {
        result.push(e[1]);
    });
    return result;
}

function display_username(e) {
    var input = e.parentElement.previousSibling.value;
    var username = e.textContent;
    e.parentElement.previousSibling.value = input.substr(0, input.indexOf('@')) + username + ' ';
    e.parentElement.previousSibling.focus();
    e.parentElement.remove();
}

if (location.pathname == '/upload.php')
{
    document.addEventListener('DOMContentLoaded', tag_search);

    function tag_search() {
        var request = new XMLHttpRequest();
        request.open('POST', 'recover_users.php', true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200)
            {
                var users = JSON.parse(this.responseText)['users'];
                var tag = 0;
                var tag_input = '';
                if (document.getElementById('search_tag')) {
                    document.getElementById('search_tag').addEventListener('keyup', function(e) {
                        var key = e.which || e.keyCode;
                        console.log(e.srcElement.nextSibling);
                        var last_input = e.srcElement.value.substr(e.srcElement.value.length - 1);
                    
                        if (last_input == '@')
                        {
                            tag_input = '';
                            tag = 1;
                        }
                        else if (last_input == ' ')
                        {
                            tag_input = '';
                            tag = 0;
                        }
                        else if (key == 8)
                            tag_input = tag_input.substr(0, tag_input.length - 1);

                        console.log(tag_input);
                        console.log(last_input);

                        console.log(tag);

                        if (tag == 1 && last_input != '@')
                        {
                            if (!tag_input)
                                tag_input = last_input;
                            else if (key != 8)
                                tag_input += last_input;
                            console.log(tag_input); 
                            var first_child = e.srcElement;
                            var box_users = document.getElementsByClassName('box_users');
                            console.log(box_users);
                            if (box_users[0])
                                box_users[0].remove();
                            var box_users = insert_elem_after_his_brother(first_child, 'box_users', 'div');
                            console.log(box_users);
                            var result = match_search_input_into_tab(tag_input, users);
                            create_box_when_elem_match(result, box_users, users, 'div');
                        }
                        else if (last_input == '@')
                        {
                            var first_child = e.srcElement;
                            var box_users = document.getElementsByClassName('box_users');
                            console.log(box_users);
                            if (box_users[0])
                                box_users[0].remove();
                            var box_users = insert_elem_after_his_brother(first_child, 'box_users', 'div');
                            console.log(box_users);
                            var result = all_users_name(users);
                            create_box_when_elem_match(result, box_users, users, 'div');
                        }
                        else if (tag == 0 && document.getElementsByClassName('box_users')[0])
                            document.getElementsByClassName('box_users')[0].remove();
                    });
                }
            }
        }
    }
}

function find_sender_name(tab_notif, users) {
    var result;
    Array.from(users).forEach(function(user) {
        if (user[0] == tab_notif[1])
            result =  user;
    });
    return result;
}

function push_content_notif(tab_notif, notif, users, sender) {
    var request = new XMLHttpRequest();
    request.open('POST', sender[4], true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send();
    request.onreadystatechange = function ()
    {
        file_read = request.responseText;
    
        if (tab_notif[4] == 'follow')
        {
            notif.innerHTML = '<img src="data:image;base64,' + file_read + '" alt=""><span class="text_notif"><b>' + sender[1] + '</b> a commencé à vous suivre</span>';
            notif.setAttribute('href', 'profil.php?user_id=' + tab_notif[1]);
        }
        else if (tab_notif[4] == 'taggued_post')
        {
            notif.innerHTML = '<img src="data:image;base64,' + file_read + '" alt=""><span class="text_notif"><b>' + sender[1] + '</b> vous a identifiez dans une photo</span>';
            notif.setAttribute('href', 'profil.php?user_id=' + tab_notif[1] + '&post_id=' + tab_notif[5]);
        }
        else if (tab_notif[4] == 'liked')
        {
            notif.innerHTML = '<img src="data:image;base64,' + file_read + '" alt=""><span class="text_notif"><b>' + sender[1] + '</b> a aimé votre photo</span>';
            notif.setAttribute('href', 'profil.php?user_id=' + tab_notif[2] + '&post_id=' + tab_notif[5]);
        }
        else if (tab_notif[4] == 'commented')
        {
            notif.innerHTML = '<img src="data:image;base64,' + file_read + '" alt=""><span class="text_notif"><b>' + sender[1] + '</b> a commenté votre photo</span>';
            notif.setAttribute('href', 'profil.php?user_id=' + tab_notif[2] + '&post_id=' + tab_notif[5]);
        }
    }
}

function click_notif(notif) {
    notif.addEventListener('click', function(e) {
        // e.preventDefault();
        console.log(notif.dataset.id);
        var request = new XMLHttpRequest();
        request.open('POST', 'push_notif_as_read.php', true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send('notif_id=' + notif.dataset.id);
    });
}

document.addEventListener('DOMContentLoaded', notif);

function notif() {
    document.getElementById('like_notif').addEventListener('click', function(e) {
        var request = new XMLHttpRequest();
        request.open('POST', 'recover_notif.php', true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200)
            {
                if (!document.getElementById('notif_box'))
                {
                    var like_notif = document.getElementById('like_notif');
                    like_notif.childNodes[0].src = 'ressources/like.png';
                    var notif_box_border = document.createElement('div');
                    like_notif.parentElement.insertBefore(notif_box_border, like_notif.nextSibling);
                    notif_box_border.setAttribute('id', 'notif_box_border');
                    var notif_box = document.createElement('div');
                    notif_box_border.appendChild(notif_box);
                    notif_box.setAttribute('id', 'notif_box');
                    var notifications = JSON.parse(this.responseText)['notifications'];
                    if (notifications.length == 0)
                    {
                        var no_notif = document.createElement('div');
                        notif_box.appendChild(no_notif);
                        no_notif.setAttribute('id', 'no_notif');
                        no_notif.style.textAlign = 'center';
                        no_notif.style.marginTop = '20px';
                        no_notif.textContent = 'No notifications';
                    }
                    else
                    {
                        var users = JSON.parse(this.responseText)['users'];
                        Array.from(notifications).forEach(function(notification) {
                            var tab_notif = notification;
                            var notif = document.createElement('a');
                            notif_box.appendChild(notif);
                            notif.setAttribute('class', 'notif');
                            notif.setAttribute('data-id', tab_notif[0]);
                            if (tab_notif[3] == 0)
                            {
                                notif.setAttribute('id', 'unread');
                                click_notif(notif);
                            }
                            var sender = find_sender_name(tab_notif, users);
                            if (!sender[4]) sender[4] = 'img/users/default';
                            push_content_notif(tab_notif, notif, users, sender);
                        });
                    }
                    // var notif_box = document.getElementById('notif_box');
                    // Array.from(notif_box.childNodes).forEach(function(notif_box_child) {
                        
                    // });
                }
                else
                    document.getElementById('notif_box_border').remove();
            }
        }
    });
}




function change_arrow() {
    var arrow_left = document.getElementById('arrow_left');
    var arrow_right = document.getElementById('arrow_right');
    var i = 0;
    var len_stickers = parseInt(document.getElementById('stickers_box').dataset.id) + 2;
    console.log(len_stickers);
    change_arrow_click(arrow_left, 'left');
    change_arrow_click(arrow_right, 'right');

    function change_arrow_click(arrow_img, direction) {
        console.log(i);
        if (i * -1 == len_stickers - 3)
            arrow_right.style.opacity = 0.35;
        arrow_img.addEventListener('mousedown', function(e) {
            if (direction == 'left' && i < 0 || direction == 'right' && i * -1 < len_stickers - 3)
                arrow_img.src = 'ressources/arrow_' + direction + '_hover.png';
            if (direction == 'left' && i < 0)
                i++;
            else if (direction == 'right' && i * -1 < len_stickers - 3)
                i--;
            console.log(i);
            var stickers = document.getElementById('stickers');
            stickers.style.transform = 'translateX(calc(14vh * ' + (i) + ')';
            
        });
        arrow_img.addEventListener('mouseup', function(e) {
            if (i >= 0) {
                arrow_left.style.opacity = 0.35;
                arrow_left.style.cursor = 'default';
            }
            else {
                arrow_left.style.opacity = 1;
                arrow_left.style.cursor = 'pointer';
            }
            if (i * -1 >= len_stickers - 3) {
                arrow_right.style.opacity = 0.35;
                arrow_right.style.cursor = 'default';
            }
            else {
                arrow_right.style.opacity = 1;
                arrow_right.style.cursor = 'pointer';
            }
            arrow_img.src = 'ressources/arrow_' + direction + '.png';
        });
        
    }
}


function zoom_in_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;
        
    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dwh < 1000) {
        frame_sticker.dataset.dwh = parseInt(frame_sticker.dataset.dwh) + 10;
        frame_sticker.dataset.dx = parseInt(frame_sticker.dataset.dx) - 5;
        frame_sticker.dataset.dy = parseInt(frame_sticker.dataset.dy) - 5;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function zoom_out_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;

    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dwh > 40) {
        frame_sticker.dataset.dwh = parseInt(frame_sticker.dataset.dwh) - 10;
        frame_sticker.dataset.dx = parseInt(frame_sticker.dataset.dx) + 5;
        frame_sticker.dataset.dy = parseInt(frame_sticker.dataset.dy) + 5;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function direction_top_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;

    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dy > -230) {
        console.log(frame_sticker.dataset.dy);
        frame_sticker.dataset.dy = parseInt(frame_sticker.dataset.dy) - 2;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function direction_right_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;

    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dx < 450) {
        console.log(frame_sticker.dataset.dx);
        frame_sticker.dataset.dx = parseInt(frame_sticker.dataset.dx) + 2;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function direction_bottom_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;

    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dy < 450) {
        console.log(frame_sticker.dataset.dy);
        frame_sticker.dataset.dy = parseInt(frame_sticker.dataset.dy) + 2;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function direction_left_click() {
    var frame_sticker = document.getElementById('frame_sticker');
    if (!frame_sticker)
        return;

    var stickers = document.getElementsByClassName('sticker');
    var sticker;
    Array.from(stickers).forEach(function(elem) {
        if (elem.dataset.id == frame_sticker.dataset.sticker_id)
            sticker = elem;
    });
    var img_sticker = sticker.childNodes[0];

    if (frame_sticker.dataset.dx > -280) {
        console.log(frame_sticker.dataset.dx);
        frame_sticker.dataset.dx = parseInt(frame_sticker.dataset.dx) - 2;
        frame_sticker.getContext('2d').clearRect(0, 0, frame_sticker.width, frame_sticker.height);
        frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    }
}

function sticker_click() {
    const zoom_in = document.getElementById('zoom_in');
    const zoom_out = document.getElementById('zoom_out');

    const direction_top = document.getElementById('direction_top');
    const direction_right = document.getElementById('direction_right');
    const direction_bottom = document.getElementById('direction_bottom');
    const direction_left = document.getElementById('direction_left');

    zoom_in.addEventListener('click', zoom_in_click);
    zoom_out.addEventListener('click', zoom_out_click);

    direction_top.addEventListener('click', direction_top_click);
    direction_right.addEventListener('click', direction_right_click);
    direction_bottom.addEventListener('click', direction_bottom_click);
    direction_left.addEventListener('click', direction_left_click);

    document.addEventListener('keydown', function(e) {
        var keypress = e.which || e.keyCode;
        if (keypress == 187)
            zoom_in_click();
        else if (keypress == 189)
            zoom_out_click();
        else if (keypress == 38)
            direction_top_click();
        else if (keypress == 39)
            direction_right_click();
        else if (keypress == 40)
            direction_bottom_click();
        else if (keypress == 37)
            direction_left_click();
    })
}

var one_time = 1;

function put_sticker(sticker) {
    const block = document.getElementById('block');
    if (document.getElementById('upload_img_video_screen'))
        var video_screen = document.getElementById('upload_img_video_screen');
    else
        var video_screen = document.getElementById('video_screen');

        document.getElementById('sticker_selected').removeAttribute('id');
        sticker.setAttribute('id', 'sticker_selected');

        const img_sticker = sticker.childNodes[0];
        var anchor_sticker = document.getElementById('anchor_sticker');

        if (document.getElementById('buttons_top'))
        document.getElementById('buttons_top').remove();

        if (anchor_sticker)
            anchor_sticker.remove(); 

        if (sticker.dataset.id == -1)
            return;
        
        create_buttons_top();

        var anchor_sticker = document.createElement('div');
            var frame_sticker = document.createElement('canvas');
                // const sticker_select = document.createElement('div');
                //     const img_sticker_select = document.createElement('img');
        const caroussel = document.getElementById('caroussel');

    anchor_sticker.setAttribute('id', 'anchor_sticker');
    anchor_sticker.appendChild(frame_sticker);
    frame_sticker.setAttribute('id', 'frame_sticker');
    frame_sticker.width = video_screen.offsetWidth;
    frame_sticker.height = video_screen.offsetHeight;
    
    if (video_screen.offsetWidth > video_screen.offsetHeight)
        frame_sticker.style.top = (-66.2 + ((frame_sticker.width - frame_sticker.height) / 2 * 100 / frame_sticker.width * 60) / 100) + 'vh';
    else
        frame_sticker.style.top = '-66.2vh';

    frame_sticker.dataset.dwh = 250;
    frame_sticker.dataset.dx = frame_sticker.width / 2 - frame_sticker.dataset.dwh / 2;
    frame_sticker.dataset.dy = frame_sticker.height / 2 - frame_sticker.dataset.dwh / 2;
    frame_sticker.dataset.sticker_id = sticker.dataset.id;
    frame_sticker.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
    
    block.insertBefore(anchor_sticker, caroussel);

    if (one_time) {
        sticker_click();
        one_time = 0;
    }
}


function submit_form_to_merge_img() {
    const block = document.getElementById('block');
    var video_screen = document.getElementById('video_screen');

    if (document.getElementById('upload_img_video_screen')) {
        var upload_img_video_screen = document.getElementById('upload_img_video_screen');
        var img_screen = upload_img_video_screen;
        if (document.getElementById('frame_sticker')) {
            var frame_sticker = document.getElementById('frame_sticker');

            var stickers = document.getElementsByClassName('sticker');
            var sticker;
            Array.from(stickers).forEach(function(elem) {
                if (elem.dataset.id == frame_sticker.dataset.sticker_id)
                    sticker = elem;
            });

            var img_sticker = sticker.childNodes[0];
            
            var final_canvas = document.createElement('canvas');
            console.log(upload_img_video_screen.width);
            console.log(upload_img_video_screen.height);
            final_canvas.width = 600;
            final_canvas.height = (upload_img_video_screen.height * 600) / upload_img_video_screen.width;
            final_canvas.getContext('2d').drawImage(upload_img_video_screen, 0, 0, 600, (upload_img_video_screen.height * 600) / upload_img_video_screen.width);
            // var ratio = upload_img_video_screen.width / frame_sticker.width;
            // frame_sticker.dataset.dx *= ratio;
            // frame_sticker.dataset.dy *= ratio;
            // frame_sticker.dataset.dwh *= ratio;

            final_canvas.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx * 600 / upload_img_video_screen.width, frame_sticker.dataset.dy * 600 / upload_img_video_screen.width, frame_sticker.dataset.dwh * 600 / upload_img_video_screen.width, frame_sticker.dataset.dwh * 600 / upload_img_video_screen.width);
            var final_screen = document.createElement('img');
            final_screen.setAttribute('id', 'upload_img_video_screen');
            final_screen.src = final_canvas.toDataURL('image/png');
            console.log(final_screen);
            upload_img_video_screen.remove();
            anchor_sticker.remove();
            video_screen.appendChild(final_screen);
            var img_screen = final_screen;
        }
    }
    else {
        var img_screen = video_screen;
        var final_canvas = document.createElement('canvas');
        final_canvas.width = video_screen.width;
        final_canvas.height = video_screen.height;
        final_canvas.getContext('2d').drawImage(video_screen, 0, 0, video_screen.width, video_screen.height);

        if (document.getElementById('frame_sticker')) {
            var frame_sticker = document.getElementById('frame_sticker');

            var stickers = document.getElementsByClassName('sticker');
            var sticker;
            Array.from(stickers).forEach(function(elem) {
                if (elem.dataset.id == frame_sticker.dataset.sticker_id)
                    sticker = elem;
            });

            var img_sticker = sticker.childNodes[0];

            var ratio = video_screen.width / frame_sticker.width;
            frame_sticker.dataset.dx *= ratio;
            frame_sticker.dataset.dy *= ratio;
            frame_sticker.dataset.dwh *= ratio;

            final_canvas.getContext('2d').drawImage(img_sticker, frame_sticker.dataset.dx, frame_sticker.dataset.dy, frame_sticker.dataset.dwh, frame_sticker.dataset.dwh);
        }
        var final_screen = document.createElement('img');
        final_screen.setAttribute('id', 'video_screen');
        final_screen.src = final_canvas.toDataURL('image/png');
        console.log(final_screen);
        video_screen.remove();
        const buttons_picture = document.getElementById('buttons_picture');
        block.insertBefore(final_screen, buttons_picture);
        var img_screen = final_screen;
    }
    const form_validate_picture = document.createElement('form');
    const img_src_validate_picture = document.createElement('input');
    const submit_validate_picture = document.createElement('input');

    form_validate_picture.setAttribute('action', 'upload.php');
    form_validate_picture.setAttribute('method', 'POST');
    form_validate_picture.setAttribute('id', 'form_validate_picture');
    block.appendChild(form_validate_picture);

    img_src_validate_picture.setAttribute('type', 'hidden');
    img_src_validate_picture.setAttribute('name', 'img_screen');
    img_src_validate_picture.setAttribute('value', img_screen.src);
    form_validate_picture.appendChild(img_src_validate_picture);

    submit_validate_picture.setAttribute('id', 'submit_validate_picture');
    submit_validate_picture.setAttribute('type', 'submit');
    submit_validate_picture.setAttribute('class', 'input-file');
    form_validate_picture.appendChild(submit_validate_picture);
    
    submit_validate_picture.click();
}

function add_validate() {
    const block = document.getElementById('block');
    const caroussel = document.getElementById('caroussel');

    if (document.getElementById('validate_picture'))
        document.getElementById('validate_picture').remove();

    const frame_validate_picture = document.createElement('div');
    const validate_picture = document.createElement('div');
        const text_validate_picture = document.createElement('div');
        const img_validate_picture = document.createElement('img');

    frame_validate_picture.setAttribute('id', 'frame_validate_picture');
    block.insertBefore(frame_validate_picture, caroussel);
    
    validate_picture.setAttribute('id', 'validate_picture');
    validate_picture.setAttribute('class', 'not_clickable');
    frame_validate_picture.appendChild(validate_picture);

        text_validate_picture.setAttribute('id', 'text_validate_picture');
        text_validate_picture.textContent = 'Validate';
        validate_picture.appendChild(text_validate_picture);

        img_validate_picture.src = 'ressources/checked.png';
        validate_picture.appendChild(img_validate_picture);

    validate_picture.addEventListener('click', submit_form_to_merge_img);
}

function change_button_take_and_delete() {
    var take_picture = document.getElementById('take_picture');
    take_picture.className = 'not_clickable';
    var take_picture_img = take_picture.childNodes[0];
    take_picture_img.src = 'ressources/picture_white_not_clickable.png';

    var delete_picture = document.getElementById('delete_picture');
    delete_picture.className = '';
    var delete_picture_img = delete_picture.childNodes[0];
    delete_picture_img.src = 'ressources/garbage.png';
}


function take_picture_click(block, video, buttons_picture) {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.setAttribute('id', 'video_screen');
    // var video_screen = document.createElement('img');
    // video_screen.setAttribute('id', 'video_screen');
    // video_screen.src = canvas.toDataURL('image/png');
    video.remove();
    block.insertBefore(canvas, buttons_picture);
    change_button_take_and_delete();
    add_validate();
}

function delete_picture_click(block, buttons_picture) {
    const video_screen = document.getElementById('video_screen');
    video_screen.remove();
    const video = create_video();
    buttons_picture.remove();
    if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
        console.log("no camera suported !")
    } else {
        navigator.mediaDevices.getUserMedia({video: {width: 600, height: 600}}).then(successCallback).catch(errorCallback);
    }
    const caroussel = document.getElementById('caroussel');
    block.insertBefore(video, caroussel);
}

function upload_picture_click(e, block, buttons_picture) {
    if (document.getElementById('anchor_sticker'))
        document.getElementById('anchor_sticker').remove();
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        var video_screen = e.target.files[0];
        var reader = new FileReader();
        change_button_take_and_delete();
        if (!video_screen.type) {
            if (!document.getElementById('error_upload')) {
                var buttons_picture = document.getElementById('buttons_picture');
                var error_upload = document.createElement('div');
                error_upload.setAttribute('id', 'error_upload');
                buttons_picture.appendChild(error_upload);
                error_upload.textContent = 'Only JPEG and PNG files are accepted.';
                return;
            }
        }
        else if (video_screen.type == 'image/jpeg' || video_screen.type == 'image/jpg' || video_screen.type == 'image/png') {
            add_validate();
            if (document.getElementById('error_upload'))
                document.getElementById('error_upload').remove();

            reader.onload = function(new_elem) {
                var video_screen = document.getElementById('video_screen');
                var new_video_screen = document.createElement('div');
                new_video_screen.setAttribute('id', 'video_screen');
                    var upload_img_new_video_screen = document.createElement('img');
                    upload_img_new_video_screen.setAttribute('id', 'upload_img_video_screen');
                    upload_img_new_video_screen.src = new_elem.target.result;
                    new_video_screen.appendChild(upload_img_new_video_screen);
                block.insertBefore(new_video_screen, video_screen);
                    // var upload_canvas_new_video_screen = document.createElement('canvas');
                    // upload_canvas_new_video_screen.setAttribute('id', 'upload_img_video_screen');
                    // upload_canvas_new_video_screen.width = upload_img_new_video_screen.width;
                    // upload_canvas_new_video_screen.height = upload_img_new_video_screen.height;
                    // console.log(upload_img_new_video_screen.width);
                    // upload_canvas_new_video_screen.getContext('2d').drawImage(upload_img_new_video_screen, 0, 0, upload_img_new_video_screen.width, upload_img_new_video_screen.height);
                    // new_video_screen.appendChild(upload_canvas_new_video_screen);
                // upload_img_new_video_screen.remove();
                video_screen.remove();
            }
        }
        if (video_screen)
            reader.readAsDataURL(video_screen);

    }
    else {
        alert('The File APIs are not fully supported in this browser.');
    }
}


function create_video() {
    const video = document.createElement('video');
    video.setAttribute('autoplay', '');
    video.setAttribute('id', 'video_screen');
    return (video);
}

function create_buttons_top() {
    const buttons_picture = document.getElementById('buttons_picture');

    const buttons_top = document.createElement('div');
        const directions_picture = document.createElement('div');
            const direction_top_picture = document.createElement('img');
            const direction_right_picture = document.createElement('img');
            const direction_bottom_picture = document.createElement('img');
            const direction_left_picture = document.createElement('img');
        const zoom_picture = document.createElement('div');
            const zoom_in = document.createElement('img');
            const zoom_out = document.createElement('img');

    buttons_top.setAttribute('id', 'buttons_top');
    buttons_picture.appendChild(buttons_top);
    
        directions_picture.setAttribute('id', 'directions_picture');
        buttons_top.appendChild(directions_picture);

            direction_top_picture.setAttribute('id', 'direction_top');
            direction_top_picture.src = 'ressources/direction_top.png';
            directions_picture.appendChild(direction_top_picture);

            direction_right_picture.setAttribute('id', 'direction_right');
            direction_right_picture.src = 'ressources/direction_right.png';
            directions_picture.appendChild(direction_right_picture);

            direction_bottom_picture.setAttribute('id', 'direction_bottom');
            direction_bottom_picture.src = 'ressources/direction_bottom.png';
            directions_picture.appendChild(direction_bottom_picture);

            direction_left_picture.setAttribute('id', 'direction_left');
            direction_left_picture.src = 'ressources/direction_left.png';
            directions_picture.appendChild(direction_left_picture);

        zoom_picture.setAttribute('id', 'zoom_picture');
        buttons_top.appendChild(zoom_picture);

            zoom_in.setAttribute('id', 'zoom_in');
            zoom_in.src = 'ressources/zoom-in.png';
            zoom_picture.appendChild(zoom_in);

            zoom_out.setAttribute('id', 'zoom_out');
            zoom_out.src = 'ressources/zoom-out.png';
            zoom_picture.appendChild(zoom_out);
}

function create_buttons_picture() {
    const buttons_picture = document.createElement('div');
    const buttons_bottom = document.createElement('div');
        const delete_picture = document.createElement('button');
            const img_delete_picture = document.createElement('img');
        const take_picture = document.createElement('button');
            const img_take_picture = document.createElement('img');
        const upload_picture = document.createElement('button');
            const label_upload_picture = document.createElement('label');
                const img_upload_picture = document.createElement('img');
            const input_upload_picture = document.createElement('input');

    buttons_picture.setAttribute('id', 'buttons_picture');

    buttons_bottom.setAttribute('id', 'buttons_bottom');
    buttons_picture.appendChild(buttons_bottom);

        delete_picture.setAttribute('id', 'delete_picture');
        delete_picture.setAttribute('class', 'not_clickable');                        
        buttons_bottom.appendChild(delete_picture);
        img_delete_picture.src = 'ressources/garbage_not_clickable.png';
        delete_picture.appendChild(img_delete_picture);

        take_picture.setAttribute('id', 'take_picture');                      
        buttons_bottom.appendChild(take_picture);
        img_take_picture.src = 'ressources/picture_white.png';
        take_picture.appendChild(img_take_picture);
        
        upload_picture.setAttribute('id', 'upload_picture');
        buttons_bottom.appendChild(upload_picture);
            label_upload_picture.setAttribute('id', 'label_upload_picture');
            label_upload_picture.setAttribute('for', 'input_upload_picture');
            upload_picture.appendChild(label_upload_picture);
                img_upload_picture.src = 'ressources/gallery.png';
                label_upload_picture.appendChild(img_upload_picture);
            input_upload_picture.setAttribute('id', 'input_upload_picture');
            input_upload_picture.setAttribute('name', 'img');
            input_upload_picture.setAttribute('type', 'file');
            input_upload_picture.setAttribute('class', 'input-file');
            upload_picture.appendChild(input_upload_picture);
    return (buttons_picture);
}

function change_state_of_take_and_delete_picture() {
    var delete_picture = document.getElementById('delete_picture');
    delete_picture.className = 'not_clickable';
    var delete_picture_img = delete_picture.childNodes[0];
    delete_picture_img.src = 'ressources/garbage_not_clickable.png';

    var take_picture = document.getElementById('take_picture');
    take_picture.className = '';
    var take_picture_img = take_picture.childNodes[0];
    take_picture_img.src = 'ressources/picture_white.png';
}


function successCallback(stream) {
    console.log("test");
    
    const block = document.getElementById('block');
    const img = document.querySelector('#no_picture');
    var video_screen = document.getElementById('video_screen');
    if (video_screen)
        video_screen.remove();
    var video_screen = create_video();
    const buttons_picture = create_buttons_picture();
    video_screen.srcObject = stream;
    if (img)
        img.remove();
    block.insertBefore(video_screen, caroussel);
    block.insertBefore(buttons_picture, caroussel);

    change_state_of_take_and_delete_picture();
    
    const take_picture = document.getElementById('take_picture');    
    take_picture.addEventListener('click', function(e) {
        if (!(take_picture.className == 'not_clickable'))
            take_picture_click(block, video_screen, buttons_picture);
    });
    
    const delete_picture = document.getElementById('delete_picture');
    delete_picture.addEventListener('click', function(e) {
        if (!(delete_picture.className == 'not_clickable'))
            delete_picture_click(block, buttons_picture);
    });
    
    const upload_picture = document.getElementById('upload_picture');
    upload_picture.addEventListener('click', function(e) {
        const label_upload_picture = document.getElementById('label_upload_picture');
        label_upload_picture.click();
        upload_picture.addEventListener('change', function(e) {
            upload_picture_click(e, block, buttons_picture);
        });
    });
    const input_upload_picture = document.getElementById('input_upload_picture');
    label_upload_picture.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

function errorCallback(error) {
    console.error('Reeeejected!', error);
}


document.addEventListener('DOMContentLoaded', picture);

function picture() {
    if (location.pathname == '/picture.php') {
        put_cam();
        change_arrow();
        function put_cam() {
            function hasGetUserMedia() {
                return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
            }
            if (hasGetUserMedia()) {

                if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
                    console.log("no camera suported !")
                } else {
                    navigator.mediaDevices.getUserMedia({video: {width: 600, height: 600}}).then(successCallback).catch(errorCallback);
                }
            }
            else {
                alert('getUserMedia() is not supported by your browser');
            }
        }
    }
}