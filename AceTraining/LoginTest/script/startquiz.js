// on document ready
$(document).ready(function() {
    //get startQuiz button
    var startQuiz = $("#startQuiz");
    //add click event
    startQuiz.click(function() {
        // quizHeading is hidden
        $("#quizHeading").hide();
        console.log('Hiding quizHeading')
        //quizContainer is shown
        $("#quizContainer").show();
        console.log('Showing quizContainer')
    });
});