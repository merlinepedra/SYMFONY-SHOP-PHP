//import '../styles/productoView.css'

/*
var firstColor = $('.badge').data('color')
$('.other-box').css("background-color", firstColor)
*/

$('.calificacion img').click(function(){
    var value = parseInt($(this).data('index'))
    $('#form_calificacion').val(value)

    
    $('.calificacion .gris_star').each(function(index, element){
        if(index < value) $(this).css('display', 'none')
        else $(this).css('display', 'inline')
    })

    $('.calificacion .orange_star').each(function(index, element){
        if(index < value) $(this).css('display', 'inline')
        else $(this).css('display', 'none')
    })
})

$('.calificacion .gris_star').hover(function(){
    /*
    $(this).css('display', 'none')
    $(this).next('.orange_star').css('display', 'inline')
    */
})