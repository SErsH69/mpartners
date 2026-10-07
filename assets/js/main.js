import initMenu from './modules/menu'
import initQuiz from './modules/quiz'
import initDragScroll from './modules/drag-scroll'
import initContactForm from './modules/contact-form'
import initDropdown from './modules/dropdown'
import initReveal from './modules/reveal'
import initHeroTrail from './modules/hero-trail'

const boot = () => {
    initMenu()
    initQuiz()
    initDragScroll()
    initContactForm()
    initDropdown()
    initReveal()
    initHeroTrail()
}

if ('loading' === document.readyState) {
    document.addEventListener('DOMContentLoaded', boot)
} else {
    boot()
}
