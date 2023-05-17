"use strict";
$(function () {
    productVerify();

    // Get and check status
    setStatusOnlineNotifications();

    setInterval(function(){
        setStatusOnlineNotifications();
    }, 6000);

    $(document).on('click', 'button.close', function () {
        sendMarkRequest($(this).data('id'));
    });

    $(document).on('click', '#purchase', function (){
        Swal.fire({
            title: 'Purchase Code',
            input: 'text',
            inputPlaceholder: '3756623c-5971-17de-7c2d-1cbec0d86a5e',
            html: 'Please fill the purchase code.<br>How to find the purchase code? <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">here</a>',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Close',
            onOpen: () => Swal.getConfirmButton().focus()
        }).then((result) => {
            if (result.value) {
                $('.reload').removeClass('hide');

                $.ajax({
                    url: baseUrl + '/api/helper/'+ $('.swal2-input').val(),
                    type: 'get',
                    success: function (data) {
                        $('.reload').addClass('hide');

                        if (data == 'Success') {
                            $('.ml-2.mb-1.close').trigger('click');

                            Swal.fire(
                                'Successful!',
                                'Thank you for buying our product.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error! ',
                                data,
                                'error'
                            );
                        }
                    }
                });
            }
        })
    });

    $(document).on('click', '#reinput', function (){
        Swal.fire({
            title: 'Reinput Purchase Code',
            input: 'text',
            inputPlaceholder: '3756623c-5971-17de-7c2d-1cbec0d86a5e',
            html: 'Please fill the purchase code.<br>How to find the purchase code? <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">here</a>',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Close',
            onOpen: () => Swal.getConfirmButton().focus()
        }).then((result) => {
            if (result.value) {
                $('.reload').removeClass('hide');
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                var postFormData = {
                    '_token' : csrf,
                }
                $.ajax({
                    url: baseUrl + '/reinputkey/index/'+ $('.swal2-input').val(),
                    type: 'post',
                    data: postFormData,
                    success: function (data) {
                        $('.reload').addClass('hide');

                        console.log(data);

                        if (data == 'Success') {
                            $('.ml-2.mb-1.close').trigger('click');

                            Swal.fire(
                                'Successful!',
                                'Thank you.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error! ',
                                data,
                                'error'
                            );
                        }
                    }
                });
            }
        })
    });
});

// Set notification to all user
function setStatusOnlineNotifications() {
    var data = {
        '_token': $('#csrf-token-meta')[0].content,
    };

    $.ajax({
        url: baseUrl + '/notifications/getStatusOnline',
        type: 'post',
        data:data,
        dataType: 'json',
        success: function (dataResponse) {
            var message;
            var classToasts;
            for(var i=0;i<dataResponse.length;i++){

                // Add status string
                if(dataResponse[i].data.status == 0){
                    classToasts = 'bg-info ' ;
                    message = 'Info! user <b>' + dataResponse[i].data.name + '</b> is currently <b>Offline</b>.';
                }else if(dataResponse[i].data.status == 1){
                    classToasts = 'bg-info ' ;
                    message = 'Info! user <b>' + dataResponse[i].data.name + '</b> is currently <b>Online</b>.';
                }else{
                    classToasts = 'bg-danger ' ;
                    message = 'Info! New user registration, user name <b>' + dataResponse[i].data.name + '</b>.';
                }

                // Check is there any div with uniq id
                if($('.' + dataResponse[i].id)[0]){
                    // exist
                }else{
                    $(document).Toasts('create', {
                        class: classToasts + dataResponse[i].id,
                        title: 'New Notification!',
                        subtitle: dateFormatter(dataResponse[i].created_at),
                        body: message,
                        icon: 'fas fa-envelope fa-lg',
                    });

                    $('.' + dataResponse[i].id).find('button').attr('data-id', dataResponse[i].id);
                }

            }
        }
    });
}

function dateFormatter(theDate) {
    var date = new Date(theDate.replace(/\s/, 'T'));
    var year = date.getFullYear().toString();
    var month = (date.getMonth() + 101).toString().substring(1);
    var day = (date.getDate() + 100).toString().substring(1);
    return year + "-" + month + "-" + day;
}

// Function mark as read for notifications
function sendMarkRequest(idNotif) {
    var data = {
        'id' : idNotif,
        '_token': $('#csrf-token-meta')[0].content,
    };

    $.ajax({
        url: baseUrl + '/notifications/markNotification',
        type: 'post',
        data: data
    });
}

function productVerify() {
    $.ajax({
        url: baseUrl + '/checkProductVerify',
        type: 'get',
        success: function (dataResponse) {
            if(dataResponse == 0){
                $(document).Toasts('create', {
                    class: "bg-danger",
                    title: 'Fill the purchase code!',
                    subtitle: GetTodayDate(),
                    body: "Please fill the purchase code. Click <span id='purchase'><u>here to fill the purchase code</u></span>. You have <b>7</b> days to verify the purchase code.",
                    icon: 'fas fa-exclamation-triangle fa-lg',
                });
            }

            if(dataResponse == 5){
                $(document).Toasts('create', {
                    class: "bg-warning",
                    title: 'Refill your purchase code!',
                    subtitle: GetTodayDate(),
                    body: "Please refill your purchase code. Click <span id='purchase'><u>here to fill the purchase code</u></span>. You have <b>7</b> days to verify the purchase code.",
                    icon: 'fas fa-exclamation-triangle fa-lg',
                });
            }

            if(dataResponse == 9){
                $(document).Toasts('create', {
                    class: "bg-warning",
                    title: 'Duplicate Purchase Code!',
                    subtitle: GetTodayDate(),
                    body: "The purchase code that you entered is already owned. If you feel the purchase code belongs to you, and you want to change it, please fill your purchase code. Click <span id='reinput'><u>here to fill the purchase code</u></span>. You have <b>7</b> days to verify the purchase code.",
                    icon: 'fas fa-exclamation-triangle fa-lg',
                });
            }
        }
    });
}

function GetTodayDate() {
    var tdate = new Date();
    var dd = tdate.getDate(); //yields day
    var MM = tdate.getMonth(); //yields month
    var yyyy = tdate.getFullYear(); //yields year
    var currentDate= dd + "-" +( MM+1) + "-" + yyyy;
    return currentDate;
}
