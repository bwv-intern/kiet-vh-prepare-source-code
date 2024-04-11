$(document).ready(function () {
    $("#date-of-birth").datepicker({
        dateFormat: 'yy/mm/dd',
    });

    $('#formAdd').validate({
       rules: {
           email: {
               required: true,
               checkValidEmailRFC: true,
               maxlength: 50
           },
           name: {
               required: true,
               maxlength: 50,
           },
           password: {
               required: true,
           },
           repassword: {
               required: true,
               equalTo: "#password",
           },
           user_flg: {
               required: true,
               userFlgValidation : true,
           },
           date_of_birth: {
               dateYMD: true,
           },
           phone: {
               number : true,
               maxlength: 20,
           },
           address: {}
       },
       messages: {
           email: {
               required: "Email is required field.",
               checkValidEmailRFC: "Please enter your email address correctly.",
           },
           name: {
               required: "Full Name is required field.",
           },
           password: {
               required: "Password is required field."
           },
           repassword: {
               required: "Re-password is required field.",
               equalTo: "Re-password is not the same as Password."
           },
           user_flg: {
               required: "User Flag is required field."
           },
           date_of_birth: {
            dateYMD: "Date of birth format is not correct. Please enter yyyy/mm/dd only.",
           },
           phone: {
               number : "Phone format is not correct. Please enter number only.",
           },
           address: {}
       },
   });
});


