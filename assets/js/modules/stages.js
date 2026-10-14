/**
 * Колода этапов защиты на лендинге.
 *
 * Карточки лежат стопкой: клик по верхнему краю поднимает этап вперёд.
 * Без скрипта видна последняя карточка — как в макете.
 */

const initStages = () => {
    document.querySelectorAll('[data-stages]').forEach((deck) => {
        const cards = [...deck.querySelectorAll('[data-stage]')]

        if (!cards.length) {
            return
        }

        // Порядок в колоде: чем дальше в списке, тем ближе к зрителю.
        let order = cards.map((card, index) => index)

        const render = () => {
            order.forEach((cardIndex, position) => {
                const card = cards[cardIndex]

                card.style.setProperty('--i', String(position))
                card.classList.toggle('is-active', position === order.length - 1)
            })
        }

        deck.addEventListener('click', (event) => {
            const head = event.target.closest('[data-stage-open]')

            if (!head) {
                return
            }

            const card = head.closest('[data-stage]')
            const cardIndex = cards.indexOf(card)

            if (cardIndex < 0 || order[order.length - 1] === cardIndex) {
                return
            }

            order = order.filter((item) => item !== cardIndex).concat(cardIndex)
            render()
        })

        render()
    })
}

export default initStages
