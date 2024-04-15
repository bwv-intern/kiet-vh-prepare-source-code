$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'The file size limit 5MB has been exceeded.');


$.validator.addMethod('fileExtension', function (value, element, extension) {
    extension = extension.toLowerCase();
    var fileExtension = value.split('.').pop().toLowerCase();
    return this.optional(element) || (fileExtension === extension);
}, 'File extension is incorrect. Please use csv.');

$.validator.addMethod("dateYMD", function (value, element) {
    return this.optional(element) || /^\d{4}\/\d{2}\/\d{2}$/.test(value);
}, "");

$.validator.addMethod(
    'dateDMY',
    function (value, element) {
        return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
    },
    'Date of birth format is not correct. Please enter date (dd/MM/yyyy) only.'
);
$.validator.addMethod('userFlgValidation', function(value, element) {
    return this.optional(element) || ['0', '1', '2'].indexOf(value) !== -1;
  }, 'User Flag format is not correct. Please enter number only.');