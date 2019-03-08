function likeVideo(button, videoId) {
  $.post('ajax/likeVideo.php', { videoId: videoId }).done(function(data) {
    var likeButton = $(button);
    var dislikeBotton = $(button).siblings('.dislikeButton');
    
    likeButton.addClass('active');
    dislikeBotton.removeClass('active');
    var result = JSON.parse(data);
    updateLikesValue(likeButton.find('.text'), result.likes);
    updateLikesValue(dislikeBotton.find('.text'), result.dislikes);

    if (result.likes < 0) {
      likeButton.removeClass('active');
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up.png');
    } else {
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up-active.png');
    }
    dislikeBotton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-down.png');
  });
}
function dislikeVideo(button, videoId) {
  $.post('ajax/DislikeVideo.php', { videoId: videoId }).done(function(data) {

    var dislikebutton = $(button);
    var likebutton = $(button).siblings('.likeButton');

    likebutton.removeClass('active');
    dislikebutton.addClass('active');

    console.log(data);
    var result = JSON.parse(data);

    updateDislikevalue(dislikebutton.find('.text'), result.dislikes);
    updateDislikevalue(likebutton.find('.text'), result.likes);
    console.log(result);
    if (result.dislikes < 0) {
      dislikebutton.removeClass('active');
      dislikebutton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down.png');
    } else {
      dislikebutton.addClass('active');
      dislikebutton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down-active.png');
    }
    likebutton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-up.png');
    
  });
}

function updateLikesValue(elemnt, num) {
  var likesCountVal = elemnt.text() || 0;
  elemnt.text(parseInt(likesCountVal) + parseInt(num));
}
function updateDislikevalue(elemnt, num) {
  var dislikescountVal = elemnt.text() || 0;
  elemnt.text(parseInt(dislikescountVal) + parseInt(num));
}
