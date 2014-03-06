var Base = {
    timeout: new Array(),
    interval: new Array(),
    setTimeout: function(token, func, time) {
        Base.timeout[token] = setTimeout(function(){func()}, time);
    },
    clearTimeout: function(token) {
        clearTimeout(Base.timeout[token]);
    },
    setInterval: function(token, func, time) {
        Base.interval[token] = setInterval(function(){func()}, time);
    },
    clearInterval: function(token) {
        clearInterval(Base.interval[token]);
    },
    input: {
        clear: function(el) {
            $(el).parent().find('input').val('');
        },
        increment: function(el) {
            var input = $(el).parent().parent().find('input'),
                val = parseInt(input.val());

            if(isNaN(input.data('max')) || val < $(el).parent().parent().find('input').data('max'))
            {
                $(el).parent().parent().find('input').val(++val);
            }
            else
            {
                $(el).parent().parent().find('input').val(input.data('max'));
            }

            Base.setTimeout('fast_increment', function(){
                Base.input.fastIncrement(el);
            }, 500);

            $(el).bind('mouseup', function(){
                Base.clearTimeout('fast_increment');
                Base.clearInterval('fast_increment');
            });
        },
        fastIncrement: function(el) {
            Base.setInterval('fast_increment', function(){
                var input = $(el).parent().parent().find('input'),
                    val = parseInt(input.val());

                if(isNaN(input.data('max')) || val < $(el).parent().parent().find('input').data('max'))
                {
                    $(el).parent().parent().find('input').val(++val);
                }
                else
                {
                    $(el).parent().parent().find('input').val(input.data('max'));
                }
            }, 50);

        },
        decrement: function(el) {
            var input = $(el).parent().parent().find('input'),
                val = parseInt(input.val());

            if(isNaN(input.data('min')) || val > $(el).parent().parent().find('input').data('min'))
            {
                $(el).parent().parent().find('input').val(--val);
            }
            else
            {
                $(el).parent().parent().find('input').val(input.data('min'));
            }

            Base.setTimeout('fast_decrement', function(){
                Base.input.fastDecrement(el);
            }, 500);

            $(el).bind('mouseup', function(){
                Base.clearTimeout('fast_decrement');
                Base.clearInterval('fast_decrement');
            });
        },
        fastDecrement: function(el) {
            Base.setInterval('fast_decrement', function(){
                var input = $(el).parent().parent().find('input'),
                    val = parseInt(input.val());

                if(isNaN(input.data('min')) || val > $(el).parent().parent().find('input').data('min'))
                {
                    $(el).parent().parent().find('input').val(--val);
                }
                else
                {
                    $(el).parent().parent().find('input').val(input.data('min'));
                }
            }, 50);

        },
        check: function(el) {
            if(!$(el).attr('checked'))
            {
                $(el).removeAttr('checked');
                $(el).next().find('span').removeClass('checked');
            }
            else
            {
                $(el).attr('checked', 'checked');
                $(el).next().find('span').addClass('checked');
            }
        },
        radioCheck: function(el) {
            var id = $(el).attr('id'),
                name = $(el).attr('name');

            $('.at-radio input[name='+name+']').each(function(i, el){
                var el_id = $(el).attr('id');
                $('label[for='+el_id+'] span').removeClass('checked');
            });

            $('label[for='+id+']').find('span').addClass('checked');
        }
    }
};