import '../styles/easyCarousel.css'

$('.in').each(function(index, element){
    var parent = $(this).closest('.out')
    var mywidth = $(this).width()
    var parentwidth = parent.width()
    var value = (parentwidth - mywidth)/2
    //console.log('parent width: ', parentwidth)
    //console.log('my width: ', mywidth)
    //console.log('calculo: ', value)
    $(this).css({left: value})
})


$('.carousel-indicators li:nth-child(1)').addClass('active')
$('.carousel-inner .carousel-item:nth-child(1)').addClass('active')

$('.carousel-item .row .col-md-4:nth-child(2)').addClass('clearfix d-none d-md-block')
$('.carousel-item .row .col-md-4:nth-child(3)').addClass('clearfix d-none d-md-block')