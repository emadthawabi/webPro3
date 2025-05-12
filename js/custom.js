//Aos Animation
AOS.init({duration:1000, once:true});

// toggle menu
document.addEventListener("DOMContentLoaded",()=> {
    document.getElementById("hamburger").addEventListener("click" , () =>{
        document.getElementById("navLinks").classList.toggle("show");
    });
});

// tour swiper slider
const swiper = new Swiper('.tourSwiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    speed: 1000,

    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'bullets',
    },

    breakpoints: {
        640: {
            slidesPerView: 2,
            slidesPerGroup: 2  // Move 2 slides at once
        },
        1024: {
            slidesPerView: 4,
            slidesPerGroup: 4  // Move 4 slides at once
        }
    },

    autoplay: {
        delay: 5000,
        disableOnInteraction: false
    }
});

// Fix for the 3D swiper
const mySwiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 200, /* Increased depth for more pronounced 3D effect */
        modifier: 2.5, /* Slightly higher modifier for sharper effect */
        slideShadows: true,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        320: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        }
    }
});

// Fixed header on scroll
const mainHeader = document.getElementById("mainHeader");
const headerHeight = mainHeader.offsetHeight;

window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
        mainHeader.classList.add("fixed");
        document.body.style.paddingTop = headerHeight + "px";
    } else {
        mainHeader.classList.remove("fixed");
        document.body.style.paddingTop = 0;
    }
});

// Back to Top Button functionality
const backToTopBtn = document.createElement('button');
backToTopBtn.id = 'backToTop';
backToTopBtn.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
document.body.appendChild(backToTopBtn);

window.addEventListener("scroll", () => {
    backToTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
});

backToTopBtn.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal
    const modal = document.getElementById('authModal');

    // Get the button that opens the modal
    const btn = document.querySelector('#loginBtn');

    // Make sure the login button exists before attaching event
    if (btn) {
        // When the user clicks the button, open the modal
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        });
    }

    // Get the <span> element that closes the modal
    const close = document.getElementsByClassName('close')[0];

    // Make sure the close button exists before attaching event
    if (close) {
        // When the user clicks on <span> (x), close the modal
        close.addEventListener('click', function() {
            closeModal();
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });

    // Close modal function
    function closeModal() {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = ''; // Re-enable scrolling
        }
    }

    // Initialize auto-hiding alerts
    const alerts = document.querySelectorAll('.auth-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Tab functionality
function openTab(evt, tabName) {
    // Declare variables
    let i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}