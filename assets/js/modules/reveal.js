/**
 * Появление текста из размытия (по мотивам verteal.com):
 * opacity 0 → 1, blur 14px → 0, сдвиг 22px → 0, 0.9s, cubic-bezier(.16, 1, .3, 1).
 *
 * - Заголовки делятся на слова; слова одной визуальной строки получают
 *   одинаковую задержку, следующая строка стартует на 0.14s позже.
 * - Прочие блоки появляются целиком со своей задержкой.
 * - Первые два блока (первый экран и «Преимущества») анимируются сразу после
 *   загрузки, остальное — при попадании в экран.
 */

const LINE_STEP = 0.14

// Заголовки, которые разбиваются на строки
const HEADINGS = [
    '.h-hero',
    '.h-section',
    '.adv-card__title',
    '.help-card__title',
    '.case-card__title',
    '.post-card__title',
    '.award__title',
]

// Блоки, которые появляются целиком (в порядке появления внутри секции)
const BLOCKS = [
    '.hero__subtitle',
    '.hero__actions',
    '.hero__note',
    '.advantages__note',
    '.adv-card',
    '.section-head__note',
    '.industries__note',
    '.reviews__note',
    '.team__note',
    '.quiz__subtitle',
    '.quiz__progress',
    '.quiz__step',
    '.quiz__expert',
    '.experience__text',
    '.experience__actions',
    '.experience__photo',
    '.experience__quote',
    '.help-card',
    '.industries__marquee',
    '.case-card',
    '.letter',
    '.post-card',
    '.award',
    '.contact__text',
    '.contact__socials',
    '.contact__form',
    '.member',
    '.geography__note',
    '.geography__cities',
    '.geography__map',
    '.practice__all',
    '.blog__more',
    '.team__actions',
]

// Цитата в «Опыте» содержит подсветку <mark> — её не дробим, показываем целиком.
const SKIP_SPLIT = '.experience__quote'

function splitWords(el) {
    const walk = (node) => {
        ;[...node.childNodes].forEach((child) => {
            if (3 === child.nodeType) {
                const parts = child.textContent.split(/(\s+)/)
                const frag = document.createDocumentFragment()

                parts.forEach((part) => {
                    if (!part) {
                        return
                    }

                    if (/^\s+$/.test(part)) {
                        frag.appendChild(document.createTextNode(part))
                        return
                    }

                    const span = document.createElement('span')
                    span.className = 'rv-word'
                    span.textContent = part
                    frag.appendChild(span)
                })

                child.replaceWith(frag)
            } else if (1 === child.nodeType && 'BR' !== child.tagName) {
                walk(child)
            }
        })
    }

    walk(el)
}

// Задержка по номеру строки: слова с одинаковым top — одна строка.
function assignLineDelays(el, base) {
    const words = [...el.querySelectorAll('.rv-word')]
    const tops = []

    words.forEach((word) => {
        const top = Math.round(word.offsetTop)
        let line = tops.findIndex((t) => Math.abs(t - top) < 4)

        if (-1 === line) {
            tops.push(top)
            line = tops.length - 1
        }

        word.style.setProperty('--rv-delay', `${(base + line * LINE_STEP).toFixed(2)}s`)
    })

    return tops.length
}

function prepare(root) {
    // Задержки внутри секции идут по порядку элементов в документе.
    root.querySelectorAll('section, .footer').forEach((section) => {
        const items = [...section.querySelectorAll([...HEADINGS, ...BLOCKS].join(','))]
        let delay = 0

        items.forEach((el) => {
            // Вложенные в уже анимируемый блок элементы едут вместе с ним.
            if (el.parentElement.closest('[data-reveal], [data-reveal-block]')) {
                return
            }

            const isHeading = HEADINGS.some((s) => el.matches(s)) && !el.matches(SKIP_SPLIT)

            if (isHeading) {
                el.setAttribute('data-reveal', '')
                splitWords(el)
                const lines = assignLineDelays(el, Math.min(delay, 0.9))
                delay += Math.max(1, lines) * LINE_STEP
            } else {
                el.setAttribute('data-reveal-block', '')
                el.style.setProperty('--rv-delay', `${Math.min(delay, 0.9).toFixed(2)}s`)
                delay += 0.08
            }
        })
    })
}

export default function initReveal() {
    if (!('IntersectionObserver' in window)) {
        document.documentElement.classList.remove('js-reveal-pending')
        return
    }

    const html = document.documentElement

    const start = () => {
        html.classList.add('js-reveal-ready')
        prepare(document)
        // Страница была скрыта до разметки (см. header.php) — показываем.
        html.classList.remove('js-reveal-pending')

        const targets = document.querySelectorAll('[data-reveal], [data-reveal-block]')

        // Первые два блока — сразу после загрузки
        document
            .querySelectorAll('.hero [data-reveal], .hero [data-reveal-block], .advantages [data-reveal], .advantages [data-reveal-block]')
            .forEach((el) => el.classList.add('is-revealed'))

        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed')
                        io.unobserve(entry.target)
                    }
                })
            },
            { rootMargin: '0px 0px -8% 0px' },
        )

        targets.forEach((el) => {
            if (!el.classList.contains('is-revealed')) {
                io.observe(el)
            }
        })

        // После прокрутки задержки по строкам больше не нужны при ресайзе —
        // слова переносятся естественно, т.к. остаются inline-block.
    }

    // Строки считаются по реальному шрифту, поэтому ждём загрузки шрифтов.
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(start)
    } else {
        start()
    }
}
