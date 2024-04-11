$( document ).ready(function() {

    var _search = {};
    _search.handleDeleteForm = handleDeleteForm;

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

    $('#btn-clear').on('click', function () {
      
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/common/resetSearch',
            type: 'get',
            data: {
                screen: $(this).data('screen'),
            },
            dataType: 'json',
            success: function (response) {

                console.log(response);
                if(response.hasError == false){
                    $("#formSearch input[type='text']").val('');
                    $("#formSearch input[type='date']").val('');
                    $("#formSearch input[type='email']").val('');
                    $("#formSearch input[type='number']").val('');
                   
                    $('input[type="checkbox"]').prop('checked', true);
                }
            }
        });
    });

    $('.delete-user').submit(handleDeleteForm);
});

function handleDeleteForm(event) {
    event.preventDefault();
    var deleteUserRoute = $(this).data('add-route');

    var $form = $(this);

    var userId = $form.attr('id').replace('deleteForm_', '');

    var confirmed = confirm("Are you sure you want to delete the record with id " + userId + "?");

    if (!confirmed) {
        return;
    }
    $form.off('submit').submit();
}