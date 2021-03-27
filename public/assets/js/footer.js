const words_array = [
    'Manguera', 'reloj', 'regadera', 'cepillo', 'pasta termica', 'calculadora', 
    'aspiradora', 'escobilla', 'reloj pulsera', 'alfombra', 'batidora', 'cocina', 
    'zapatillas', 'Ipsum', 'Lorem'
]

$('#tercer_bloque_footer .col-md-4').each(function(index, element){
    var divider = '·'
    if(index==1) divider = '¦'
    if(index==2) divider = '|'
    
    var $container = $("<div class='container'></div>")

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


// $('.etiquetas_populares_footer .words_footer:nth-child()')