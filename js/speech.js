function initializeSpeechRecognition(inputField, speechButton) {
  if ('webkitSpeechRecognition' in window) {
    const recognition = new webkitSpeechRecognition();

    recognition.continuous = false;
    recognition.interimResults = false;

    speechButton.addEventListener('click', () => {
      inputField.classList.add('listening');
      recognition.start();
    });

    recognition.onstart = () => {
      speechButton.disabled = true;
      inputField.placeholder = "Listening...";
    };

    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript;
      inputField.value = transcript;
    };

    recognition.onend = () => {
      speechButton.disabled = false;
      inputField.classList.remove('listening');
      inputField.placeholder = "Start speaking...";
    };

    recognition.onerror = (event) => {
      console.error('Speech recognition error:', event.error);
      inputField.placeholder = "Error occurred. Please try again.";
      speechButton.disabled = false;
      inputField.classList.remove('listening');
    };

  } else {
    inputField.placeholder = "Speech recognition not supported in this browser.";
    speechButton.style.display = 'none';
  }
}


// Example usage for multiple input fields:
const input1 = document.getElementById('name');
const button1 = document.getElementById('speech-button');
initializeSpeechRecognition(input1, button1);

const input2 = document.getElementById('age');
const button2 = document.getElementById('age-button');
initializeSpeechRecognition(input2, button2);

const input3 = document.getElementById('address');
const button3 = document.getElementById('address-button');
initializeSpeechRecognition(input3, button3);

const input4 = document.getElementById('phone');
const button4 = document.getElementById('phone-button');
initializeSpeechRecognition(input4, button4);

