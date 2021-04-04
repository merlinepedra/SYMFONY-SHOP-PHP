//import '../styles/header.css'

$('.losPagos i').addClass('fa-3x')

$('.losPagos i').each(function(index, element){
    var tdWidth = $(this).parent('td').width
    var selfWidth = $(this).width
    $(this).css('left', (tdWidth-selfWidth)/2)
});

var resize_mastercard = function(){
    var visa_width = $('#mastercard_img').next('img').width()
    var visa_heigth = $('#mastercard_img').next('img').height()
    console.log('visa_width: ', visa_width)
    console.log('visa_heigth: ', visa_heigth)
    $('#mastercard_img').css('width', visa_width)
    $('#mastercard_img').css('height', visa_heigth)
  };

$(window).resize(resize_mastercard);
$(window).on('load', resize_mastercard);