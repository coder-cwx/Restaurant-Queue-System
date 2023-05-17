// Param check user online or not
var isOnline;
$(function () {

    // Checking if twilio is active
    if(checkPhoneInput()){
        var telInput = document.querySelector("#phone");

        var iti = window.intlTelInput(telInput, {
            // any initialisation options go here
            initialCountry: "auto",
            geoIpLookup: function(success, failure) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    success(countryCode);
                });
            },
            utilsScript:"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js",
            separateDialCode:true,
            placeholderNumberType:"MOBILE",
        });
    }

    // Check status online
    checkStatusOnlineUer();

    // Check every time status online
    setInterval(function(){
        checkStatusOnlineUer();
    }, 4000);

    $('#id_branch').on('change', function () {
        var value = this.value;
        getDepartmentName(value);
    });

    // Trigger click get it button
    $(document).on('click', '#get-it', function (e) {
        e.preventDefault();
        if(isOnline){
            if ($('#id_department').val() != '') {

                // Checking if twilio is active
                if(checkPhoneInput()){
                    var number = iti.getNumber();
                    $('#phone').val(number);
                }

                var emailClient = $('#email').val();
                var phoneClient = checkPhoneInput() ? $('#phone').val() : '';

                // Param with value
                var data = {
                    'phone': phoneClient,
                    'id_branch': $('#id_branch').val(),
                    'id_department': $('#id_department').val(),
                    'email': emailClient,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                };

                $.ajax({
                    url: baseUrl + '/token/getToken',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                        var url = baseUrl + "/token/number/" + data.token;

                        if(phoneClient != ''){
                            sendTokenClient(phoneClient, url);
                        }else{
                            sendEmailClient(emailClient, url);
                        }
                    }
                });
            } else {
                // Error when select is empty
                Swal.fire(
                    message_get_it_callback.title,
                    message_get_it_callback.message,
                    'error'
                );
            }
        }
    });
});

// Function checking is phone input exist
function checkPhoneInput(){
    return $('#phone').length;
}

// Send mail to client
function sendEmailClient(email, url) {
    if(email != ''){
        $('.reload').removeClass('hide');

        // Param with value
        var dataClient = {
            'email': email,
            'url': url,
            '_token': $('meta[name="csrf-token"]').attr('content'),
        };

        $.ajax({
            url: baseUrl + '/token/sentMail',
            type: 'post',
            data: dataClient,
            success: function (responseDataClient) {
                $('.reload').addClass('hide');
                if (responseDataClient == 'success') {
                    swalPopUp(url, 'isset');
                }
            }
        });
    }else{
        swalPopUp(url, 'notset');
    }
}

// Send token to client
function sendTokenClient(phone, url) {
    if(phone != ''){
        $('.reload').removeClass('hide');

        // Param with value
        var dataClient = {
            'phone': phone,
            'url': url,
            '_token': $('meta[name="csrf-token"]').attr('content'),
        };

        $.ajax({
            url: baseUrl + '/token/sendMessage',
            type: 'post',
            data: dataClient,
            success: function (responseDataClient) {
                $('.reload').addClass('hide');
                if (responseDataClient == 'success') {
                    swalPopUp(url, 'isset');
                }else{
                    Swal.fire(
                        message_error_sms_callback.title,
                        message_error_sms_callback.message,
                        'error'
                    )
                }
            }
        });
    }else{
        swalPopUp(url, 'notset');
    }
}

function getDepartmentName(branch_id) {
    var data = {
        'id_branch': branch_id,
    }

    $.ajax({
        url: baseUrl + '/api/getDepartmentByBranch',
        data: data,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            // Check is there any user online
            // If yes will return the department
            if(!jQuery.isEmptyObject(data)){
                var appendData = '';
                appendData += '<option value=""> '+ select_department +' </option>';

                $.each( data, function ( key, value ) {
                    appendData += '<option value="'+ value.id +'">'+ value.name +'</option>'
                });

                $('#id_department').html("");
                $('#id_department').append(appendData);
            }else{
                // Reset the input
                resetInput();

                // Show popup when all user offline
                Swal.fire(
                    choose_department_callback.title,
                    choose_department_callback.message,
                    'info'
                );
            }
        }
    });
}

// Check is there any user online
// if all user offline will close the registration
function checkStatusOnlineUer() {
    $.ajax({
        url: baseUrl + '/api/status-online/checkUserOnline',
        type: 'post',
        dataType: 'json',
        success: function (data) {
            if (data == 0) {
                isOnline = false; // all user offline
                $('#row-token').hide();
                $('#row-offline').show();
            } else {
                isOnline = true; // all user online
                $('#row-token').show();
                $('#row-offline').hide();
            }
        }
    });
}

// Function show sweetAlert
function swalPopUp(url, isSet) {
    var qrImage = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='+ url +'&choe=UTF-8';

    var message = '';
    if (isSet === "isset"){
        message += success_token_callback.message;
    }
    message += success_token_callback.more_message;

    Swal.fire({
        title: success_token_callback.title,
        html: message + '<br>' +
            '<i>'+success_token_callback.close_popup+'</i>',
        imageUrl: qrImage,
        imageWidth: 200,
        imageHeight: 200,
        timer: 60000,
        imageAlt: 'Token Number',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: success_token_callback.button_get_token,
        cancelButtonText: success_token_callback.close_button,
    }).then((result) => {
        if (result.value) {
            location.href = url;
        }
    });

    // Reset the input
    resetInput()
}

// Function reset the input
function resetInput() {
    // Reset the input
    $('#id_branch').val('');
    $('#id_department').val('');
    $('#email').val('');
    $('#phone').val('');
}
