const getStoredTheme = () => localStorage.getItem('theme')
const setStoredTheme = theme => localStorage.setItem('theme', theme)

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme()
    if (storedTheme) {
        return storedTheme
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}

const setTheme = theme => {
    document.documentElement.setAttribute('data-bs-theme', theme)
}

themeSwitcher = document.documentElement.querySelector('#themeSwitcher');
themeSwitcher.checked = getPreferredTheme() === 'dark';

themeSwitcher.addEventListener('click', (e) => {
    if (!e.currentTarget.checked) {
        setStoredTheme('light');
        console.log(getStoredTheme())
    } else {
        setStoredTheme('dark');
        console.log(getStoredTheme())
    }

    setTheme(getPreferredTheme())
})
