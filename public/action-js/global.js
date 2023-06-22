$(document).ready(function(){


});

function pdfmodal(title, src) {
  $('#pdf-modal').modal('show')
  $('#pdf-title').html(title)
  $('#pdf-embed').attr('src', src)
  $('#pdf-download').attr('href', src)
}
