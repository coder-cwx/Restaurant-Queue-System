"use strict";
$(document).ready(function()
{
    // Get and check status
    setStatusOnlineNotifications();

    var inputOptions = {
        '48': '0',
        '49': '1',
        '50': '2',
        '51': '3',
        '52': '4',
        '53': '5',
        '54': '6',
        '55': '7',
        '56': '8',
        '57': '9',
        '65': 'a',
        '66': 'b',
        '67': 'c',
        '68': 'd',
        '69': 'e',
        '70': 'f',
        '71': 'g',
        '72': 'h',
        '73': 'i',
        '74': 'j',
        '75': 'k',
        '76': 'l',
        '77': 'm',
        '78': 'n',
        '79': 'o',
        '80': 'p',
        '81': 'q',
        '82': 'r',
        '83': 's',
        '84': 't',
        '85': 'u',
        '86': 'v',
        '87': 'w',
        '88': 'x',
        '89': 'y',
        '90': 'z',
    };

    writeTheShortcut(inputOptions);

    $(document).on('click', 'button.close', function()
    {
        sendMarkRequest($(this).data('id'));
    });

    $(document).on('keydown', function(e)
    {
        var userType = e.which;
        keydownShortcut(userType)
    });

    // Call ajax for the first time
    // to check user is online or not
    $.ajax(
    {
        url: baseUrl + '/status/checkStatusOnline',
        type: 'post',
        data:
        {
            'id': user,
            '_token': $('#csrf-token').val()
        },
        dataType: 'json',
        success: function(data)
        {

            if (data.is_online == true)
            {
                $('#is_online').prop('checked', true);
            }
            else
            {
                $('#is_online').prop('checked', false);
            }
        }
    });

    // Click the toggle online or offline
    $('#is_online').on('change', function()
    {

        var isChecked = $(this).is(':checked');
        var selectedData;
        var $switchLabel = $('.switch-label');

        if (isChecked)
        {
            selectedData = $switchLabel.attr('data-on');
        }
        else
        {
            selectedData = $switchLabel.attr('data-off');
        }

        // Change status online or offline
        changeStatusOnline(user, selectedData, branch);
    });

    // Action when click submit for call new token
    $("#shortcut").click(function(e)
    {
        e.preventDefault();
        if ($('#id_department').val() != '' && $('#id_counter').val() != '')
        {

            var idCounterVal = $('#id_counter').val();
            var idDeptVal = $('#id_department').val();
            var deptName = $("#id_department").children("option").filter(":selected").text();

            // Get the name of department, we need split because its join with branch name
            var splitTheDept = deptName.split("/");
            var splitSpace = splitTheDept[0].split(" ");
            if (splitSpace.length > 1)
            {
                var finalDeptName = splitTheDept[0];
            }
            else
            {
                var finalDeptName = splitTheDept[0].replace(/\s/g, '');
            }

            Swal.fire(
            {
                title: 'Keyboard Shortcut',
                input: 'select',
                inputOptions: inputOptions,
                inputPlaceholder: 'Select Shortcut',
                showCancelButton: true,
            }).then((result) =>
            {
                if (result.value)
                {
                    var getValue = result.value;

                    var dataKeyboard = {
                        idCounter: idCounterVal,
                        idDept: idDeptVal,
                        deptName: finalDeptName,
                        shortcut: getValue,
                    };

                    if (typeof(Storage) !== "undefined")
                    {
                        // Store
                        funcLocalStorage('save', 'shortcut', JSON.stringify(dataKeyboard))
                    }
                    else
                    {
                        alert("Sorry, your browser does not support Web Storage...");
                    }

                    $('#id_department').val('');
                    $('#id_counter').val('');

                    writeTheShortcut(inputOptions);

                    // Error when select is empty
                    Swal.fire(
                        'Success!',
                        'You can use "' + inputOptions[getValue] + '" shortcut for calling next token.',
                        'success'
                    );
                }
            })
        }
        else
        {
            // Error when select is empty
            Swal.fire(
                'error',
                'Please choose department and counter to add shortcut.',
                'error'
            );
        }
    });

    // Action when click submit for call new token
    $("#btn-calls-submit").click(function(e)
    {
        e.preventDefault();

        if ($('#id_department').val() != '' && $('#id_counter').val() != '')
        {

            $('.background-calling').show();

            var idCounterVal = $('#id_counter').val();
            var idDeptVal = $('#id_department').val();
            var deptName = $("#id_department").children("option").filter(":selected").text();

            // Get the name of department, we need split because its join with branch name
            var splitTheDept = deptName.split("/");
            var splitSpace = splitTheDept[0].split(" ");
            if (splitSpace.length > 1)
            {
                var finalDeptName = splitTheDept[0];
            }
            else
            {
                var finalDeptName = splitTheDept[0].replace(/\s/g, '');
            }

            // Call function next token
            callNextToken(idDeptVal, idCounterVal, finalDeptName);
        }
        else
        {
            // Error when select is empty
            Swal.fire(
                'error',
                'Please choose department and counter.',
                'error'
            );
        }
    });

    // Ajax get data from database
    setInterval(function()
    {
        var data = {
            'id_branch': branch,
        }

        $.ajax(
        {
            type: "get",
            data: data,
            url: baseUrl + "/api/calls/apiTokenNumber",
            success: function(dataResponse)
            {
                var appendData = '';
                if (dataResponse.length !== 0)
                {
                    var getEmailClient;
                    $.each(dataResponse, function(i, getData)
                    {
                        if (getData.email_client != null)
                        {
                            getEmailClient = '<td><button type="button" id="send" data-email="' + getData.email_client + '" data-id="' + getData.crypt + '" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button></td>';
                        }
                        else
                        {
                            getEmailClient = '<td> No Email Client </td>';
                        }
                        appendData += "<tr>" +
                            "<td>" + getData.branch.name + "</td>" +
                            "<td>" + getData.department.name + "</td>" +
                            "<td>-</td>" +
                            "<td>" + getData.department.letter + leftPad(getData.number, 4) + "</td>" +
                            "<td>Waiting for a queue</td>" +
                            "<td>" + dateFormatter(getData.date) + "</td>" +
                            getEmailClient +
                            "</tr>";
                    });

                    $('#loadCalls').html("");
                    $('#loadCalls').append(appendData);
                }
                else
                {
                    appendData += "<tr><td colspan=\"7\" class=\"text-center\">There is no queue.</td></tr>";

                    $('#loadCalls').html("");
                    $('#loadCalls').append(appendData);
                }
            }
        });
    }, 4000);

    // Ajax get data from database
    setInterval(function()
    {
        var data = {
            'id_branch': branch,
        }

        $.ajax(
        {
            type: "get",
            data: data,
            url: baseUrl + "/api/calls/apiGetHaveCalled",
            success: function(dataResponse)
            {
                var appendData = '';
                if (dataResponse.length !== 0)
                {

                    $.each(dataResponse, function(i, getData)
                    {
                        appendData += "<tr>" +
                            "<td>" + getData.branch.name + "</td>" +
                            "<td>" + getData.department.name + "</td>" +
                            "<td>" + getData.counter.name + "</td>" +
                            "<td>" + getData.department.letter + leftPad(getData.number, 4) + "</td>" +
                            "<td>" + getData.status + "</td>" +
                            "<td>" + dateFormatter(getData.date) + "</td>" +
                            "</tr>";
                    });

                    $('#loadHaveCalled').html("");
                    $('#loadHaveCalled').append(appendData);
                }
                else
                {
                    appendData += "<tr><td colspan=\"6\" class=\"text-center\">There is no data.</td></tr>";

                    $('#loadHaveCalled').html("");
                    $('#loadHaveCalled').append(appendData);
                }
            }
        });
    }, 5000);

    $(document).on('click', '#send', function()
    {
        $('.email-input .swal2-content input.swal2-input').prop('disabled', true);
        var url = baseUrl + "/token/number/" + $(this).attr("data-id");
        var emailClient = $(this).attr("data-email");
        Swal.fire(
        {
            title: 'Send token',
            input: 'email',
            inputClass: 'disabled',
            inputValue: emailClient,
            inputPlaceholder: 'Send the token to client email.',
            html: 'This is the client`s email .',
            inputAttributes:
            {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Send',
            cancelButtonText: 'Close',
            customClass: "email-input",
            onOpen: () => Swal.getConfirmButton().focus()
        }).then((result) =>
        {

            if (result.value)
            {
                $('.reload').removeClass('hide');

                // Param with value
                var data = {
                    'email': $('.swal2-input').val(),
                    'url': url,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                };

                $.ajax(
                {
                    url: baseUrl + '/token/sentMail',
                    type: 'post',
                    data: data,
                    success: function(data)
                    {
                        $('#email').val('');
                        $('.reload').addClass('hide');
                        if (data == 'success')
                        {
                            // Success sent email
                            Swal.fire(
                                'Success',
                                'Sent! Please check the email.',
                                'success'
                            );
                        }

                    }
                });
            }
        })
    });
});

function writeTheShortcut(inputOptions)
{
    if (localStorage.getItem('shortcut') === null)
    {
        $('.department').html('<i class="fa fa-tags" aria-hidden="true"></i> Department :  Empty')
        $('.shortcut').html('<i class="fa fa-keyboard" aria-hidden="true"></i> Your shortcut keyboard is empty, please add by click "Shortcut" button.')
    }
    else
    {
        var dataShortcut = JSON.parse(localStorage.getItem('shortcut'));
        $('.department').html('<i class="fa fa-tags" aria-hidden="true"></i> Department :  ' + dataShortcut.deptName)
        $('.shortcut').html('<i class="fa fa-keyboard" aria-hidden="true"></i> Your shortcut keyboard is ' + inputOptions[dataShortcut.shortcut])
    }
}

function keydownShortcut(userType)
{
    if (!funcLocalStorage('get', 'shortcut', ''))
    {
        var dataShortcut = JSON.parse(localStorage.getItem('shortcut'));

        if (userType == dataShortcut.shortcut)
        {
            // Call function next token
            callNextToken(dataShortcut.idDept, dataShortcut.idCounter, dataShortcut.deptName);
        }
    }
}

// Function localstorage
function funcLocalStorage(type, storageName, item)
{
    if (type == 'save')
    {
        localStorage.setItem(storageName, item);
    }
    else if (type == 'get')
    {
        localStorage.getItem(storageName);
    }
    else
    {
        localStorage.removeItem(storageName);
    }
}

function callNextToken(idDeptVal, idCounterVal, finalDeptName)
{
    // Param with value
    var data = {
        'id_department': idDeptVal,
        'id_counter': idCounterVal,
        '_token': $('#csrf-token').val(),
    };

    $.ajax(
    {
        url: baseUrl + '/calls/update',
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(data)
        {
            $('.background-calling').hide();

            // Success
            Swal.fire(
                data['type'],
                data['message'],
                data['type']
            );

            if (data['type'] == 'success')
            {
                // Send mail to next clients
                sendNextToken(finalDeptName, idDeptVal, data['id_token']);
            }

            $('#id_department').val('');
            $('#id_counter').val('');
        }
    });
}

function dateFormatter(theDate)
{
    var date = new Date(theDate.replace(/\s/, 'T'));
    var year = date.getFullYear().toString();
    var month = (date.getMonth() + 101).toString().substring(1);
    var day = (date.getDate() + 100).toString().substring(1);
    return year + "-" + month + "-" + day;
}

function leftPad(value, length)
{
    value = String(value);
    length = length - value.length;
    return ('0'.repeat(length) + value)
}

// Send next token to email
function sendNextToken(deptName, deptId, idCurrentToken)
{
    var data = {
        'department': deptName
    };

    $.ajax(
    {
        url: baseUrl + '/token/getCurrentToken/',
        type: 'get',
        data: data,
        success: function(currentToken)
        {

            var dataMail = {
                'currnetToken': currentToken,
                'idCurrentToken': idCurrentToken,
                'deptId': deptId,
                '_token': $('#csrf-token').val(),
            };

            $.ajax(
            {
                url: baseUrl + '/mail/sendToNextClient',
                type: 'post',
                data: dataMail,
                dataType: 'json',
                success: function(data)
                {
                    $('#id_department').val('');
                    $('#id_counter').val('');

                    // Success
                    Swal.fire(
                        data['type'],
                        data['message'],
                        data['type']
                    );
                }
            });

        }
    });
}

// Function change status
function changeStatusOnline(userId, status, branchId)
{
    var data = {
        'id': userId,
        'id_branch': branchId,
        'status': status,
        '_token': $('#csrf-token').val(),
    };

    $.ajax(
    {
        url: baseUrl + '/status/updateStatusOnline',
        type: 'post',
        data: data,
        dataType: 'json',
    });
}