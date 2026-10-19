document.getElementById("adminForm").addEventListener("submit", function(e){

    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    if(password !== confirmPassword){

        alert("Passwords do not match.");

        e.preventDefault();

    }

});