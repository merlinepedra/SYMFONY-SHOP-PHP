import '../styles/productoView.css'

var firstColor = $('.badge').data('color')
console.log(firstColor)
$('.other-box').css("background-color", firstColor)

$('.carousel-item .box').click(function(){
    console.log('Diste click!!!')
    var thisColor = $(this).css("background-color");
    $('.other-box').css("background-color", thisColor);
})