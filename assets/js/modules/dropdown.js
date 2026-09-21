/**
 * Выпадающее меню шапки на ПК (по мотивам flabbergast.agency).
 * Открывается из полосы «Меню», закрывается по клику вне, Esc и по ссылке.
 */
export default function initDropdown() {
    document.querySelectorAll('[data-dropdown]').forEach((root) => {
        const toggle = root.querySelector('[data-dropdown-toggle]')
        const panel = root.querySelector('.nav-drop')

        if (!toggle || !panel) {
            return
        }

        // Порядковый номер для лесенки появления пунктов
        panel.querySelectorAll('.nav-drop__item').forEach((item, index) => {
            item.style.setProperty('--i', index)
        })

        const setOpen = (isOpen) => {
            root.classList.toggle('is-open', isOpen)
            toggle.setAttribute('aria-expanded', String(isOpen))
            panel.setAttribute('aria-hidden', String(!isOpen))
        }

        toggle.addEventListener('click', (event) => {
            event.stopPropagation()
            setOpen(!root.classList.contains('is-open'))
        })

        document.addEventListener('click', (event) => {
            if (!root.contains(event.target)) {
                setOpen(false)
            }
        })

        document.addEventListener('keydown', (event) => {
            if ('Escape' === event.key && root.classList.contains('is-open')) {
                setOpen(false)
                toggle.focus()
            }
        })

        panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)))
    })
}
