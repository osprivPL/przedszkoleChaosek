// Show the last character of password input briefly
document.addEventListener('DOMContentLoaded', function() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        let revealTimeout;
        
        input.addEventListener('input', function() {
            if (revealTimeout) {
                clearTimeout(revealTimeout);
            }
            if (this.value.length === 0) {
                return;
            }
            const fullValue = this.value;
            const lastChar = fullValue[fullValue.length - 1];
            this.type = 'text';
            const maskedValue = '•'.repeat(fullValue.length - 1) + lastChar;
            this.value = maskedValue;
            revealTimeout = setTimeout(() => {
                this.type = 'password';
                this.value = fullValue;
            }, 500);
        });
        input.addEventListener('blur', function() {
            if (revealTimeout) {
                clearTimeout(revealTimeout);
                this.type = 'password';
            }
        });
    });
});
