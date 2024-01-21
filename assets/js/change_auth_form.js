const registerForm = document.querySelector('.register');
const loginForm = document.querySelector('.login');

document.addEventListener('DOMContentLoaded', () => {
    loginForm.style.display = "none";
    registerForm.style.display = "flex";
})

const switchForm = () => {
    if(registerForm.style.display === "flex") {
        registerForm.style.display = "none";
        loginForm.style.display = "flex";
    }
    else {
        registerForm.style.display = "flex";
        loginForm.style.display = "none";
    }
}