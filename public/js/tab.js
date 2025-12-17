// Get all tab buttons
const tabs = document.querySelectorAll('.tab')

// Tab link classes
const activeTabClass = ['text-white', 'bg-primary']
const inactiveTabClass = ['text-primary', 'bg-slate-200']

// Add event listener to each expandable card
tabs.forEach(function (tab) {
    const tabLinks = tab.querySelectorAll('.tab-link')
    const tabContents = tab.querySelectorAll('.tab-content')

    // Set first tab as active
    tabLinks[0].classList.add(...activeTabClass)
    
    tabLinks.forEach(function (tabLink) {
        // Set all other tabs as inactive
        if (tabLink != tabLinks[0]) {
            tabLink.classList.add(...inactiveTabClass)
        }

        tabLink.addEventListener('click', function (event) {
            event.preventDefault()

            tabLinks.forEach(function (link) {
                // Remove all tab link classes
                link.classList.remove(...activeTabClass, ...inactiveTabClass)

                // Add active class to clicked tab
                if (link == tabLink) {
                    link.classList.add(...activeTabClass)
                    return
                }

                // Add inactive class to all other tabs
                link.classList.add(...inactiveTabClass)
            })

            // Hide all tab contents
            tabContents.forEach(function (tabContent) {
                tabContent.classList.add('hidden')
            })

            // Show target tab content
            const targetTab = tab.querySelector(this.getAttribute('href'))
            targetTab.classList.remove('hidden')
        })
    })
})