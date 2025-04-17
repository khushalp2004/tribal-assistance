function hoveredIt() {
    document.getElementById("editText").style.display = "block"; 
}

function unhoveredIt() {
    document.getElementById("editText").style.display = "none"; 
}

// Function to trigger file input when clicking the profile picture
function triggerFileInput() {
    document.getElementById("profile-pic-upload").click();
}

// Function to preview the selected image before uploading
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profile-pic').src = e.target.result;
            document.getElementById('imageData').value = e.target.result;
            document.getElementById('cropbtn').classList.remove('hidden'); // Show Crop Button
        };
        reader.readAsDataURL(file);
    }
}