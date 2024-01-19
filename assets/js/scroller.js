const scroller = document.querySelector('.scroller');
document.addEventListener('scroll', () => {
    if(window.scrollY > 0) {
        scroller.style.display = "block";
    }
    else {
        scroller.style.display = "none";
    }
})

scroller.addEventListener('click', () => {
    window.scrollTo(0, 0);
});