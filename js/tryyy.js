// Mobile Menu Functionality
const menuIcon = document.getElementById("menu-icon");
const navLinks = document.getElementById("nav-links");

// Function to toggle the mobile menu
function toggleMenu() {
  navLinks.classList.toggle("nav-active");
  menuIcon.classList.toggle("toggle");
  document.body.classList.toggle("no-scroll");
}

// Click event for menu icon
menuIcon.addEventListener("click", toggleMenu);

// Keydown event for accessibility (Enter or Space key)
menuIcon.addEventListener("keydown", (e) => {
  if (e.key === "Enter" || e.key === " ") {
    e.preventDefault();
    toggleMenu();
  }
});

// Close menu when a link is clicked
const navLinksItems = document.querySelectorAll(".nav-links li a");
navLinksItems.forEach((link) => {
  link.addEventListener("click", () => {
    navLinks.classList.remove("nav-active");
    menuIcon.classList.remove("toggle");
    document.body.classList.remove("no-scroll");
  });
});

// Color Wheel Animation
const canvas = document.getElementById("colorWheelCanvas");
const ctx = canvas.getContext("2d");
const radius = canvas.width / 2;
const toRad = Math.PI / 180;

function drawColorWheel() {
  for (let angle = 0; angle <= 360; angle += 1) {
    const startAngle = (angle - 1) * toRad;
    const endAngle = angle * toRad;
    ctx.beginPath();
    ctx.moveTo(radius, radius);
    ctx.arc(radius, radius, radius, startAngle, endAngle, false);
    ctx.closePath();
    ctx.fillStyle = `hsl(${angle}, 100%, 50%)`;
    ctx.fill();
  }
}

drawColorWheel();

// Color Mixer Functionality
const redRange = document.getElementById("redRange");
const greenRange = document.getElementById("greenRange");
const blueRange = document.getElementById("blueRange");
const colorDisplay = document.getElementById("colorDisplay");

function updateColorDisplay() {
  const r = redRange.value;
  const g = greenRange.value;
  const b = blueRange.value;
  const rgbColor = `rgb(${r}, ${g}, ${b})`;
  colorDisplay.style.backgroundColor = rgbColor;
}

redRange.addEventListener("input", updateColorDisplay);
greenRange.addEventListener("input", updateColorDisplay);
blueRange.addEventListener("input", updateColorDisplay);

// Initialize color display
updateColorDisplay();



const hoverDiv = document.getElementById('hoverDiv');
    const hoverAudio = document.getElementById('hoverAudio');

    hoverDiv.addEventListener('mouseenter', () => {
      hoverAudio.currentTime = 0; // Start from the beginning
      hoverAudio.play();
    });

    hoverDiv.addEventListener('mouseleave', () => {
      hoverAudio.pause();
      hoverAudio.currentTime = 0; // Reset audio
    });