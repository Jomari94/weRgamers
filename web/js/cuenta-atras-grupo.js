if (!yiiOptions.newEvent) {
    var begin = moment.tz(yiiOptions.inicio, moment.tz.guess());
    $('#countdown').countdown(begin.toDate(), {})
        .on('update.countdown', function(event) {
            var format = '<input type="text" value="%H" max="24" class="dialh" /> ';
            format += '<input type="text" value="%M" max="60" class="dialm" /> ';
            format += '<input type="text" value="%S" max="60" class="dials" />';
            if (event.offset.totalDays > 0) {
                format = '<input type="text" value="%-D" max="7" class="diald" /> ' + format;
            }
            format = '<p>' + yiiOptions.activityBeginning + '</p>' + format;
            $(this).html(event.strftime(format));
            knobify();
        }).on('finish.countdown', function(event) {
            if(yiiOptions.hayFin && (new Date(yiiOptions.fin) > new Date())) {
                var active = moment.tz(yiiOptions.fin, moment.tz.guess());
                $('#countdown').countdown(active.toDate(), {})
                .on('update.countdown', function(event) {
                    var format = '<input type="text" value="%H" max="24" class="dialh" /> ';
                    format += '<input type="text" value="%M" max="60" class="dialm" /> ';
                    format += '<input type="text" value="%S" max="60" class="dials" />';
                    if (event.offset.totalDays > 0) {
                        format = '<input type="text" value="%-D" max="7" class="diald" /> ' + format;
                    }
                    format = '<p>' + yiiOptions.activityActive + '</p>' + format;
                    $(this).html(event.strftime(format));
                    knobify();
                }).on('finish.countdown', function(event) {
                    $(this).html(yiiOptions.nofinish);
                });
            } else {
                if(!yiiOptions.hayFin) {
                    $(this).html(yiiOptions.finish);
                } else {
                    $(this).html(yiiOptions.nofinish);
                }
            }
        });

    $('#countdown-abs').countdown(begin.toDate(), {})
        .on('update.countdown', function(event) {
            var format = '<div class="absolute-clock" id="hora"><input type="text" value="%H" max="24" class="dialha" /></div>';
            format += '<div class="absolute-clock" id="minuto"><input type="text" value="%M" max="60" class="dialma"></div>';
            format += '<div class="absolute-clock" id="segundo"><input type="text" value="%S" max="60" class="dialsa" /></div>';
            if (event.offset.totalDays > 0) {
                format = '<div class="absolute-clock" id="dia"><input type="text" value="%-D" max="7" class="dialda" /></div>' + format;
            }
            format = '<p>' + yiiOptions.activityBeginning + '</p>' + '<h2>%D ' + yiiOptions.day + '%!D %H : %M : %S</h2>' + format;
            $(this).html(event.strftime(format));
            knobifyAbs();
        }).on('finish.countdown', function(event) {
            if(yiiOptions.hayFin && (new Date(yiiOptions.fin) > new Date())) {
                var active = moment.tz(yiiOptions.fin, moment.tz.guess());
                $('#countdown-abs').countdown(active.toDate(), {})
                .on('update.countdown', function(event) {
                    var format = '<div class="absolute-clock" id="hora"><input type="text" value="%H" max="24" class="dialha" /></div>';
                    format += '<div class="absolute-clock" id="minuto"><input type="text" value="%M" max="60" class="dialma"></div>';
                    format += '<div class="absolute-clock" id="segundo"><input type="text" value="%S" max="60" class="dialsa" /></div>';
                    if (event.offset.totalDays > 0) {
                        format = '<div class="absolute-clock" id="dia"><input type="text" value="%-D" max="7" class="dialda" /></div>' + format;
                    }
                    format = '<p>' + yiiOptions.activityActive + '</p>' + '<h2>%D ' + yiiOptions.day + '%!D %H : %M : %S</h2>' + format;
                    $(this).html(event.strftime(format));
                    knobifyAbs();
                }).on('finish.countdown', function(event) {
                    $(this).html(yiiOptions.nofinish).removeClass('countdown-active');
                });
            } else {
                if(!yiiOptions.hayFin) {
                    $(this).html(yiiOptions.finish);
                } else {
                    $(this).html(yiiOptions.nofinish);
                }
                $(this).removeClass('countdown-active');
            }
        });
}

