/**
 * Цифры первого экрана проявляются под курсором (по мотивам verteal.com).
 *
 * Сам узор невидим: на canvas рисуется маска из ячеек сетки, по которой
 * вырезается картинка. Каждая ячейка — одна цифра макета, у неё своя задержка
 * вспышки и своя скорость затухания, поэтому след рассыпается неравномерно.
 */

const RADIUS = 96 // радиус обзора вокруг курсора, px
const STEP = 10 // шаг интерполяции между позициями курсора, px
const JUMP = 420 // прыжок длиннее — рисуем одной точкой, а не линией
const IDLE_FRAMES = 150 // кадров без движения мыши до остановки цикла
const FALLOFF = 1.6 // крутизна затухания от центра к краю обзора

// Сетка цифр в системе координат исходного узора (1531×559).
const ART_W = 1531
const ART_H = 559
const CELL_W = 8.55
const CELL_H = 10.8
const ORIGIN_X = -4.35
const ORIGIN_Y = -0.95
const COLS = Math.ceil((ART_W - ORIGIN_X) / CELL_W)
const ROWS = Math.ceil((ART_H - ORIGIN_Y) / CELL_H)

const rand = (min, max) => min + Math.random() * (max - min)

const initHeroTrail = () => {
    const hero = document.querySelector('.hero')

    if (!hero) {
        return
    }

    const backdrop = hero.querySelector('.hero__backdrop')
    const canvas = hero.querySelector('.hero__trail')
    const image = hero.querySelector('.hero__pattern')

    if (!backdrop || !canvas || !image) {
        return
    }

    // Эффект только для мыши на десктопном макете и при отключённом
    // «уменьшении движения»: на планшете и мобилке узор другой.
    const pointer = window.matchMedia('(hover: hover) and (pointer: fine)')
    const desktop = window.matchMedia('(min-width: 1025px)')
    const calm = window.matchMedia('(prefers-reduced-motion: reduce)')

    const ctx = canvas.getContext('2d')

    if (!ctx) {
        return
    }

    const cells = new Map()

    let rect = null
    let art = null
    let scaleX = 1
    let scaleY = 1
    let dpr = 1
    let active = false
    let running = false
    let idle = IDLE_FRAMES
    let prev = null
    let next = null

    const allowed = () => pointer.matches && desktop.matches && !calm.matches

    // Положение полосы пересчитывается на каждом скролле, размеры canvas —
    // только когда они действительно поменялись: смена width обнуляет холст.
    const measure = () => {
        rect = backdrop.getBoundingClientRect()

        const box = image.getBoundingClientRect()

        art = {
            x: box.left - rect.left,
            y: box.top - rect.top,
            w: box.width,
            h: box.height,
        }

        scaleX = art.w / ART_W
        scaleY = art.h / ART_H
        dpr = Math.min(window.devicePixelRatio || 1, 2)

        const width = Math.round(rect.width * dpr)
        const height = Math.round(rect.height * dpr)

        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width
            canvas.height = height
            prev = null
            cells.clear()
        }

        ctx.setTransform(dpr, 0, 0, dpr, 0, 0)
    }

    // Вспышка одной ячейки: сила зависит от расстояния до курсора, а задержка
    // и скорость затухания — случайные, иначе цифры гаснут одной стенкой.
    const ignite = (i, j, force) => {
        if (i < 0 || j < 0 || i >= COLS || j >= ROWS) {
            return
        }

        const key = j * 256 + i
        const cell = cells.get(key)
        const target = Math.min(force * rand(1, 1.35), 1)

        if (cell) {
            if (target > cell.target) {
                cell.target = target
                cell.wait = Math.min(cell.wait, 2)
            }

            return
        }

        cells.set(key, {
            i,
            j,
            value: 0,
            target,
            wait: Math.round(rand(0, 9)),
            rise: rand(0.14, 0.4),
            decay: rand(0.955, 0.994),
            phase: rand(0, Math.PI * 2),
            speed: rand(0.04, 0.11),
        })
    }

    const splash = (x, y) => {
        const cx = (x - art.x) / scaleX
        const cy = (y - art.y) / scaleY
        const rx = RADIUS / scaleX
        const ry = RADIUS / scaleY
        const i0 = Math.floor((cx - rx - ORIGIN_X) / CELL_W)
        const i1 = Math.ceil((cx + rx - ORIGIN_X) / CELL_W)
        const j0 = Math.floor((cy - ry - ORIGIN_Y) / CELL_H)
        const j1 = Math.ceil((cy + ry - ORIGIN_Y) / CELL_H)

        for (let j = j0; j <= j1; j += 1) {
            const dy = (ORIGIN_Y + (j + 0.5) * CELL_H - cy) / ry

            for (let i = i0; i <= i1; i += 1) {
                const dx = (ORIGIN_X + (i + 0.5) * CELL_W - cx) / rx
                const d = Math.hypot(dx, dy)

                if (d < 1) {
                    ignite(i, j, (1 - d) ** FALLOFF)
                }
            }
        }
    }

    const paint = () => {
        const w = rect.width
        const h = rect.height

        if (next) {
            const from = prev || next
            const dx = next.x - from.x
            const dy = next.y - from.y
            const span = Math.hypot(dx, dy)
            const steps = span > JUMP ? 0 : Math.ceil(span / STEP)

            for (let i = 1; i <= steps; i += 1) {
                splash(from.x + (dx * i) / steps, from.y + (dy * i) / steps)
            }

            if (0 === steps) {
                splash(next.x, next.y)
            }

            prev = next
            next = null
        }

        ctx.globalCompositeOperation = 'source-over'
        ctx.clearRect(0, 0, w, h)
        ctx.fillStyle = '#000'

        const cw = CELL_W * scaleX
        const ch = CELL_H * scaleY

        cells.forEach((cell, key) => {
            if (cell.wait > 0) {
                cell.wait -= 1

                return
            }

            cell.value += (cell.target - cell.value) * cell.rise
            cell.target *= cell.decay
            cell.phase += cell.speed

            if (cell.value < 0.004 && cell.target < 0.004) {
                cells.delete(key)

                return
            }

            // Лёгкое мерцание, чтобы цифры жили, а не просто гасли.
            const flicker = 0.88 + 0.12 * Math.sin(cell.phase)

            ctx.globalAlpha = Math.min(cell.value * flicker, 1)
            ctx.fillRect(
                art.x + (ORIGIN_X + cell.i * CELL_W) * scaleX,
                art.y + (ORIGIN_Y + cell.j * CELL_H) * scaleY,
                cw,
                ch
            )
        })

        ctx.globalAlpha = 1

        // Узор остаётся только там, где зажглись ячейки.
        ctx.globalCompositeOperation = 'source-in'
        ctx.drawImage(image, art.x, art.y, art.w, art.h)
        ctx.globalCompositeOperation = 'source-over'
    }

    const frame = () => {
        if (!running) {
            return
        }

        idle += 1

        if (idle > IDLE_FRAMES && 0 === cells.size) {
            running = false
            prev = null
            ctx.setTransform(1, 0, 0, 1, 0, 0)
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0)

            return
        }

        paint()
        window.requestAnimationFrame(frame)
    }

    const onMove = (event) => {
        if (!active || !rect) {
            return
        }

        const x = event.clientX - rect.left
        const y = event.clientY - rect.top

        // Курсор далеко от полосы — след не подпитываем, но даём ему догореть.
        if (y < -RADIUS || y > rect.height + RADIUS) {
            prev = null

            return
        }

        next = { x, y }
        idle = 0

        if (!running) {
            running = true
            window.requestAnimationFrame(frame)
        }
    }

    const onGeometry = () => {
        if (active) {
            measure()
        }
    }

    const start = () => {
        if (active) {
            return
        }

        active = true
        measure()
        backdrop.classList.add('is-interactive')
        window.addEventListener('pointermove', onMove, { passive: true })
        window.addEventListener('scroll', onGeometry, { passive: true })
        window.addEventListener('resize', onGeometry)
    }

    const stop = () => {
        if (!active) {
            return
        }

        active = false
        running = false
        prev = null
        next = null
        cells.clear()
        backdrop.classList.remove('is-interactive')
        window.removeEventListener('pointermove', onMove)
        window.removeEventListener('scroll', onGeometry)
        window.removeEventListener('resize', onGeometry)
        ctx.setTransform(1, 0, 0, 1, 0, 0)
        ctx.clearRect(0, 0, canvas.width, canvas.height)
    }

    const sync = () => {
        if (allowed()) {
            start()
        } else {
            stop()
        }
    }

    const ready = () => {
        if (image.complete && image.naturalWidth) {
            sync()
        } else {
            image.addEventListener('load', sync, { once: true })
        }
    }

    ;[pointer, desktop, calm].forEach((query) => {
        if (query.addEventListener) {
            query.addEventListener('change', ready)
        }
    })

    ready()
}

export default initHeroTrail
