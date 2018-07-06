/**
 * Created by krong on 26.06.2018.
 */

$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    else {
        return decodeURI(results[1]) || 0;
    }
};

function addError(elem) {
    elem.addClass('error');
    elem.removeClass('ok');
}

function removeError(elem) {
    elem.addClass('ok');
    elem.removeClass('error');
}

function validateField(form, field, value) {
    var input;

    form.children('.input-field').each(function () {
        if ($(this).children('input').attr("name") == field) {
            input = $(this);
        }
    });

    if (value == 'wrong') {
        addError(input);
    } else {
        removeError(input);
    }
}

$.validator = {
    validateName: function (input) {
        if (input.val() == '') {
            addError(input.parent());
        } else {
            removeError(input.parent());
        }
    },
    validateEmail: function (input) {
        var pattern = new RegExp("^[a-z0-9._-]+@[a-z0-9-]+.[a-z]{2,6}$");
        if (pattern.test(input.val())) {
            removeError(input.parent());
        } else {
            addError(input.parent());
        }
    },
    validatePhone: function (input) {
        var pattern1 = new RegExp("^[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$");
        var pattern2 = new RegExp("^7[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$");
        var pattern3 = new RegExp("^8[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$");

        var value = input.val();

        if (value.charAt(0) == '+') {
            value = value.substr(1);
        }

        if (
            pattern1.test(value) ||
            pattern2.test(value) ||
            pattern3.test(value)
        ) {
            removeError(input.parent());
        } else {
            addError(input.parent());
        }
    },
    validate: function (input) {
        if (input.attr('name') == 'name') {
            $.validator.validateName(input);
        }
        if (input.attr('name') == 'email') {
            $.validator.validateEmail(input);
        }
        if (input.attr('name') == 'phone') {
            $.validator.validatePhone(input);
        }
    },
    checkErrors: function (input) {
        var form = input.parent().parent();
        var button = form.children('.send-button');

        if (form.children('.input-field').hasClass("error")) {
            return false
        } else {
            return true
        }

    }
};

$(document).ready(function () {

    $('.cform').on('input keydown change', function () {
        var input = $(this).children('.input-field').children('input');
        var emptyFields, checkbox, checked;

        $(this).each(function () {
            emptyFields = $(this).children('.input-field').find('input').length;
        });

        input.each(function () {
            if ($(this).prop('type') == 'checkbox') {
                checkbox = $(this);
            } else {
                checkbox = false;
            }

            if ($(this).val() != '') {
                emptyFields = emptyFields - 1;
            }
        });

        if (checkbox) {
            checked = checkbox.prop('checked');
        } else {
            checked = true;
        }

        if (emptyFields == 0 && $.validator.checkErrors(input) && checked) {
            $(this).children('.send-button').prop('disabled', false);
        } else {
            $(this).children('.send-button').prop('disabled', true);
        }
    });

    $('input').on('input keyup keydown change', function () {
        $.validator.validate($(this));
    });

    $('.cform').submit(function () {
        event.preventDefault(); //this will prevent the default submit

        // var action = '';
        var action = 'zsend/';

        var form = $(this);
        var formData = form.serialize();

        var urlSource = '&utmSource=' + $.urlParam('utm_source');
        var urlMedium = '&utmMedium=' + $.urlParam('utm_medium');
        var urlCampaign = '&utmCampaign=' + $.urlParam('utm_campaign');
        var urlContent = '&utmContent=' + $.urlParam('utm_content');
        var urlRerm = '&utmTerm=' + $.urlParam('utm_term');

        var urlData = urlSource + urlMedium + urlCampaign + urlContent + urlRerm;

        var fields = [];

        $(this).children('.input-field').children('input').each(function () {
            fields.push($(this).prop('name'));
        });


        $.ajax({
            url: action + 'validate.php',
            type: 'post',
            data: {
                formData: formData,
                urlData: urlData,
                fields: fields,
            },
            dataType: 'json',
            beforeSend: function (xhr) {

            },
            success: function (res) {
                if (res.answer != 'validate success') {
                    var responseHtml = $('.here');
                    responseHtml.html('');
                    responseHtml.fadeIn(100);
                    responseHtml.append('<ul>');
                    for (var key in res) {
                        if (res[key] == 'wrong') {
                            responseHtml.append('<li>' + key + ': ' + res[key] + '</li>');
                            console.log(key + ': ' + res[key]);
                        }
                    }
                    responseHtml.append('</ul>');

                    validateField(form, 'phone', res.phone);
                    validateField(form, 'email', res.email);
                    validateField(form, 'name', res.name);
                    validateField(form, 'agreed', res.agreed);
                } else {
                    console.log('Validate success!');
                    $.ajax({
                        url: action + 'send.php',
                        type: 'post',
                        data: {
                            formData: res,
                            urlData: urlData,
                        },
                        success: function (res) {
                            if (res == 'send success') {
                                window.location.replace("tnx.html");
                                console.log('Mail successful sended!');
                            }
                        },
                        error: function () {
                            console.log('Error to send mail!');
                        }
                    });

                }
            },
            error: function () {
                console.log('Error to validate data!');
            }
        });
        // return false;
    });


});