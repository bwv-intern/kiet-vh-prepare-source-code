$( document ).ready(function() {

    $("#date-of-birth").datepicker({
        dateFormat: 'dd/mm/yy'
    });

    $('#formSearch').validate({
        rules: {
            'email': {
                checkValidEmailRFC: true
            },
            'name': {
                maxlength: 50
            },
            'user_flag': {
                digits: true,
                range: [0, 2]
            },
            'date-of-birth': {
                dateFormat: true
            },
            'phone': {
                number: true,
            }
        },
        messages: {
            'email': {
                checkValidEmailRFC: 'Please enter your email address correctly.'
            },
            'name': {
                maxlength: 'Full Name format is not correct. Please enter max 50 character only'
            },
            'user_flag': {
                range: 'User flag must be between 0 and 2.'
            },
            'date-of-birth': {
                dateFormat: 'Date of birth format is not correct. Please enter date (dd/MM/yyyy) only.'
            },
            'phone': {
                number: 'Phone format is not correct. Please enter number only.',
            }
        },
    });


    $("#addButton").on("click", function () {
        var addUserRoute = $(this).data('add-route');
        window.location.href = addUserRoute;
    });
});
