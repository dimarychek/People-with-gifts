$(document).ready(function(){

    $('.emailb').click(function(){
        var em = $('.email').val();
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

        if(!pattern.test(em) || em.length==0)
        {
            $('.mail')
                .addClass('error')
                .after('<div class="error">Некорректный E-mail!</div>');
            return false;
        }else {
            var ajaxurl = '/wp-admin/admin-ajax.php';
            var mail = {
                action : 'myajax_submit',
                email : em
            };

        $.post( ajaxurl, mail, function (back) {
                $('.mail')
                .addClass('send')
                .after('<div class="not_error">Заявка отправлена!</div>');
            $('.email').val('');
        });
        }
    });

    $(':input').focus(function() {
        $('.email')
            .removeClass('error')
            .next('div')
            .remove();
    });
});

$(document).ready(function() {
    $('.count_mark').height($('.count_mark').width());
});

function pulse() {
    add();
    function add() {
        $('.gift_count').addClass('pulse');
        $('.gift_block').addClass('pulse');
        $('.gift_block1').addClass('pulse');
        $('.gift_block2').addClass('pulse');

    }
    setTimeout(remove, 5000);
    function remove() {
        $('.gift_count').removeClass('pulse');
        $('.gift_block').removeClass('pulse');
        $('.gift_block1').removeClass('pulse');
        $('.gift_block2').removeClass('pulse');
    }
}

$(document).ready(function() {
   setInterval(pulse, 10000);
});