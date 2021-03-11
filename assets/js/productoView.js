import '../styles/productoView.css'

var firstColor = $('.badge').data('color')
$('.other-box').css("background-color", firstColor)

var otherBoxWidth = $('.other-box').width()
$('.carousel').css('width', otherBoxWidth + 60)


var carouselTop = otherBoxWidth + (otherBoxWidth/3)
console.log('carouselTop: ', carouselTop)
$('.carousel').css('top', carouselTop)

$('.carousel-item .box').click(function(){
    console.log('Diste click!!!')
    var thisColor = $(this).css("background-color");
    $('.other-box').css("background-color", thisColor);
})