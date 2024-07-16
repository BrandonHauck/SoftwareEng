//This script creates the vault door spin animation
document.getElementById('enterButton').addEventListener('click', function() {
    this.classList.add('roll-off');
    setTimeout(function() {
        window.location.href = 'login.html';
    }, 1000); // Adjust the timeout to match the animation duration
});