//import '../styles/productoView.css'

/*
var firstColor = $('.badge').data('color')
$('.other-box').css("background-color", firstColor)
*/

$('.calificacion i').click(function(){
    var value = parseInt($(this).data('index'))
    $('#form_calificacion').val(value)
    $('.calificacion i').each(function(index, element){
        if(index < value) $(this).css('color', 'orangered')
        else $(this).css('color', 'grey')
    })
})