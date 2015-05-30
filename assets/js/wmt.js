// this doc is ready
$(document).ready(function() {

    // the login form ^_^
    $("#projects-login-submit").click(function(event) {
        event.preventDefault();
        var theForm = $("#projects-login-form");
        var password = $("#projects-login-form input[name=password]");
        password.val(calcMD5(password.val()));
        theForm.submit();
    });

    /*
     * Daily activity
     * */
    // changes the job activity
    $("form.daily-activity select#job").change(function(event) {
        var job = $("form.daily-activity #job");
        var start = $("form.daily-activity #start_time button");
        if ($.isNumeric(job.val())) {
            start.removeClass('disabled');
        }
        else
        {
            start.addClass('disabled');
        }
    });

    // clicks the start button
    $("form.daily-activity #start_time button").click(function(event) {
        $("form.daily-activity select#job").attr('disabled', true); // disable job selection

        window.onbeforeunload = function() { // alert the user for unfinished work
            return 'Trebuie sa incheiati activitatea inainte sa parasiti pagina!';
        }

        get_start_time();   // get the time from the server with ajax
    });

    // clicks the stop button
    $("form.daily-activity #end_time button").click(function(event) {
        get_finish_time();   // get the time from the server with ajax
    });

    // input the no of bills
    var bills_no = $("form.daily-activity #bills_no");
    bills_no.focusout(function() {
        if (bills_no.val()) {
            $("form.daily-activity button[type='submit']").removeClass('disabled');
        }
        else
        {
            $("form.daily-activity button[type='submit']").addClass('disabled');
        }
    });

    // submit the form
    $("form.daily-activity button[type='submit']").click(function(event) {
        window.onbeforeunload = null;
    });

    /*
     * Reports
     * */
    // report/user
    var report_date_from = $('form.report-form #date_from');
    var report_date_to = $('form.report-form #date_to');
    var month_year = $('form.report-form #month_year');
    var year = $('form.report-form #year');

    //filters that set the others to default when set
    // from date
    report_date_from.change(function(event) {
        month_year.val('');
        year.val('');

    });

    // to dateaa
    report_date_to.change(function(event) {
        month_year.val('');
        year.val('');
    });

    // month-year
    month_year.change(function(event) {
        report_date_from.val('');
        report_date_to.val('');
        year.val('');
    });

    // year
    year.change(function(event) {
        report_date_from.val('');
        report_date_to.val('');
        month_year.val('');
    });

    /*
     * Reports global
     * 
     * Graphic report by user
     * */
    var gr_user = $('.report-form.global-report select#user');
    var gr_date_from = $('.report-form.global-report input#date_from');
    var gr_date_to = $('.report-form.global-report input#date_to');
    var gr_month_year = $('.report-form.global-report input#month_year');
    var gr_year = $('.report-form.global-report input#year');
    var job_wrapper = $(".report-form.global-report .gru-job-wrapper");
    var job_select = $(".report-form.global-report .gru-job-wrapper select#job");
    var gr_submit_btn = $('.report-form.global-report button[type="submit"]');
    gr_user.change(function(event) {
        job_wrapper.hide();
        gr_submit_btn.addClass('disabled');
        if ($.isNumeric(gr_user.val())) {
            // hide graph if
            var gu_graph_wrapper = $("#gu-graph-wrapper");
            if (gu_graph_wrapper.length != 0) {
                gu_graph_wrapper.fadeOut(600);
                gu_graph_wrapper.remove();
            }

            if (gr_date_from.val() && gr_date_to.val()) {
                load_jobs_4gu();
            }
            else if (gr_month_year.val())
            {
                load_jobs_4gu();
            }
            else if (gr_year.val())
            {
                load_jobs_4gu();
            }
        }
    });

    // clear other periods on change
    gr_date_from.change(function(event) {
        job_wrapper.hide();
        gr_submit_btn.addClass('disabled');
        if ($.isNumeric(gr_user.val()) && gr_date_to.val()) {
            load_jobs_4gu();
        }
    });
    gr_date_to.change(function(event) {
        job_wrapper.hide();
        gr_submit_btn.addClass('disabled');
        if ($.isNumeric(gr_user.val()) && gr_date_from.val()) {
            load_jobs_4gu();
        }
    });
    gr_month_year.change(function(event) {
        job_wrapper.hide();
        gr_submit_btn.addClass('disabled');
        if ($.isNumeric(gr_user.val())) {
            load_jobs_4gu();
        }
    });
    gr_year.change(function(event) {
        job_wrapper.hide();
        gr_submit_btn.addClass('disabled');
        if ($.isNumeric(gr_user.val())) {
            load_jobs_4gu();
        }
    });

    job_select.change(function(event) {
        if ($.isNumeric(job_select.val())) {
            gr_submit_btn.removeClass('disabled');
        }
        else
        {
            gr_submit_btn.addClass('disabled');
        }
    });

    // call datepicker on input
    $("#work_date").datepicker({
        "dateFormat": "dd.mm.yy",
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#date_from").datepicker({
        "dateFormat": "dd.mm.yy",
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#date_to").datepicker({
        "dateFormat": "dd.mm.yy",
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#month").datepicker({
        "dateFormat": "mm.yy",
        "changeDay": false,
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#month_year").datepicker({
        "dateFormat": "mm.yy",
        "changeDay": false,
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#year").datepicker({
        "dateFormat": "yy",
        "changeDay": false,
        "changeMonth": false,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#birth_date").datepicker({
        "dateFormat": "dd.mm.yy",
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
    $("#employment_date").datepicker({
        "dateFormat": "dd.mm.yy",
        "changeMonth": true,
        "changeYear": true,
        yearRange: "-100:+0"
    }
    );
}); // ends doc.ready
/* /> ends doc.ready */


/*
 * time format hh:mm
 * */
function compute_time_difference() {
    var start = $('.post-factor-form #start_time').val();
    var finish = $('.post-factor-form #finish_time').val();
    var total_time = $('.post-factor-form #total_time');
    var total_time_hidden = $('.post-factor-form input[name=total_time]');

    if (start && finish) {
        var start_arr = start.split(":");
        var start_hh = parseInt(start_arr[0]);
        var start_mm = parseInt(start_arr[1]);

        var finish_arr = finish.split(":");
        var finish_hh = parseInt(finish_arr[0]);
        var finish_mm = parseInt(finish_arr[1]);

        var total = (finish_hh - start_hh) * 60 + (finish_mm - start_mm);

        if (total > 0) {
            total_time.val(total);
            total_time_hidden.val(total);
            $('.post-factor-form .total-time-wrapper').show();
        }
        else
        {
            total_time.val('');
            total_time_hidden.val('');
            $('.post-factor-form .total-time-wrapper').hide();
        }
    }
    else
    {
        total_time.val('');
        total_time_hidden.val('');
        $('.post-factor-form .total-time-wrapper').hide();
    }
}

/*
 * Loads jobs for global user reports based on user and date selected by ajax
 * */
function load_jobs_4gu() {

    // youtube loading
    var animEl = $('#la-anim-1');

    var job_wrapper = $(".report-form.global-report .gru-job-wrapper");
    var job_select = $(".report-form.global-report .gru-job-wrapper select#job");
    job_select.html('');

    var user = $('.report-form.global-report select#user');
    var date_from = $('.report-form.global-report input#date_from');
    var date_to = $('.report-form.global-report input#date_to');
    var month_year = $('.report-form.global-report input#month_year');
    var year = $('.report-form.global-report input#year');

    var data = '';

    if (date_from.val() && date_to.val()) {
        data = 'date_from=' + date_from.val() + '&date_to=' + date_to.val();
    }
    else if (month_year.val())
    {
        data = 'month_year=' + month_year.val();
    }
    else if (year.val())
    {
        data = 'year=' + year.val();
    }

    if ($.isNumeric(user.val())) {
        data += '&user=' + user.val();
    }

    if (data != '') {
        var url = '/report/load-jobs-gu';
        var message_fail = 'Eroare! Serverul nu a returnat job-urile.';

        $.ajax({
            type: "POST",
            url: url,
            async: true,
            dataType: "json",
            data: data,
            beforeSend: function(x) {
                // do stuff before the request is sent
                animEl.addClass('la-animate');
            },
            success: function(data, textStatus, jqXHR) {
                if (data.status == 1)
                {
                    job_select.append("<option>Selectare job</option>");
                    $.each(data.jobs, function(index, value) {
                        job_select.append('<option value="' + index + '">' + value + '</option>');
                    });
                    job_select.append('<option value="-1">Toate joburile</option>');

                    job_wrapper.fadeIn(1000);
                }
                else
                {
                    alert(message_fail);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(message_fail);
            },
            complete: function(jqXHR, textStatus) {
                // do stuff when the request is finished
                setTimeout(function() {
                    animEl.removeClass('la-animate');
                }, 2000);
            }
        }); // the end
    }
}

function send_no_of_errors(user_id, job_id, job_date) {
    var no_of_errors = $('.errors-actions input[data-rel="' + user_id + '"]').val();
    if ($.isNumeric(no_of_errors) && $.isNumeric(user_id) && $.isNumeric(job_id)) {
        var data = 'no_errors=' + no_of_errors + '&user_id=' + user_id + '&job_id=' + job_id + '&job_date=' + job_date;

        var url = '/update_errors';
        var message_fail = 'Numarul de erori nu a putut fi salvat in baza de date!';
        $.ajax({
            type: "POST",
            url: url,
            async: true,
            dataType: "json",
            data: data,
            beforeSend: function(x) {
                // do stuff before the request is sent
            },
            success: function(data, textStatus, jqXHR) {
                if (data.status == 1)
                {
                    var errors = parseInt($('.td-no-of-errors' + user_id).html());
                    errors += parseInt(no_of_errors);
                    $('.td-no-of-errors' + user_id).html(errors);
                    $('.errors-actions input[data-rel="' + user_id + '"]').val('');
                }
                else
                {
                    alert(message_fail);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(message_fail);
            },
            complete: function(jqXHR, textStatus) {
                // do stuff when the request is finished
            }
        }); // the end
    }
}



/* Returns the server time
 *
 * /get_start_time
 *
 * /get_finish_time
 * */
function get_start_time() {
    var url = '/get_start_time';
    var message_fail = 'Serverul nu a returnat ora inceperii!';
    $.ajax({
        type: "POST",
        url: url,
        async: true,
        dataType: "json",
        beforeSend: function(x) {
            // do stuff before the request is sent
        },
        success: function(data, textStatus, jqXHR) {
            if (data.status == 1) {
                var date = new Date(data.time * 1000); // multiplied by 1000 so that the argument is in milliseconds, not seconds
                var hours = date.getHours(); // hours part from the timestamp
                var minutes = date.getMinutes(); // minutes part from the timestamp
                var seconds = date.getSeconds(); // seconds part from the timestamp

                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                // will display time in 10:30:23 format
                var formattedTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                var start_time_wrapper = $("form.daily-activity #start_time");
                $("form.daily-activity #start_time button").hide();
                start_time_wrapper.html('<input type="text" class="form-control" value="' + formattedTime + '" disabled>');

                $("form.daily-activity #end_time button").removeClass('disabled');

                $('.total_time_wrapper #total_time').html('<input type="hidden" value="' + data.time + '">');

                var job_id = $("form.daily-activity select#job").val();
                $('form.daily-activity div.hidden-inputs').append('<input type="hidden" name="job_id" value="' + job_id + '">');
                $('form.daily-activity div.hidden-inputs').append('<input type="hidden" name="start_time" value="' + data.time + '">');
            }
            else{
                alert(message_fail);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(message_fail);
        },
        complete: function(jqXHR, textStatus) {
            // do stuff when the request is finished
        }
    }); // the end
}

/* Get the time on the server and a difference in minutes of start - finish time.
 *
 * /get_finish_time
 * */
function get_finish_time() {
    var url = '/get_finish_time';
    var data = 'start_time=' + $('.total_time_wrapper #total_time input').val();
    var message_fail = 'Serverul nu a returnat ora incheierii!';
    $.ajax({
        type: "POST",
        url: url,
        async: true,
        data: data,
        dataType: "json",
        beforeSend: function(x) {
            // do stuff before the request is sent
        },
        success: function(data, textStatus, jqXHR) {
            if (data.status == 1) {
                var date = new Date(data.finish_time * 1000); // multiplied by 1000 so that the argument is in milliseconds, not seconds
                var hours = date.getHours(); // hours part from the timestamp
                var minutes = date.getMinutes(); // minutes part from the timestamp
                var seconds = date.getSeconds(); // seconds part from the timestamp

                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                // will display time in 10:30:23 format
                var formattedTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                var end_time_wrapper = $("form.daily-activity #end_time");
                $("form.daily-activity #end_time button").hide();
                end_time_wrapper.html('<input type="text" class="form-control" value="' + formattedTime + '" disabled>');

                $('form.daily-activity .total_time_wrapper #total_time').html('<input type="text" class="form-control" value="' + Math.round(data.difference / 60) + ' min" disabled><input type="hidden" name="total_time" value="' + Math.round(data.difference / 60) + '">');
                $('form.daily-activity .total_time_wrapper').show();

                $("form.daily-activity #bills_no").attr('disabled', false);
                $("form.daily-activity #obs").attr('disabled', false);

                $('form.daily-activity div.hidden-inputs').append('<input type="hidden" name="finish_time" value="' + data.finish_time + '">');
            }
            else {
                alert(message_fail);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(message_fail);
        },
        complete: function(jqXHR, textStatus) {
            // do stuff when the request is finished
        }
    }); // the end
}

/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Copyright (C) Paul Johnston 1999 - 2000.
 * Updated by Greg Holt 2000 - 2001.
 * See http://pajhome.org.uk/site/legal.html for details.
 */
/*
 * Convert a 32-bit number to a hex string with ls-byte first
 */
var hex_chr = "0123456789abcdef";
function rhex(num){
    str = "";
    for (j = 0; j <= 3; j++)
        str += hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
                hex_chr.charAt((num >> (j * 8)) & 0x0F);
    return str;
}

/*
 * Convert a string to a sequence of 16-word blocks, stored as an array.
 * Append padding bits and the length, as described in the MD5 standard.
 */
function str2blks_MD5(str){
    nblk = ((str.length + 8) >> 6) + 1;
    blks = new Array(nblk * 16);
    for (i = 0; i < nblk * 16; i++)
        blks[i] = 0;
    for (i = 0; i < str.length; i++)
        blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
    blks[i >> 2] |= 0x80 << ((i % 4) * 8);
    blks[nblk * 16 - 2] = str.length * 8;
    return blks;
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */
function add(x, y)
{
    var lsw = (x & 0xFFFF) + (y & 0xFFFF);
    var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
    return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt)
{
    return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * These functions implement the basic operation for each round of the
 * algorithm.
 */
function cmn(q, a, b, x, s, t)
{
    return add(rol(add(add(a, q), add(x, t)), s), b);
}
function ff(a, b, c, d, x, s, t)
{
    return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function gg(a, b, c, d, x, s, t)
{
    return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function hh(a, b, c, d, x, s, t)
{
    return cmn(b ^ c ^ d, a, b, x, s, t);
}
function ii(a, b, c, d, x, s, t)
{
    return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Take a string and return the hex representation of its MD5.
 */
function calcMD5(str) {
    x = str2blks_MD5(str);
    a = 1732584193;
    b = -271733879;
    c = -1732584194;
    d = 271733878;

    for (i = 0; i < x.length; i += 16) {
        olda = a;
        oldb = b;
        oldc = c;
        oldd = d;

        a = ff(a, b, c, d, x[i + 0], 7, -680876936);
        d = ff(d, a, b, c, x[i + 1], 12, -389564586);
        c = ff(c, d, a, b, x[i + 2], 17, 606105819);
        b = ff(b, c, d, a, x[i + 3], 22, -1044525330);
        a = ff(a, b, c, d, x[i + 4], 7, -176418897);
        d = ff(d, a, b, c, x[i + 5], 12, 1200080426);
        c = ff(c, d, a, b, x[i + 6], 17, -1473231341);
        b = ff(b, c, d, a, x[i + 7], 22, -45705983);
        a = ff(a, b, c, d, x[i + 8], 7, 1770035416);
        d = ff(d, a, b, c, x[i + 9], 12, -1958414417);
        c = ff(c, d, a, b, x[i + 10], 17, -42063);
        b = ff(b, c, d, a, x[i + 11], 22, -1990404162);
        a = ff(a, b, c, d, x[i + 12], 7, 1804603682);
        d = ff(d, a, b, c, x[i + 13], 12, -40341101);
        c = ff(c, d, a, b, x[i + 14], 17, -1502002290);
        b = ff(b, c, d, a, x[i + 15], 22, 1236535329);

        a = gg(a, b, c, d, x[i + 1], 5, -165796510);
        d = gg(d, a, b, c, x[i + 6], 9, -1069501632);
        c = gg(c, d, a, b, x[i + 11], 14, 643717713);
        b = gg(b, c, d, a, x[i + 0], 20, -373897302);
        a = gg(a, b, c, d, x[i + 5], 5, -701558691);
        d = gg(d, a, b, c, x[i + 10], 9, 38016083);
        c = gg(c, d, a, b, x[i + 15], 14, -660478335);
        b = gg(b, c, d, a, x[i + 4], 20, -405537848);
        a = gg(a, b, c, d, x[i + 9], 5, 568446438);
        d = gg(d, a, b, c, x[i + 14], 9, -1019803690);
        c = gg(c, d, a, b, x[i + 3], 14, -187363961);
        b = gg(b, c, d, a, x[i + 8], 20, 1163531501);
        a = gg(a, b, c, d, x[i + 13], 5, -1444681467);
        d = gg(d, a, b, c, x[i + 2], 9, -51403784);
        c = gg(c, d, a, b, x[i + 7], 14, 1735328473);
        b = gg(b, c, d, a, x[i + 12], 20, -1926607734);

        a = hh(a, b, c, d, x[i + 5], 4, -378558);
        d = hh(d, a, b, c, x[i + 8], 11, -2022574463);
        c = hh(c, d, a, b, x[i + 11], 16, 1839030562);
        b = hh(b, c, d, a, x[i + 14], 23, -35309556);
        a = hh(a, b, c, d, x[i + 1], 4, -1530992060);
        d = hh(d, a, b, c, x[i + 4], 11, 1272893353);
        c = hh(c, d, a, b, x[i + 7], 16, -155497632);
        b = hh(b, c, d, a, x[i + 10], 23, -1094730640);
        a = hh(a, b, c, d, x[i + 13], 4, 681279174);
        d = hh(d, a, b, c, x[i + 0], 11, -358537222);
        c = hh(c, d, a, b, x[i + 3], 16, -722521979);
        b = hh(b, c, d, a, x[i + 6], 23, 76029189);
        a = hh(a, b, c, d, x[i + 9], 4, -640364487);
        d = hh(d, a, b, c, x[i + 12], 11, -421815835);
        c = hh(c, d, a, b, x[i + 15], 16, 530742520);
        b = hh(b, c, d, a, x[i + 2], 23, -995338651);

        a = ii(a, b, c, d, x[i + 0], 6, -198630844);
        d = ii(d, a, b, c, x[i + 7], 10, 1126891415);
        c = ii(c, d, a, b, x[i + 14], 15, -1416354905);
        b = ii(b, c, d, a, x[i + 5], 21, -57434055);
        a = ii(a, b, c, d, x[i + 12], 6, 1700485571);
        d = ii(d, a, b, c, x[i + 3], 10, -1894986606);
        c = ii(c, d, a, b, x[i + 10], 15, -1051523);
        b = ii(b, c, d, a, x[i + 1], 21, -2054922799);
        a = ii(a, b, c, d, x[i + 8], 6, 1873313359);
        d = ii(d, a, b, c, x[i + 15], 10, -30611744);
        c = ii(c, d, a, b, x[i + 6], 15, -1560198380);
        b = ii(b, c, d, a, x[i + 13], 21, 1309151649);
        a = ii(a, b, c, d, x[i + 4], 6, -145523070);
        d = ii(d, a, b, c, x[i + 11], 10, -1120210379);
        c = ii(c, d, a, b, x[i + 2], 15, 718787259);
        b = ii(b, c, d, a, x[i + 9], 21, -343485551);

        a = add(a, olda);
        b = add(b, oldb);
        c = add(c, oldc);
        d = add(d, oldd);
    }
    return rhex(a) + rhex(b) + rhex(c) + rhex(d);
}
// Ends MD5 script