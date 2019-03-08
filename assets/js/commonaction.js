$(document).ready(function() {
  //in this function we toggle side nav bar and when toggling giving padding to section container
  $('.navShowHide').on('click', function() {
    var main = $('#mainSectionContainer');
    var nav = $('#sideNavContainer');
    //hasClass to see if main has a class called leftpadding
    //note:.show or .hide gives inline style not class so high priorty
    if (main.hasClass('leftPadding')) {
      nav.hide();
    } else {
      nav.show();
    }
    main.toggleClass('leftPadding');
  });
});

function notSignedIn() {
  alert('You must sign in to perform this action');
}
