// Modo dark y light
const btnSwitch = document.querySelector('.container-switch #checkbox');


// dark mode
btnSwitch.addEventListener('click', () => {
    console.log("Toggle dark mode");
    document.body.classList.toggle('dark');
    
    // Guardamos el modo en localstorage.
    if (document.body.classList.contains('dark')) {
        console.log("Setting dark mode to true");
        localStorage.setItem('dark-mode', 'true');
    }
    else {
        console.log("Setting dark mode to false");
        localStorage.setItem('dark-mode', 'false');
    }
});

// get localstorage theme
const localStorageTheme = localStorage.getItem('dark-mode');

// set dark mode
if (localStorageTheme === 'true') {
    console.log("Dark mode is set to true");
    document.body.classList.add('dark');
    btnSwitch.checked = true;
} else if (localStorageTheme === 'false') {
    console.log("Dark mode is set to false");
    document.body.classList.remove('dark');
    btnSwitch.checked = false;
}