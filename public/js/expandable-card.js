// Get all expandable card
const expandableCards = document.querySelectorAll('.expandable-card')

// Add event listener to each expandable card
expandableCards.forEach(function (card) {
    const expandableCardButton = card.querySelector('.expandable-card-button')
    const expandableCardBody = card.querySelector('.expandable-card-body')
    const expandableCardExpandIcon = card.querySelector('.expandable-card-expand-icon')

    expandableCardButton.addEventListener('click', function (event) {
        event.preventDefault()
        expandableCardBody.classList.toggle('hidden')
        expandableCardExpandIcon.classList.toggle('rotate-180')
    })
})