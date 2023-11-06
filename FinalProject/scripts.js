const sections = document.querySelectorAll('.section');
const dotIndicator = document.querySelector('.dot-indicator');
const container = document.querySelector('.horizontal-scroll-container');

let currentSectionIndex = 0;
let isScrolling = true; // Variable to control auto-scrolling
let dotClicked = false;

// Function to pause auto-scrolling for 3 seconds
function pauseAutoScroll() {
    dotClicked = true;
    setTimeout(() => {
        dotClicked = false;
    }, 3000); // 3000 milliseconds (3 seconds)
}

function scrollToSection(index) {
    sections[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function updateDotIndicator() {
    const dots = Array.from(dotIndicator.children);
    dots.forEach((dot, index) => {
        if (index === currentSectionIndex) {
            dot.style.background = 'white'; // Active dot color
            dot.style.height = "15px";
            dot.style.width = "15px";
        } else {
            dot.style.background = '#aaa'; // Inactive dot color
            dot.style.height = "10px";
            dot.style.width = "10px";
        }
    });
}

function scrollToNextSection() {
    if (isScrolling) {
        currentSectionIndex = (currentSectionIndex + 1) % sections.length;
        scrollToSection(currentSectionIndex);
        updateDotIndicator();
    }
}

// Set an interval to scroll to the next section every 10 seconds
const scrollInterval = setInterval(scrollToNextSection, 5000);

// Initialize the dot indicator
sections.forEach((section, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if (index === currentSectionIndex) {
        dot.style.background = 'white'; // Active dot color
        dot.style.height = "15px";
        dot.style.width = "15px";
    }
    dot.addEventListener('click', (event) => {
        scrollToSection(index);
        currentSectionIndex = index;
        updateDotIndicator();
    });
    dotIndicator.appendChild(dot);
});

// Use Intersection Observer to detect if the container is not in view
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) {
            isScrolling = false; // Pause auto-scrolling when the container is not in view
        } else {
            isScrolling = true; // Resume auto-scrolling when the container is in view
        }
    });
});

observer.observe(container);
