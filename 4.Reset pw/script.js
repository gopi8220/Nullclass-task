function resetPassword() {
    var email = document.getElementById('email').value;
    var messageElement = document.getElementById('message');

    if (!email) {
        messageElement.innerText = 'Please enter your email or phone number.';
        return;
    }

    messageElement.innerText = 'Password reset initiated. Check your email or phone for further instructions.';
}
