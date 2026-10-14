/**
 * Колода этапов защиты на лендинге.
 *
 * Пока высокая обёртка проходит через экран, колода закреплена, а карточки
 * по очереди выезжают снизу и встают в стопку — листается обычным скроллом.
 * Клик по карточке прокручивает страницу к её шагу. Ниже 1025px колода
 * превращается в обычный список, и скрипт в неё не вмешивается.
 */

const DESKTOP = '(min-width: 1025px)'

const initStages = () => {
    document.querySelectorAll('[data-stages]').forEach((scroll) => {
        const deck = scroll.querySelector('[data-stages-deck]')
        const cards = [...scroll.querySelectorAll('[data-stage]')]

        if (!deck || cards.length < 2) {
            return
        }

        const media = window.matchMedia(DESKTOP)
        const pin = scroll.firstElementChild
        let frame = 0

        // Длина прокрутки, за которую колода пролистывается целиком.
        const travel = () => Math.max(scroll.offsetHeight - pin.offsetHeight, 0)

        const reset = () => {
            deck.classList.remove('is-scrolly')
            cards.forEach((card) => {
                card.style.removeProperty('--p')
                card.classList.remove('is-front')
            })
        }

        const update = () => {
            frame = 0

            if (!media.matches) {
                return
            }

            const total = travel()
            const passed = Math.min(Math.max(-scroll.getBoundingClientRect().top, 0), total)
            const position = total > 0 ? (passed / total) * (cards.length - 1) : cards.length - 1
            const front = Math.round(position)

            cards.forEach((card, index) => {
                const progress = Math.min(Math.max(position - index + 1, 0), 1)

                card.style.setProperty('--p', progress.toFixed(3))
                card.classList.toggle('is-front', index === front)
            })
        }

        const onScroll = () => {
            if (frame) {
                return
            }

            frame = window.requestAnimationFrame(update)
        }

        const apply = () => {
            if (!media.matches) {
                reset()

                return
            }

            deck.classList.add('is-scrolly')
            update()
        }

        deck.addEventListener('click', (event) => {
            const head = event.target.closest('[data-stage-open]')

            if (!head || !media.matches) {
                return
            }

            const index = cards.indexOf(head.closest('[data-stage]'))
            const total = travel()

            if (index < 0 || 0 === total) {
                return
            }

            window.scrollTo({
                top: scroll.getBoundingClientRect().top + window.scrollY + (total * index) / (cards.length - 1),
                behavior: 'smooth',
            })
        })

        window.addEventListener('scroll', onScroll, { passive: true })
        window.addEventListener('resize', apply)

        if (media.addEventListener) {
            media.addEventListener('change', apply)
        }

        apply()
    })
}

export default initStages
