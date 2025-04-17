    document.getElementById("dob").addEventListener("change", function () {
        let dob = new Date(this.value);
        let today = new Date();
        
        let age = today.getFullYear() - dob.getFullYear();
        let monthDiff = today.getMonth() - dob.getMonth();
        let dayDiff = today.getDate() - dob.getDate();

        // Adjust age if birthday hasn't occurred yet this year
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        document.getElementById("age").value = age >= 0 ? age : 0;
    });