//import '../styles/header.css'

$('.losPagos i').addClass('fa-3x')

$('.losPagos i').each(function(index, element){
    var tdWidth = $(this).parent('td').width
    var selfWidth = $(this).width
    $(this).css('left', (tdWidth-selfWidth)/2)
});

var resize_mastercard = function(){
  $('.mastercard_img').each(function(index, element){
    var visa_width = $(this).next('img').width()
    var visa_heigth = $(this).next('img').height()
    
    $(this).css('width', visa_width)
    $(this).css('height', visa_heigth)
  })
  };

$(window).resize(resize_mastercard);
$(window).on('load', resize_mastercard);