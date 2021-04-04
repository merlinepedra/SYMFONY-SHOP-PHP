var resize_all = function(){
    var parentWidth = $('#carritoSection .other-box-index').parent('div').width()
    console.log('parent width: ', parentWidth)
    var value = (parentWidth - 30)/2
    $('#carritoSection .other-box-index').css('width', value)
    $('#carritoSection .other-box-index').css('height', value)


    $('#cant_sub div').css('width', (parentWidth - 10)/ 2)
  };

$(window).resize(resize_all);
$(window).on('load', resize_all);