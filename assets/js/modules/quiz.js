/**
 * Квиз «Консультация специалиста»: шаги, шкала прогресса, навигация.
 *
 * Разметка приходит из Contact Form 7, поэтому отправку мы не перехватываем —
 * только не даём перейти дальше, пока на шаге не выбран ответ.
 */
export default function initQuiz() {
    document.querySelectorAll('[data-quiz]').forEach((host) => {
        const steps = [...host.querySelectorAll('[data-quiz-step]')]
        const bars = [...host.querySelectorAll('.quiz__progress-item')]
        const progress = host.querySelector('.quiz__progress')
        const prev = host.querySelector('[data-quiz-prev]')
        const next = host.querySelector('[data-quiz-next]')

        if (!steps.length) {
            return
        }

        let current = 0

        // Шаг пройден, когда на нём выбран вариант; последний шаг — контакты.
        const isAnswered = (index) => {
            const step = steps[index]
            const radios = step.querySelectorAll('input[type="radio"]')

            return !radios.length || [...radios].some((radio) => radio.checked)
        }

        const render = () => {
            steps.forEach((step, index) => {
                const isActive = index === current
                step.classList.toggle('is-active', isActive)
                step.hidden = !isActive
            })

            bars.forEach((bar, index) => bar.classList.toggle('is-active', index <= current))

            if (progress) {
                progress.setAttribute('aria-valuenow', String(current + 1))
            }

            if (prev) {
                prev.hidden = 0 === current
            }

            if (next) {
                next.hidden = current === steps.length - 1
                next.disabled = !isAnswered(current)
            }
        }

        const go = (delta) => {
            if (delta > 0 && !isAnswered(current)) {
                steps[current].classList.add('is-invalid')

                return
            }

            steps[current].classList.remove('is-invalid')
            current = Math.min(Math.max(current + delta, 0), steps.length - 1)
            render()
        }

        if (prev) {
            prev.addEventListener('click', () => go(-1))
        }

        if (next) {
            next.addEventListener('click', () => go(1))
        }

        // Выбор ответа снимает блокировку и переводит на следующий шаг.
        host.addEventListener('change', (event) => {
            if ('radio' !== event.target.type) {
                return
            }

            steps[current].classList.remove('is-invalid')
            render()

            if (current < steps.length - 1) {
                window.setTimeout(() => go(1), 220)
            }
        })

        // Enter в полях контактов не должен «перелистывать» шаги.
        host.addEventListener('keydown', (event) => {
            if ('Enter' === event.key && 'radio' === event.target.type) {
                event.preventDefault()
                event.target.checked = true
                event.target.dispatchEvent(new Event('change', { bubbles: true }))
            }
        })

        render()
    })
}
