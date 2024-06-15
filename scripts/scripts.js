document.addEventListener('DOMContentLoaded', () => {

    //Variables for the buttons for the elevator interface
    const buttons = document.querySelectorAll('.floor_button');
    const statusBox = document.getElementById('status-box');

    // Variable to display current year at the footer with the copyright
    const currentYearElement = document.getElementById('current_year');

    //Variable to display current time and date
    const dateTimeDisplay = document.getElementById('date-time-display');

    // Password and User validation variables
    const loginForm = document.getElementById('login-form');
    const usernameInput = document.getElementById('uname');
    const passwordInput = document.getElementById('psw');
    const errorMessage = document.getElementById('error-message');

    // Scroll to top button
    const top_bttn = document.getElementById("top_bttn"); // Changed from 'let' to 'const'

    var current_year = new Date().getFullYear();




    // ======================== MAIN CODE ============================= //

    // ----------------- ELEVATOR INTERFACE ----------------- //

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove 'active' class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Add 'active' class to the clicked button
            button.classList.add('active');

            // Update the status box
            const buttonId = button.id;
            const buttonNumber = buttonId.replace('floor_', '');
            statusBox.textContent = `The elevator is on floor: ${buttonNumber}.`;
        });
    });


    // ------------------ LOGIN FORM VALIDATION --------------------------//
    if (loginForm && usernameInput && passwordInput && errorMessage) {
        function validateInput(input) {
            return input.value.length >= 7;
        }
    
        loginForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent form submission
    
            const isUsernameValid = validateInput(usernameInput);
            const isPasswordValid = validateInput(passwordInput);
    
            if (!isUsernameValid || !isPasswordValid) {
                errorMessage.textContent = 'Username and Password must be at least 7 characters long.';
                if (!isUsernameValid) {
                    usernameInput.style.borderColor = 'red';
                } else {
                    usernameInput.style.borderColor = '';
                }
    
                if (!isPasswordValid) {
                    passwordInput.style.borderColor = 'red';
                } else {
                    passwordInput.style.borderColor = '';
                }
            } else {
                errorMessage.textContent = '';
                usernameInput.style.borderColor = '';
                passwordInput.style.borderColor = '';
    
                // Proceed with form submission or further validation
                loginForm.submit(); 
            }
        });
    
        usernameInput.addEventListener('input', () => {
            if (validateInput(usernameInput)) {
                usernameInput.style.borderColor = '';
            }
        });
    
        passwordInput.addEventListener('input', () => {
            if (validateInput(passwordInput)) {
                passwordInput.style.borderColor = '';
            }
        });

        // Give focus to the username input field when the page has loaded
        window.addEventListener('load', () => {
            usernameInput.focus();
        });
    }


    // ------------------ SCROLL TO TOP FUNCTION -------------------- //

    if (top_bttn) {
        // Display the scroll-to-top button when scrolled down
        window.onscroll = function() {
            scrollFunction(); // Added semicolon
        };
    
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                top_bttn.style.display = "block";
            } else {
                top_bttn.style.display = "none";
            }
        }
    
        // Scroll to the top of the document
        top_bttn.addEventListener('click', function() { // Added event listener for 'click'
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        });
    }


    // -------------------------- FOOTER CURRENT YEAR DISPLAY ------------------ //
    if (currentYearElement) {
        // Set the current year in the footer
        if (currentYearElement) {
            currentYearElement.textContent = current_year;
        }
    }

    
    // ----------------------- DISPLAY CURRENT DAY AND TIME FUNCTION ------------//
    if (dateTimeDisplay) {
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleString();
            dateTimeDisplay.textContent = dateTimeString;
        }
    
        // Initial call to set the date and time
        updateDateTime();
    
        // Update the date and time every second
        setInterval(updateDateTime, 1000);
    }


});
