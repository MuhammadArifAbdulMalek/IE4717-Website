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
    const divs = container.getElementsByClassName("productwrapper");

    scrollButton.addEventListener("click", function () {
      currentDivIndex = (currentDivIndex + 1) % divs.length;
      const divToScrollTo = divs[currentDivIndex];
  
      if (divToScrollTo) {
        // Scroll the container to the next div if it exists
        container.scroll({
          left: divToScrollTo.offsetLeft,
          behavior: "smooth",
        });
        
        // Print the current div index to the console
        console.log("Current Div Index: " + currentDivIndex);
      }
    });
  
    container.addEventListener("scroll", function () {
      // Calculate the current div index based on scroll position
      currentDivIndex = Math.floor(container.scrollLeft / divs[0].offsetWidth);
      console.log("Current Div Index: " + currentDivIndex);
    });
  });