$( document ).ready(function() {

    $("#date-of-birth").datepicker({
        dateFormat: 'yy/mm/dd',
    });

    $('#formSearch').validate({
        rules: {
            'email': {
                checkValidEmailRFC: true
            },
            
            'user_flag': {
                digits: true,
            },
            'date_of_birth': {
                dateYMD: true
            },
            'phone': {
                number: true,
            }
        },
        messages: {
            'email': {
                checkValidEmailRFC: 'Please enter your email address correctly.'
            },
            'user_flag': {
                digits: 'User Flag format is not correct. Please enter number only.'
            },
            'date_of_birth': {
                dateYMD: 'Date of birth format is not correct. Please enter date (yyyy/MM/dd) only.'
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
