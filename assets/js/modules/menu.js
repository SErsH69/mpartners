/**
 * Выпадающее меню шапки.
 */
export default function initMenu() {
    const panel = document.getElementById('site-menu')

    if (!panel) {
        return
    }

    const openers = document.querySelectorAll('[data-menu-open]')
    const closers = panel.querySelectorAll('[data-menu-close]')

    const setOpen = (isOpen) => {
        panel.classList.toggle('is-open', isOpen)
        panel.hidden = false
        document.body.classList.toggle('is-locked', isOpen)
        openers.forEach((btn) => btn.setAttribute('aria-expanded', String(isOpen)))

        if (isOpen) {
            const first = panel.querySelector('a, button')
            if (first) {
                first.focus()
            }
        }
    }

    openers.forEach((btn) => btn.addEventListener('click', () => setOpen(true)))
    closers.forEach((btn) => btn.addEventListener('click', () => setOpen(false)))

    panel.addEventListener('click', (event) => {
        if (event.target === panel) {
            setOpen(false)
        }
    })

    document.addEventListener('keydown', (event) => {
        if ('Escape' === event.key && panel.classList.contains('is-open')) {
            setOpen(false)
        }
    })

    panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)))
}
