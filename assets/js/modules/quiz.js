/**
 * Квиз «Консультация специалиста»: 4 шага, шкала прогресса, кнопки навигации.
 */
export default function initQuiz() {
    document.querySelectorAll('[data-quiz]').forEach((form) => {
        const steps = [...form.querySelectorAll('[data-quiz-step]')]
        const bars = [...form.querySelectorAll('.quiz__progress-item')]
        const progress = form.querySelector('.quiz__progress')
        const prev = form.querySelector('[data-quiz-prev]')
        const next = form.querySelector('[data-quiz-next]')

        if (!steps.length) {
            return
        }

        let current = 0

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
            }
        }

        const go = (delta) => {
            current = Math.min(Math.max(current + delta, 0), steps.length - 1)
            render()
        }

        if (prev) {
            prev.addEventListener('click', () => go(-1))
        }

        if (next) {
            next.addEventListener('click', () => go(1))
        }

        // Выбор ответа переводит на следующий шаг.
        form.addEventListener('change', (event) => {
            if ('radio' === event.target.type && current < steps.length - 1) {
                window.setTimeout(() => go(1), 220)
            }
        })

        form.addEventListener('submit', (event) => event.preventDefault())

        render()
    })
}
