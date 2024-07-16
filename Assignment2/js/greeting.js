document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();  // The Date() object constructor
    var hourNow = today.getHours();
    var greeting;

    if (hourNow >= 22){
        greeting = "It's Getting Late, But You're Still Welcome Here!";
    } else if (hourNow >= 18) {
        greeting = "Welcome & Good Evening!";
    } else if (hourNow >= 12) {
        greeting = "Welcome & Good Afternoon! We Hope You Brought Pizza.";
    } else {
        greeting = "Welcome & Good Morning! Have You Had A Coffee Today?";
    }

    var element = document.getElementById('greet');
    typeWriter(element, greeting);
});

function typeWriter(element, text, i = 0) {
    if (i < text.length) {
        element.innerHTML = text.substring(0, i + 1);
        setTimeout(function() {
            typeWriter(element, text, i + 1);
        }, 35); // Adjust the typing speed by changing the timeout value (in milliseconds)
    }
}