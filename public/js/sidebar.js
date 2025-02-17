const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const content = document.querySelector(".content"); // Konten utama

// Ensure these heights match the CSS sidebar height values
let collapsedSidebarWidth = "85px"; // Lebar sidebar saat collapsed
let fullSidebarWidth = "270px"; // Lebar sidebar penuh

// Toggle sidebar's collapsed state
sidebarToggler.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    adjustContentMargin(); // Sesuaikan margin konten saat sidebar berubah
});

// Update sidebar height and menu toggle text
const toggleMenu = (isMenuActive) => {
    sidebar.style.height = isMenuActive ? `${sidebar.scrollHeight}px` : "56px";
    menuToggler.querySelector("span").innerText = isMenuActive
        ? "close"
        : "menu";
};

// Toggle menu-active class and adjust height
menuToggler.addEventListener("click", () => {
    toggleMenu(sidebar.classList.toggle("menu-active"));
});

// Menyesuaikan margin konten berdasarkan status sidebar
const adjustContentMargin = () => {
    content.style.marginLeft = sidebar.classList.contains("collapsed")
        ? collapsedSidebarWidth
        : fullSidebarWidth;
};

// Atur margin awal saat halaman dimuat
adjustContentMargin();

// (Optional code): Adjust sidebar height on window resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        sidebar.style.height = "calc(100vh - 32px)";
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
    }
    adjustContentMargin();
});
