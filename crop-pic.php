<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop & Rotate Image</title>
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-yellow: #FFD700;
            --dark-yellow: #FFC000;
            --light-yellow: #FFF9C4;
            --dark-gray: #333333;
            --light-gray: #f5f5f5;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 30px;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }
        
        h2 {
            color: var(--dark-gray);
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .file-input-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        #fileInput {
            display: none;
        }
        
        .custom-file-input {
            background-color: var(--primary-yellow);
            color: var(--dark-gray);
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-block;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .custom-file-input:hover {
            background-color: var(--dark-yellow);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        #image-preview {
            max-width: 100%;
            max-height: 400px;
            display: none;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        button {
            background-color: var(--primary-yellow);
            color: var(--dark-gray);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        button:hover {
            background-color: var(--dark-yellow);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .crop-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .crop-btn:hover {
            background-color: #45a049;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            .buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Upload, Crop & Rotate Image</h2>

    <!-- File Input -->
    <div class="file-input-container">
        <input type="file" id="fileInput" name="profilepic" accept=".jpg,.jpeg,.png">
        <label for="fileInput" class="custom-file-input">Choose Image</label>
    </div>
    
    <!-- Image Preview -->
    <img id="image-preview" style="display: none;">

    <!-- Buttons for Crop/Rotate -->
    <div class="buttons">
        <button type="button" onclick="rotateLeft()">
            <span>↺</span> Rotate Left
        </button>
        <button type="button" onclick="rotateRight()">
            <span>↻</span> Rotate Right
        </button>
        <button type="button" onclick="cropImage()" class="crop-btn">
            <span>✓</span> Crop & Upload
        </button>
    </div>
</div>

<!-- JavaScript Code -->
<script>
    let cropper;
    const fileInput = document.getElementById("fileInput");
    const imagePreview = document.getElementById("image-preview");

    // Load image into Cropper.js
    fileInput.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = "block";

            // Destroy previous Cropper instance (if exists)
            if (cropper) {
                cropper.destroy();
            }

            // Initialize Cropper.js
            cropper = new Cropper(imagePreview, {
                aspectRatio: 1, // Square crop
                viewMode: 2,
                background: false,
                autoCropArea: 0.8,
                responsive: true
            });
        };
        reader.readAsDataURL(file);
    });

    // Rotate Left
    function rotateLeft() {
        if (cropper) {
            cropper.rotate(-90);
        }
    }

    // Rotate Right
    function rotateRight() {
        if (cropper) {
            cropper.rotate(90);
        }
    }

    // Crop & Upload Image
    function cropImage() {
        if (!cropper) {
            alert("Please select and crop an image first!");
            return;
        }

        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300
        });

        // Convert to Blob
        canvas.toBlob((blob) => {
            const formData = new FormData();
            formData.append("profilepic", blob, "cropped.jpg");

            // Send to PHP server
            fetch("upload.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                window.location.href = "profile.php";
                console.log(data);
            })
            .catch(error => console.error("Error uploading image:", error));
        }, "image/jpeg");
    }
</script>

</body>
</html>