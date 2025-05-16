
const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");
const uiSidebar = !(localStorage.getItem('sidebar-toggle'))? localStorage.setItem('sidebar-toggle', 'false'): localStorage.getItem('sidebar-toggle');

if (uiSidebar === 'true') {
    sidebar.classList.add('close');
} else {
    sidebar.classList.remove('close');
}

toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    if (sidebar.classList.contains("close")) {
        localStorage.setItem('sidebar-toggle', 'true');
    } else {
        localStorage.setItem('sidebar-toggle', 'false');
    }
});
const themeUi = !(localStorage.getItem('dark-mode'))? localStorage.setItem('dark-mode', 'false'): localStorage.getItem('dark-mode');
console.log(themeUi);

if (themeUi === 'true') {
    body.classList.add('dark');
    modeText.innerText = "Light mode";
} else {
    body.classList.remove('dark');
    modeText.innerText = "Dark mode";
}


searchBtn.addEventListener("click", () => {
    sidebar.classList.remove("close");
});

modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
        modeText.innerText = "Light mode";
        localStorage.setItem('dark-mode', 'true');
    } else {
        modeText.innerText = "Dark mode";
        localStorage.setItem('dark-mode', 'false');
    }
});
