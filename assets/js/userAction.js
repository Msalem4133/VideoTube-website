function subscribe(userTo, userFrom, button) {
  if (userTo == userFrom) {
    alert("you can't subscribe to yourself");
    return;
  }
  $.post('ajax/subscribe.php',{userTo:userTo,userFrom:userFrom})
  .done(function(data) {
    console.log(data);
    var result=JSON.parse(data);
    var subscribeButton=$(button);
    var elemnt=subscribeButton.find('.text')
    if(result.subsribe>0)
    {
      subscribeButton.removeClass('subscribe');
      subscribeButton.addClass('unsubscribe');
      elemnt.text("SUBSCRIBED "+result.count)
    }
    else
    {
      subscribeButton.removeClass('unsubscribe');
      subscribeButton.addClass('subscribe');
      elemnt.text("SUBSCRIBE "+result.count)
    }
  });
}
