/**
 * Горизонтальные ленты (отрасли, письма, команда): перетаскивание мышью
 * в дополнение к обычной прокрутке и свайпу.
 */
export default function initDragScroll() {
    // В мобильном макете лента отраслей показана с середины.
    const centerOnMobile = () => {
        if (!window.matchMedia('(max-width: 600px)').matches) {
            return
        }

        document.querySelectorAll('[data-center-mobile]').forEach((track) => {
            const items = track.children
            const mid = items[Math.floor(items.length / 2)]

            if (!mid) {
                return
            }

            // В макете центр средней карточки стоит на 177px из 375.
            track.scrollLeft = mid.offsetLeft + mid.offsetWidth / 2 - track.clientWidth * 0.4725
        })
    }

    centerOnMobile()
    window.addEventListener('load', centerOnMobile)

    // Точки под лентой отмечают карточку, ближайшую к левому краю.
    document.querySelectorAll('[data-dots]').forEach((track) => {
        const dots = document.getElementById(track.dataset.dots)

        if (!dots) {
            return
        }

        const items = [...track.children]
        const update = () => {
            const left = track.getBoundingClientRect().left
            let active = 0
            let best = Infinity

            items.forEach((item, index) => {
                const dist = Math.abs(item.getBoundingClientRect().left - left - 20)
                if (dist < best) {
                    best = dist
                    active = index
                }
            })

            ;[...dots.children].forEach((dot, index) => dot.classList.toggle('is-active', index === active))
        }

        track.addEventListener('scroll', update, { passive: true })
    })

    document.querySelectorAll('[data-drag-scroll]').forEach((track) => {
        let startX = 0
        let startScroll = 0
        let isDown = false
        let moved = false

        const stop = () => {
            if (!isDown) {
                return
            }

            isDown = false
            track.classList.remove('is-dragging')
        }

        track.addEventListener('pointerdown', (event) => {
            if (0 !== event.button) {
                return
            }

            isDown = true
            moved = false
            startX = event.clientX
            startScroll = track.scrollLeft
            track.classList.add('is-dragging')
        })

        track.addEventListener('pointermove', (event) => {
            if (!isDown) {
                return
            }

            const delta = event.clientX - startX

            if (Math.abs(delta) > 4) {
                moved = true
            }

            track.scrollLeft = startScroll - delta
        })

        track.addEventListener('pointerup', stop)
        track.addEventListener('pointerleave', stop)
        track.addEventListener('pointercancel', stop)

        // Не даём «протащенной» ленте открыть ссылку.
        track.addEventListener(
            'click',
            (event) => {
                if (moved) {
                    event.preventDefault()
                    event.stopPropagation()
                    moved = false
                }
            },
            true,
        )
    })
}
