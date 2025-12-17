// Nav hamburger & menu
const navMenu = document.querySelector('#nav-menu')
const navMenuPanel = navMenu.querySelector('.nav-panel')

navMenu.addEventListener('click', function () {
    navMenuPanel.classList.toggle('hidden')
})

document.addEventListener('click', function (event) {
    const target = event.target
    if (!navMenu.contains(target)) {
        navMenuPanel.classList.add('hidden')
    }
})

// Get all the dropdown items
const navItemDropdown = document.querySelectorAll('.nav-item-dropdown')

// Add event listener to each dropdown item
navItemDropdown.forEach(function (item) {
    const navItemDropdownButton = item.querySelector('.nav-item-dropdown-button')
    const navPanel = item.querySelector('.nav-panel')

    navItemDropdownButton.addEventListener('click', function () {
        navPanel.classList.toggle('hidden')
    })

    document.addEventListener('click', function (event) {
        const target = event.target
        if (!item.contains(target)) {
            navPanel.classList.add('hidden')
        }
    })
})
