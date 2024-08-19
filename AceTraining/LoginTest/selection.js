$(document).ready(function () {

    let selected = $('#floatingSelect').val();
    console.log(selected)
    if (selected != "Document") {
        console.log('Hiding documentUpload')
        $('#documentUpload').hide();
    }
    $('#floatingSelect').change(function () {
        selected = $('#floatingSelect').val();
        console.log(selected);
        if (selected == "Document") {
            console.log('Showing documentUpload')
            $('#documentUpload').show();
        } else {
            console.log('Hiding documentUpload')
            $('#documentUpload').hide();
        }
    })



        
            //check if all inputs are filled
            function checkInputs() {
                var allFilled = true;
                inputFields.each(function () {
                    if ($(this).val() == "") {
                        allFilled = false;
                    }
                });
                return allFilled;
            }
            //check if selected is 2 and if the file input is filled
            function checkFile() {
                var fileFilled = true;
                if ($('#floatingSelect').val() == "2") {
                    if ($('#inputGroupFile02').val() == "") {
                        fileFilled = false;
                    }
                }
                return fileFilled;
            }
            //check if all inputs are filled and if the file input is filled
            function checkAll() {
                var allFilled = checkInputs();
                var fileFilled = checkFile();
                return allFilled && fileFilled;
            }
            //enable button if all inputs are filled and if checkall returns true
            if (selected == "2") {
                if (checkAll() == true) {
                    submitButton.prop('disabled', false);
                } else {
                    submitButton.prop('disabled', true);
                }
            }
                //get the submit button
    var submitButton = $("#postSubmit");
    //get all input fields except the file input
    var inputFields = $("input:not([type='file'])");
    inputFields.change(function () {
        console.log(selected)
        if (selected == 2) {
            if (checkAll() == true) {
                console.log('Check all is true')
                submitButton.prop('disabled', false);
                    console.log('Hiding, check all in not true')
                    submitButton.prop('disabled', true);
            }
        } else {
            if (checkInputs() == true) {
                console.log('Check inputs is true')
                submitButton.prop('disabled', false);
            } else {
                console.log('Hiding, check inputs is not true')
                submitButton.prop('disabled', true)
            }
        }
    });
});