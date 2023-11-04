/* Sections and Dot Indicator Scroll */

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

document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".horizontal-scroll-container");
    const sections = document.querySelectorAll(".section");
    const dotIndicator = document.querySelector(".dot-indicator");

    let currentSectionIndex = 0;

    // Function to scroll to the next section
    function scrollToNextSection() {
        currentSectionIndex = (currentSectionIndex + 1) % sections.length;
        scrollSectionIntoView(currentSectionIndex);
    }

    // Function to scroll a section into view
    function scrollSectionIntoView(index) {
        sections[index].scrollIntoView({
            behavior: "smooth",
            block: "center",
        });
    }

    // Automatically scroll to the next section every 10 seconds
    const scrollInterval = setInterval(scrollToNextSection, 10000);

    // Stop the auto-scroll when the user interacts with the container
    container.addEventListener("scroll", function () {
        clearInterval(scrollInterval);
    });
});
  
