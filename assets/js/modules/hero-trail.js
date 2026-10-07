/**
 * Узор первого экрана проявляется под курсором (по мотивам verteal.com).
 *
 * Базовая картинка в фоне еле видна (opacity 0.22), а поверх неё лежит canvas,
 * на котором тот же узор показывается только там, где недавно был курсор.
 * Маска следа рисуется на половинном разрешении — так пятно мягче и дешевле.
 */

const RADIUS = 160 // радиус «фонарика», px
const FADE = 0.05 // доля следа, которая гаснет за кадр
const STEP = 16 // шаг интерполяции между позициями курсора, px
const IDLE_FRAMES = 110 // кадров без движения мыши до остановки цикла
const MASK_SCALE = 0.5

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
    const mask = document.createElement('canvas')
    const maskCtx = mask.getContext('2d')

    if (!ctx || !maskCtx) {
        return
    }

    let rect = null
    let art = null
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

        dpr = Math.min(window.devicePixelRatio || 1, 2)

        const width = Math.round(rect.width * dpr)
        const height = Math.round(rect.height * dpr)

        if (canvas.width !== width || canvas.height !== height) {
            canvas.width = width
            canvas.height = height
            mask.width = Math.max(1, Math.round(rect.width * MASK_SCALE))
            mask.height = Math.max(1, Math.round(rect.height * MASK_SCALE))
            prev = null
        }

        ctx.setTransform(dpr, 0, 0, dpr, 0, 0)
        maskCtx.setTransform(MASK_SCALE, 0, 0, MASK_SCALE, 0, 0)
    }

    const blob = (x, y) => {
        const glow = maskCtx.createRadialGradient(x, y, 0, x, y, RADIUS)

        glow.addColorStop(0, 'rgba(255, 255, 255, 0.5)')
        glow.addColorStop(0.45, 'rgba(255, 255, 255, 0.16)')
        glow.addColorStop(1, 'rgba(255, 255, 255, 0)')

        maskCtx.fillStyle = glow
        maskCtx.beginPath()
        maskCtx.arc(x, y, RADIUS, 0, Math.PI * 2)
        maskCtx.fill()
    }

    const paint = () => {
        const w = rect.width
        const h = rect.height

        // Затухание следа.
        maskCtx.globalCompositeOperation = 'destination-out'
        maskCtx.fillStyle = `rgba(0, 0, 0, ${FADE})`
        maskCtx.fillRect(0, 0, w, h)

        // Новый отрезок следа — с промежуточными точками, чтобы быстрый
        // взмах мышью не оставлял разрывов.
        maskCtx.globalCompositeOperation = 'lighter'

        if (next) {
            const from = prev || next
            const dx = next.x - from.x
            const dy = next.y - from.y
            const span = Math.hypot(dx, dy)
            // Длинный прыжок (курсор вернулся в окно) рисуем одной точкой,
            // иначе след получится пунктиром.
            const steps = span > 420 ? 0 : Math.ceil(span / STEP)

            for (let i = 1; i <= steps; i += 1) {
                blob(from.x + (dx * i) / steps, from.y + (dy * i) / steps)
            }

            if (0 === steps) {
                blob(next.x, next.y)
            }

            prev = next
            next = null
        }

        // Узор остаётся только там, где маска непрозрачна.
        ctx.globalCompositeOperation = 'source-over'
        ctx.clearRect(0, 0, w, h)
        ctx.drawImage(mask, 0, 0, w, h)
        ctx.globalCompositeOperation = 'source-in'
        ctx.drawImage(image, art.x, art.y, art.w, art.h)
        ctx.globalCompositeOperation = 'source-over'
    }

    const frame = () => {
        if (!running) {
            return
        }

        idle += 1

        if (idle > IDLE_FRAMES) {
            running = false
            prev = null
            ctx.setTransform(1, 0, 0, 1, 0, 0)
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0)
            maskCtx.setTransform(1, 0, 0, 1, 0, 0)
            maskCtx.clearRect(0, 0, mask.width, mask.height)
            maskCtx.setTransform(MASK_SCALE, 0, 0, MASK_SCALE, 0, 0)

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

        // Курсор за пределами полосы по вертикали — след не рисуем, но даём
        // ему догореть.
        if (y < -RADIUS || y > rect.height + RADIUS) {
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
        if (!active) {
            return
        }

        measure()
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
