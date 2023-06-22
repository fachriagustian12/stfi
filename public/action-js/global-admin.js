$(document).ready(function(){
  sessionStorage.setItem("survey", 0);
  var sid = sessionStorage.getItem("sidebar");
  sid == 1 ? $('body').addClass('sidebar-enable vertical-collpsed') : $('body').removeClass('sidebar-enable vertical-collpsed')
});
