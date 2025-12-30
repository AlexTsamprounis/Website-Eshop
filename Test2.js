/* Textarea character counter */

document.addEventListener("DOMContentLoaded", function() {
    
    const input = document.querySelector("#formComments");
    const remainingCharsDisplay = document.querySelector("#remainingChars");
    const maxChars = 400;

    input.addEventListener("input", function() {
        const remainingChars = maxChars - input.value.length;
        remainingCharsDisplay.textContent = remainingChars;

    });

    remainingCharsDisplay.innerHTML = maxChars;
});

/* Event listeners for form and input elements */