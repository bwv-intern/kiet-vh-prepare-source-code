$(function () {
    $("#date-of-birth").datepicker({
        dateFormat: 'yy/mm/dd',
    });

    $('#formEdit').validate({
        rules: {
            email: {
                required: true,
                email: true,
                checkValidEmailRFC: true,
                maxlength: 50
            },
            name: {
                required: true,
                maxlength: 50
            },
            password: {
                maxlength: 255,
            },
            repassword: {
                required: function (element) {
                    return $('#password').val().length > 0;
                },
                maxlength: 255,
                equalTo: "#password",
            },
            user_flg: {
                required: true
            },
            date_of_birth: {
                dateYMD: true
            },
            phone: {
                number: true,
                maxlength: 20
            },
            address: {}
        },
        messages: {
            email: {
                email: "Please enter your email address correctly."
            },
            repassword: {
                equalTo: "Re-password is not the same as Password.",
            },
            date_of_birth: {
                dateYMD: "Date of birth format is not correct. Please enter yyyy/mm/dd only.",
            },
            phone: {
                number: "Phone format is not correct. Please enter number only.",
            },
            address: {}
        },
    });
});