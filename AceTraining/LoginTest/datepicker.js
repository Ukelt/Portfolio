
(function($) {
    $(document).ready(function() {
      console.log("Is this working");
  
      // Initialize the datepicker
      $("#quizStartDate").datepicker({
        onSelect: function() {
          // Enable the end date input and set the minimum date to the selected start date
          $("#quizEndDate").prop("disabled", false);
          $("#quizEndDate").datepicker("option", "minDate", $(this).datepicker("getDate"));
        }
      });
  
      // Disable the end date input on page load
      $("#quizEndDate").prop("disabled", true);
    });

    
  })(jQuery);
  //on floatingSelect change, check the value and then hide or show the appropriate div





  