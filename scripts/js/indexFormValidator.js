document.getElementById("loginPanel").addEventListener("submit", function(e){
    e.preventDefault();

    let email = document.getElementById("tbxEmail");
    let password = document.getElementById("tbxHaslo");
    let error = false;

    if (email.value==="" || !email.value.includes("@") || !email.value.includes(".")){
        email.style.borderColor="red";
        document.getElementById("emailError").style.display="block";
        error = true;
    }
    else{
        email.style.borderColor="initial";
        document.getElementById("emailError").style.display="none";
    }
    if (password.value===""){
        password.style.borderColor="red";
        document.getElementById("passwordError").style.display="block";
        error=true;
    }
    else{
        email.style.borderColor="initial";
        document.getElementById("passwordError").style.display="none";
    }
    if (error) return;

    this.submit();
});