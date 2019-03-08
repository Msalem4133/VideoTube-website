function postComment(button, postedBy, videoId, replyTo, containerClass) {
  var textarea = $(button).siblings('textarea');
  var commentText = textarea.val();
  textarea.val('');

  if (commentText) {
    $.post('ajax/postComment.php', {
      commentText: commentText,
      postedBy: postedBy,
      videoId: videoId,
      responseTo: replyTo
    }).done(function(comment) {
      if (!replyTo) $('.' + containerClass).prepend(comment);
      else
        $(button)
          .parent()
          .siblings('.' + containerClass)
          .append(comment);
    });
  } else {
    alert("you can't post an empty comment");
  }
}

function toggleReplay(button) {
  console.log('mido');
  var parent = $(button).closest('.itemContainer');
  var commentForm = parent.find('.commentForm').first();

  commentForm.toggleClass('hidden');
}

function likeComment(commentId, button, videoId) {
  $.post('ajax/likeComment.php', {
    commentId: commentId,
    videoId: videoId
  }).done(function(data) {
    var likeButton = $(button);
    var dislikeButton = $(button).siblings('.dislikeButton');

    var likeCount = $(button).siblings('.likedCount');

    dislikeButton.removeClass('active');

    var result = JSON.parse(data);

    var counter = likeCount.text();
    if (result.likes > 0) {
      likeButton.addClass('active');
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up-active.png');

      if (counter == '') {
        likeCount.text(1);
      } else {
        likeCount.text(parseInt(counter) + parseInt(result.likes));
      }
    } else {
      likeButton.removeClass('active');
      likeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-up.png');
      if (counter == 1) {
        likeCount.text('');
      } else {
        likeCount.text(parseInt(counter) - 1);
      }
    }
    dislikeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-down.png');
  });
}

function dislikeComment(commentId, button, videoId) {
  $.post('ajax/dislikeComment.php', {
    commentId: commentId,
    videoId: videoId
  }).done(function(data) {
    var dislikeButton = $(button);
    var likeButton = $(button).siblings('.likeButton');

    var likeCount = $(button).siblings('.likedCount');

    dislikeButton.addClass('active');

    var result = JSON.parse(data);

    var counter = likeCount.text();
    if (result.likes < 1) {
      dislikeButton.addClass('active');
      dislikeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down-active.png');

      if (counter == '') {
        likeCount.text(-1);
      } else {
        likeCount.text(parseInt(counter) + parseInt(result.likes));
      }
    } else {
      dislikeButton.removeClass('active');
      dislikeButton
        .find('img:first')
        .attr('src', 'assets/images/icons/thumb-down.png');
      if (counter == 1 || counter == -1) {
        likeCount.text('');
      } else {
        likeCount.text(parseInt(counter) + 1);
      }
    }
    likeButton
      .find('img:first')
      .attr('src', 'assets/images/icons/thumb-up.png');
  });
}

function getReplies(commentId, button, videoId) {
  $.post('ajax/getCommentReplies.php', {
    commentId: commentId,
    videoId: videoId
  }).done(function(comments) {
    var replies = $('<div>').addClass('repliesSection');
    replies.append(comments);
    $(button).replaceWith(replies);
  });
}
