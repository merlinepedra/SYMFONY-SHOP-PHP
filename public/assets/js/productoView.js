//import '../styles/productoView.css'

/*
var firstColor = $('.badge').data('color')
$('.other-box').css("background-color", firstColor)
*/

$('.calificacion img').click(function(){
    var value = parseInt($(this).data('index'))
    $('#form_calificacion').val(value)
    $('.calificacion img').each(function(index, element){
        /*
        if(index < value) $(this).css('color', 'orangered')
        else $(this).css('color', 'grey')
        */

        if(index < value) $(this).css('background-image', "url('../../exact_images/estrella_naranja.png')")
        else $(this).css('background-image', "url('../../exact_images/estrella_gris.png')")
    })
})