const words_array = [
    'Manguera', 'reloj', 'regadera', 'cepillo', 'pasta termica', 'calculadora', 
    'aspiradora', 'escobilla', 'reloj pulsera', 'alfombra', 'batidora', 'cocina', 
    'zapatillas', 'Ipsum', 'Lorem'
]

console.log('Estamos en footer!!')

$('#tercer_bloque_footer .col-md-4').each(function(index, element){
    var divider = ' · '
    if(index==1) divider = ' ¦ '
    if(index==2) divider = ' | '
    
    var $container = $("<div class='nube_manguera'></div>")

    words_array.forEach(word => {
        var $a = $("<span class='words_footer'>"+word+'</span>')
        var $b = $('<span>'+divider+'</span>')
        $container.append($a)
        $container.append($b)
    });

    if(index === 2) $container.addClass('etiquetas_populares_footer')

    //$container.empty('span:last-child')
    $(this).append($container)
})

/*
$('.col-md-4 .nube_manguera:nth-child(0)').css('font-size', '0.294cm')
$('.col-md-4 .nube_manguera:nth-child(1)').css('font-size', '0.394cm')
$('.col-md-4 .nube_manguera:nth-child(2)').css('font-size', '0.494cm')
*/

// $('.etiquetas_populares_footer .words_footer:nth-child()')