const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const content = document.querySelector(".content"); // Konten utama

// Lebar dan tinggi sidebar
let collapsedSidebarWidth = "85px"; // Untuk desktop
let fullSidebarWidth = "270px"; 
let collapsedSidebarHeight = "56px"; // Untuk mobile
let expandedSidebarHeight = "100px"; // Tinggi saat menu aktif di mobile
let fullSidebarHeight = "calc(100vh - 32px)";

// Fungsi toggle dropdown
const toggleDropdown = (dropdown, menu, isOpen) => {
    dropdown.classList.toggle("open", isOpen);
    menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
};

// Tutup semua dropdown yang terbuka
const closeAllDropdowns = () => {
    document.querySelectorAll(".dropdown-container1.open").forEach((openDropdown) => {
        toggleDropdown(openDropdown, openDropdown.querySelector(".dropdown-menu1"), false);
    });
};

// Event dropdown toggle
document.querySelectorAll(".dropdown-toggle1").forEach((dropdownToggle) => {
    dropdownToggle.addEventListener("click", (e) => {
        e.preventDefault();
        const dropdown = dropdownToggle.closest(".dropdown-container1");
        const menu = dropdown.querySelector(".dropdown-menu1");
        const isOpen = dropdown.classList.contains("open");

        closeAllDropdowns();
        toggleDropdown(dropdown, menu, !isOpen);
    });
});

// Event toggle sidebar (collapse)
document.querySelectorAll(".sidebar-toggler").forEach((button) => {
    button.addEventListener("click", () => {
        closeAllDropdowns();
        sidebar.classList.toggle("collapsed");
    });
});

// Fungsi untuk toggle menu (mobile)
const toggleMenu = (isMenuActive) => {
    if (window.innerWidth < 1024) {
        sidebar.style.height = isMenuActive ? expandedSidebarHeight : collapsedSidebarHeight;
        const icon = menuToggler.querySelector("span");
        if (icon) icon.innerText = isMenuActive ? "close" : "menu";
    }
};

// Event toggle menu (hamburger menu di mobile)
if (menuToggler) {
    menuToggler.addEventListener("click", () => {
        const isActive = sidebar.classList.toggle("menu-active");
        toggleMenu(isActive);
    });
}

// Fungsi sesuaikan tinggi konten
const adjustContentMargin = () => {
    if (window.innerWidth >= 1024) {
        content && (content.style.marginLeft = sidebar.classList.contains("collapsed") ? collapsedSidebarWidth : fullSidebarWidth);
    } else {
        content && (content.style.marginLeft = "0");
    }
};

// Responsif saat resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        sidebar.style.height = fullSidebarHeight;
        sidebar.classList.remove("menu-active");
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = collapsedSidebarHeight;
        toggleMenu(sidebar.classList.contains("menu-active"));
    }
    adjustContentMargin();
});
