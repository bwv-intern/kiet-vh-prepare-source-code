$(document).ready(function () {
    $("#date-of-birth").datepicker({
        dateFormat: 'yy/mm/dd',
    });

    $('#formAdd').validate({
        onfocusout: function(element) {
           this.element(element);
       },
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
               maxlength: 255,
           },
           repassword: {
               required: true,
               maxlength: 255,
               equalTo: "#password",
           },
           user_flg: {
               required: true,
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
               maxlength: "Email must be less than 50 characters."
           },
           name: {
               required: "Full Name is required field.",
               maxlength: "Full Name must be less than 50 characters."
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
               maxlength: "Phone must be less than 20 characters."
           },
           address: {}
       },
   });
});


