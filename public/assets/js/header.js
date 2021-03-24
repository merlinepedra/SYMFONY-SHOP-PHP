//import '../styles/header.css'

$('.losPagos i').addClass('fa-3x')

$('.losPagos i').each(function(index, element){
    var tdWidth = $(this).parent('td').width
    var selfWidth = $(this).width
    $(this).css('left', (tdWidth-selfWidth)/2)
});