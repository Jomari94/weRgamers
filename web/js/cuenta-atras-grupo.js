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
        }).on('finish.countdown', function(event) {
            if(yiiOptions.hayFin) {
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
                    })
            } else {
                $(this).html(yiiOptions.finish);
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
        }).on('finish.countdown', function(event) {
            if(yiiOptions.hayFin) {
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
                    })
            } else {
                $(this).html(yiiOptions.finish).removeClass('countdown-active');
            }
        });
}
