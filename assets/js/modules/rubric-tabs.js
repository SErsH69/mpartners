/**
 * Табы рубрик в списках разделов.
 *
 * Переключают карточки на месте, без перезагрузки. Если скрипт не
 * отработал, табы остаются обычными ссылками на архив рубрики.
 */

const initRubricTabs = () => {
    const tabs = document.querySelector('[data-rubric-tabs]')
    const list = document.querySelector('[data-rubric-list]')

    if (!tabs || !list) {
        return
    }

    const cards = [...list.children]
    const links = [...tabs.querySelectorAll('a[data-rubric]')]

    // Фильтровать нечего, пока у карточек нет рубрик.
    if (!cards.some((card) => card.dataset.rubrics)) {
        return
    }

    const apply = (rubric) => {
        cards.forEach((card) => {
            const own = (card.dataset.rubrics || '').split(' ').filter(Boolean)

            card.hidden = Boolean(rubric) && !own.includes(rubric)
        })

        links.forEach((link) => {
            link.parentElement.classList.toggle('pr-chip--active', (link.dataset.rubric || '') === rubric)
        })
    }

    tabs.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-rubric]')

        if (!link) {
            return
        }

        event.preventDefault()
        apply(link.dataset.rubric || '')
    })
}

export default initRubricTabs
