/**
 * Форма связи: выбор канала связи чипами и отправка через admin-ajax.
 */
export default function initContactForm() {
    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        const chips = [...form.querySelectorAll('.chip-toggle')]
        const message = form.querySelector('.contact__message')
        const submit = form.querySelector('[type="submit"]')

        form.addEventListener('change', (event) => {
            if ('channel' !== event.target.name) {
                return
            }

            chips.forEach((chip) => chip.classList.toggle('is-active', chip.contains(event.target)))
        })

        const say = (text, isError) => {
            if (!message) {
                return
            }

            message.textContent = text
            message.hidden = !text
            message.classList.toggle('is-error', Boolean(isError))
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault()

            if (!form.reportValidity()) {
                return
            }

            submit.disabled = true
            say('')

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    credentials: 'same-origin',
                })
                const payload = await response.json()

                if (payload.success) {
                    form.reset()
                    chips.forEach((chip, index) => chip.classList.toggle('is-active', 1 === index))
                }

                say(payload.data && payload.data.message, !payload.success)
            } catch (error) {
                say('Не удалось отправить заявку. Попробуйте позже.', true)
            } finally {
                submit.disabled = false
            }
        })
    })
}
