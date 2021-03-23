import '../styles/productoView.css'

/*
var firstColor = $('.badge').data('color')
$('.other-box').css("background-color", firstColor)
*/

$('.clasificacion i').click(function(){
    $actual = this
    $color = 'orangered'
    $('.clasificacion i').each(function(index, element){
        element.css('color', $color)
        if(element === $actual) 
        {
            $color = 'grey'
            document.forms["form"]["clasificacion"].value = index
        }
    })
})