/* const container = document.querySelector('.horizontal-scroll-container');
const content = document.querySelector('.content');
const dotIndicators = document.querySelectorAll('.dot');
const sections = document.querySelectorAll('.section');

dotIndicators.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        container.scrollLeft = sections[index].offsetLeft;
    });
});

container.addEventListener('scroll', () => {
    sections.forEach((section, index) => {
        if (container.scrollLeft >= section.offsetLeft && container.scrollLeft < section.offsetLeft + section.offsetWidth) {
            dotIndicators[index].style.backgroundColor = 'aquamarine'; // Change the active dot color
        } else {
            dotIndicators[index].style.backgroundColor = '#555'; // Change the inactive dot color
        }
    });
}); */

const container = document.querySelector('.horizontal-scroll-container');
const content = document.querySelector('.content');
const dots = document.querySelector('.dot-indicator');

const sections = document.querySelectorAll('.section');
sections.forEach((section, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    dot.addEventListener('click', () => {
        container.scrollLeft = index * section.offsetWidth;
    });
    dots.appendChild(dot);
});

container.addEventListener('scroll', () => {
    const scrollPosition = container.scrollLeft;
    const sectionWidth = sections[0].offsetWidth;
    const activeDotIndex = Math.floor(scrollPosition / sectionWidth);
    const dotsArray = Array.from(dots.querySelectorAll('.dot'));
    dotsArray.forEach((dot, index) => {
        if (index === activeDotIndex) {
            dot.style.background = 'white'; // Active dot color
            dot.style.height = "15px";
            dot.style.width = "15px";
        } else {
            dot.style.background = '#aaa'; // Inactive dot color
            dot.style.height = "10px";
            dot.style.width = "10px";
        }
    });
});