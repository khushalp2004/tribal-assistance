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
        let transcript = event.results[0][0].transcript
            .toLowerCase() // Convert everything to lowercase first
            .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize first letter of each word
        
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

  const input1 = document.getElementById('name');
  initializeSpeechRecognition(input1);
  const input3 = document.getElementById('city');
  initializeSpeechRecognition(input3);
  

  //for email
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

  const input2 = document.getElementById('email');
  initializeSpeechRecognition2(input2);


  //for password
  function initializeSpeechRecognition3(inputField) {
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

  const input4 = document.getElementById('password');
  initializeSpeechRecognition3(input4);