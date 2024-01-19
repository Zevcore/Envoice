const nav = document.querySelector(".nav");

document.addEventListener('scroll', () => {
    if(window.scrollY > 0) {
        nav.classList.add('sticky-nav');
    }
    else {
        nav.classList.remove('sticky-nav');
    }
})