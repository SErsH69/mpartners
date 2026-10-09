/**
 * Списки карточек: табы рубрик и кнопка «Показать ещё».
 *
 * Оба механизма управляют одними и теми же карточками, поэтому живут в
 * одном месте: видимость карточки считается один раз — по выбранной рубрике
 * и по текущему пределу показа. Кнопка прячется, когда показывать нечего.
 */

const initCards = () => {
    document.querySelectorAll('[data-more-list]').forEach((list) => {
        const cards = [...list.children]
        const section = list.closest('section') || document
        const tabs = section.querySelector('[data-rubric-tabs]')
        const button = section.querySelector('[data-more]')
        const step = Math.max(parseInt(list.dataset.moreStep || '0', 10) || cards.length, 1)

        let rubric = ''
        let limit = step

        const matches = (card) => {
            if (!rubric) {
                return true
            }

            return (card.dataset.rubrics || '').split(' ').filter(Boolean).includes(rubric)
        }

        const render = () => {
            let shown = 0

            cards.forEach((card) => {
                const fits = matches(card)
                const visible = fits && shown < limit

                card.hidden = !visible

                if (fits) {
                    shown++
                }
            })

            if (button) {
                // Прячем кнопку, когда скрытых карточек не осталось.
                button.hidden = shown <= limit
            }
        }

        if (tabs) {
            const links = [...tabs.querySelectorAll('a[data-rubric]')]
            const sortable = cards.some((card) => card.dataset.rubrics)

            if (sortable) {
                tabs.addEventListener('click', (event) => {
                    const link = event.target.closest('a[data-rubric]')

                    if (!link) {
                        return
                    }

                    event.preventDefault()
                    rubric = link.dataset.rubric || ''
                    limit = step

                    links.forEach((item) => {
                        item.parentElement.classList.toggle('pr-chip--active', (item.dataset.rubric || '') === rubric)
                    })

                    render()
                })
            }
        }

        if (button) {
            button.addEventListener('click', () => {
                limit += step
                render()
            })
        }

        render()
    })
}

export default initCards