function knobify(){
    $(".diald").knob({
        'thickness': .3,
        'width': 110,
        'height': 110,
        'max': 365,
        'readOnly': true,
        'fgColor': '#03fff7',
        'bgColor': '#919191',
        'format': function(value) {
            return value + ' d';
        }
    });
    $(".dialh").knob({
        'thickness': .3,
        'width': 110,
        'height': 110,
        'max': 24,
        'readOnly': true,
        'fgColor': '#62ff03',
        'bgColor': '#919191',
        'format': function(value) {
            return value + ' h';
        }
    });
    $(".dialm").knob({
        'thickness': .3,
        'width': 110,
        'height': 110,
        'max': 60,
        'readOnly': true,
        'fgColor': '#ffec03',
        'bgColor': '#919191',
        'format': function(value) {
            return value + ' m';
        }

    });
    $(".dials").knob({
        'thickness': .3,
        'width': 110,
        'height': 110,
        'max': 60,
        'readOnly': true,
        'fgColor': '#d01616',
        'bgColor': '#919191',
        'format': function(value) {
            return value + ' s';
        }
    });
}

function knobifyAbs(){
    $(".dialda").knob({
        'thickness': .17,
        'width': 250,
        'height': 250,
        'max': 365,
        'readOnly': true,
        'fgColor': '#03fff7',
        'bgColor': '#919191',
        'displayInput': false
    });
    $(".dialha").knob({
        'thickness': .21,
        'width': 200,
        'height': 200,
        'max': 24,
        'readOnly': true,
        'fgColor': '#62ff03',
        'bgColor': '#919191',
        'displayInput': false
    });
    $(".dialma").knob({
        'thickness': .28,
        'width': 150,
        'height': 150,
        'max': 60,
        'readOnly': true,
        'fgColor': '#ffec03',
        'bgColor': '#919191',
        'displayInput': false

    });
    $(".dialsa").knob({
        'thickness': .3,
        'width': 100,
        'height': 100,
        'max': 60,
        'readOnly': true,
        'fgColor': '#d01616',
        'bgColor': '#919191',
        'displayInput': false
    });
}

$(document).on('ready', function(e) {
    var datetime = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1]) \d{2}:\d{2}$/;

    $('#event-fin').on('change blur', function (e) {
        if (datetime.test($('#event-fin').val())) {
            if (new Date($('#event-inicio').val()) > new Date($('#event-fin').val())) {
                $('.field-event-fin').addClass('has-error');
                $('.field-event-fin .help-block').text('La fecha de fin debe ser posterior a la de inicio.');
            } else {
                $('.field-event-fin').removeClass('has-error');
                $('.field-event-fin').addClass('has-success');
                $('.field-event-fin .help-block').text('');
            }
        } else {
            $('.field-event-fin').removeClass('has-success');
            $('.field-event-fin').addClass('has-error');
            $('.field-event-fin .help-block').text('La fecha no es válida.');
        }
    });

    $('#event-inicio').on('change blur afterValidateAttribute', function (e) {
        if (datetime.test($('#event-inicio').val())) {
            if (new Date($('#event-inicio').val()) > new Date($('#event-fin').val())) {
                $('.field-event-fin').addClass('has-error');
                $('.field-event-fin .help-block').text('La fecha de fin debe ser posterior a la de inicio.');
            } else {
                $('.field-event-inicio').removeClass('has-error');
                $('.field-event-inicio').addClass('has-success');
                $('.field-event-inicio .help-block').text('');
            }
        } else {
            $('.field-event-inicio').removeClass('has-success');
            $('.field-event-inicio').addClass('has-error');
            $('.field-event-inicio .help-block').text('La fecha no es válida.');
        }
    });

    $('#event-group-form').on('submit', function (e) {
        return !$('.field-event-fin').hasClass('has-error');
    });
});
