//intText.js is intended to provide an interactive textfield experience

// JavaScript which counts the number of characters entered within the textfield

// Variables / element selectors
var USChars = document.getElementById("UserCharLeft");
var PWChars = document.getElementById("PWCharLeft");
var user = document.getElementById("newuser");
var PW = document.getElementById("newPW");
var submitRequest = document.getElementById("submitRequest");

// Functions
function updateCharCount() {
    var userLength = user.value.length;
    var pwLength = PW.value.length;
    var userCounter = 7 - userLength;
    var pwCounter = 7 - pwLength;

    // Update username character count
    if (userLength === 0) {
        USChars.innerHTML = "Username must contain a minimum of 7 characters";
    } else if (0 < userCounter && userCounter < 7) {
        USChars.innerHTML = "Username characters required for submission = " + userCounter;
    } else if (userCounter <= 0) {
        USChars.innerHTML = "Username length is satisfactory";
    }

    // Update password character count
    if (pwLength === 0) {
        PWChars.innerHTML = "Password must contain a minimum of 7 characters";
    } else if (0 < pwCounter && pwCounter < 7) {
        PWChars.innerHTML = "Password characters required for submission = " + pwCounter;
    } else if (pwCounter <= 0) {
        PWChars.innerHTML = "Password length is satisfactory";
    }

    // Enable or disable submit button
    if (userCounter <= 0 && pwCounter <= 0) {
        submitRequest.disabled = false;
    } else {
        submitRequest.disabled = true;
    }
}

// Event listeners
user.addEventListener('input', updateCharCount, false);
PW.addEventListener('input', updateCharCount, false);

// Initial check
updateCharCount();
