function showToast(message, duration = 3000) { // Duration in milliseconds (default 3 seconds)
    const toast = document.createElement('div');
    toast.className = 'custom-toast'; // Add a class for styling
    toast.textContent = message;

    // Basic styling (customize this in your CSS)
    toast.style.cssText = `
        position: fixed; /* Or absolute, depending on your layout */
        bottom: 20px;  /* Adjust vertical position */
        left: 50%;
        transform: translateX(-50%);
        background-color: #333; /* Dark background */
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        z-index: 9999; /* Ensure it's on top */
        opacity: 0; /* Initially hidden */
        transition: opacity 0.3s ease-in-out; /* Smooth fade in/out */
    `;

    document.body.appendChild(toast);

    // Fade in
    setTimeout(() => {
        toast.style.opacity = 1;
    }, 100); // Small delay before fading in

    // Fade out and remove
    setTimeout(() => {
        toast.style.opacity = 0;
        setTimeout(() => {
            toast.remove();
        }, 300); // Delay to allow fade out to complete
    }, duration);
}

// Example usage:
showToast("Form submitted successfully!");

// Example usage after a form submission:
const submitButton = document.querySelector('button[type="submit"][name="submit"]');
if (submitButton) {
    submitButton.addEventListener('click', (event) => {
        // ... your form submission logic ...

        // After successful submission:
        showToast("Form submitted successfully!");

        // After failed submission:
        // showToast("Error in form submission!");
    });
}