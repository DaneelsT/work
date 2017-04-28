/**
 *	The main script file responsible for handling script execution.
 *
 * 	@author Gaetan Dumortier
 * 	@since 24 October 2016
 */

/**
 *  Responsible for creating input masks for the start and end time fields.
 */
function createMasks() {
    $('#startTime').mask('00:00', {'translation': {0: {pattern: /[0-9*]/}}});
    $('#endTime').mask('00:00', {'translation': {0: {pattern: /[0-9*]/}}});
}

/**
 *  Append the minutes (:00) to the start and end time automatically when only the hours are entered.
 *  @param The time to append the minutes to (start|end)
 */
 function appendMinutes(time) {
     var time = $("#" + time + "Time");

     if(time.val().length == 2) {
         time.val(time.val() + ":00");
     }else if(time.val().length == 1) {
         time.val("0" + time.val() + ":00");
     }
 }

/**
 *  Check the entered time in the start and end time fields and make sure the entered value is valid.
 *  @param The time to validate (start|end)
 */
function validateTime(time) {
    var time = $("#" + time + "Time");
    var timeResult = time.val().split(':');
    if(timeResult[0] > 23 || timeResult[1] > 59) {
        time.val("");
    }
}

/**
 * Responsible for displaying a confirmationbox when the book month or book year button has been clicked.
 * Not the best looking code I ever written but ok.
 */
function closeConfirmationBox() {
    var button = $("#closebutton");
    var buttonText = button.text();
    var option;
    var text = "Are you sure you want to book and close this month?\nThere is no going back!"; // default text

    // Work out if the button is for month or year
    if(buttonText.toLowerCase().indexOf('month') > -1 || buttonText.toLowerCase().indexOf('maand') > -1) {
        option = "month";
    }else{
        option = "year";
    }

    // Switch between month and year text
    switch(option) {
        case "month":
            if(buttonText.toLowerCase() == "close month") {
                text = "Are you sure you want to book and close this month?\nThere is no going back!";
            }else if(buttonText.toLowerCase() == "sluit maand") {
                text = "Ben je zeker dat je deze maand wil boeken en afsluiten?\nDit kan niet ongedaan worden gemaakt!";
            }
            break;
        case "year":
            if(buttonText.toLowerCase() == "close year") {
                text = "Are you sure you want to book and close this year?\nThere is no going back!";
            }else if(buttonText.toLowerCase() == "sluit jaar") {
                text = "Ben je zeker dat je dit jaar wil boeken en afsluiten?\nDit kan niet ongedaan worden gemaakt!";
            }
            break;
    }

    $(button).click(function() {
        var confirmation = confirm(text);
        if(confirmation == false) {
            return false;
        }
    });
}

$(document).ready(function() {
    // Create the input masks.
    createMasks();

    // Called when the startTime fields changes.
    $("#startTime").change(function() {
        appendMinutes("start");
        validateTime("start");
    });

    // Called when the endTime fields changes.
    $("#endTime").change(function() {
        appendMinutes("end");
        validateTime("end");
    });

    // Called when the book month button has been clicked.
    closeConfirmationBox();

});
