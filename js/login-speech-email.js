///for emails
function initializeSpeechRecognition(inputField) {
    if ('webkitSpeechRecognition' in window) {
      const recognition = new webkitSpeechRecognition();
  
      recognition.continuous = false;
      recognition.interimResults = false;
  
      inputField.addEventListener('dblclick', () => {
        inputField.classList.add('listening');
        recognition.start();
      });
  
      recognition.onstart = () => {
        speechButton.disabled = true;
        inputField.placeholder = "Listening...";
      };
      
      recognition.onresult = (event) => {
        let transcript = event.results[0][0].transcript.replace(/\s+/g, '').toLowerCase();
        inputField.value = transcript + "@gmail.com";
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
  const input1 = document.getElementById('email');
  // const button1 = document.getElementById('email-button');
  initializeSpeechRecognition(input1);


  //for password
  function initializeSpeechRecognition2(inputField) {
    if ('webkitSpeechRecognition' in window) {
      const recognition = new webkitSpeechRecognition();
  
      recognition.continuous = false;
      recognition.interimResults = false;
  
      inputField.addEventListener('dblclick', () => {
        inputField.classList.add('listening');
        recognition.start();
      });
  
      recognition.onstart = () => {
        speechButton.disabled = true;
        inputField.placeholder = "Listening...";
      };
  
      recognition.onresult = (event) => {
        // const transcript = event.results[0][0].transcript;
        let transcript = event.results[0][0].transcript.replace(/\s+/g, '').toLowerCase();
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

  const input2 = document.getElementById('password');
  // const button2 = document.getElementById('password-button');
  initializeSpeechRecognition2(input2);
  
 
  