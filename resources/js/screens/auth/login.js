$(function() {
    $('#login-form').validate({
        rules: {
            "email": {
                required: true,
                email: true,
                checkValidEmailRFC: true
            },
            "password": {
                required: true,
            },
        },
        messages: {
            "email": {
                required: "Email is required field.",
                email: "Please enter your email address correctly.",
                checkValidEmailRFC: "Please enter your email address correctly."
            },
            "password": {
                required: "Password is required field.",
            },
        },
    });
});
