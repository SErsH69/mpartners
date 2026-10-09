/**
 * Панель поиска в шапке.
 *
 * Кнопка с лупой раскрывает полосу с полем, Esc и клик мимо её закрывают.
 */

const initSearch = () => {
    const panel = document.getElementById('site-search')

    if (!panel) {
        return
    }

    const input = panel.querySelector('.search-form__input')
    const toggles = document.querySelectorAll('[data-search-open]')

    const setState = (open) => {
        panel.hidden = !open
        toggles.forEach((button) => button.setAttribute('aria-expanded', String(open)))

        if (open && input) {
            input.focus({ preventScroll: true })
        }
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-search-open]')) {
            event.preventDefault()
            setState(panel.hidden)

            return
        }

        if (event.target.closest('[data-search-close]')) {
            event.preventDefault()
            setState(false)

            return
        }

        if (!panel.hidden && !event.target.closest('#site-search')) {
            setState(false)
        }
    })

    document.addEventListener('keydown', (event) => {
        if ('Escape' === event.key) {
            setState(false)
        }
    })

    // Пустой запрос отправлять незачем — иначе WordPress покажет всё подряд.
    panel.addEventListener('submit', (event) => {
        if (input && '' === input.value.trim()) {
            event.preventDefault()
            input.focus()
        }
    })
}

export default initSearch
