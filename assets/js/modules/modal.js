/**
 * Попап с формой.
 *
 * Открывается по любой ссылке на форму (`#form`, `#modal`) и по элементам с
 * `data-modal-open`; закрывается по крестику, фону и Esc. Пока попапа на
 * странице нет, ссылки работают как обычные якоря.
 */

const SELECTOR = 'a[href="#form"], a[href="#modal"], [data-modal-open]'

const initModal = () => {
    const modal = document.getElementById('mp-modal')

    if (!modal) {
        return
    }

    const dialog = modal.querySelector('.modal__dialog')
    let opener = null

    const open = (event) => {
        const trigger = event.target.closest(SELECTOR)

        if (!trigger || trigger.closest('#mp-modal')) {
            return
        }

        event.preventDefault()
        opener = trigger
        modal.hidden = false
        document.body.classList.add('is-modal-open')

        const field = dialog.querySelector('input:not([type="hidden"]):not([type="checkbox"])')

        if (field) {
            field.focus({ preventScroll: true })
        }
    }

    const close = () => {
        if (modal.hidden) {
            return
        }

        modal.hidden = true
        document.body.classList.remove('is-modal-open')

        if (opener) {
            opener.focus({ preventScroll: true })
            opener = null
        }
    }

    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-modal-close]')) {
            event.preventDefault()
            close()

            return
        }

        open(event)
    })

    document.addEventListener('keydown', (event) => {
        if ('Escape' === event.key) {
            close()
        }
    })

    // Отправленную форму закрываем, чтобы человек увидел ответ и не завис.
    document.addEventListener('wpcf7mailsent', close)
}

export default initModal
