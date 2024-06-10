document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.floor_button');
    const statusBox = document.getElementById('status-box');

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
});

