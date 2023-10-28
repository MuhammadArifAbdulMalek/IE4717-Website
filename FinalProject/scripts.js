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
    const scrollcontainer = document.getElementsByClassName("scroll-content");
    const scrollButton = document.getElementById("scroll-button");
  
    let currentDivIndex = 0;
    const divs = scrollcontainer.querySelectorAll(".productwrapper");
  
    scrollButton.addEventListener("click", function () {
      currentDivIndex = (currentDivIndex + 1) % divs.length;
      const divToScrollTo = divs[currentDivIndex];
      
      // Scroll the container to the next div
      container.scroll({
        left: divToScrollTo.offsetLeft,
        behavior: "smooth", // Use smooth scrolling for a nice effect
      });
    });
  });
  