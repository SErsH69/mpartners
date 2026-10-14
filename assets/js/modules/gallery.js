/**
 * Просмотр дипломов: клик по скану открывает lightGallery с переключением
 * между всеми сканами ленты. Обычные ссылки в карточках не трогаем —
 * поэтому выбираем только отмеченные `data-lg-item`.
 */

import lightGallery from 'lightgallery'
import lgZoom from 'lightgallery/plugins/zoom'
import lgThumbnail from 'lightgallery/plugins/thumbnail'

import 'lightgallery/css/lightgallery.css'
import 'lightgallery/css/lg-zoom.css'
import 'lightgallery/css/lg-thumbnail.css'

const initGallery = () => {
    document.querySelectorAll('[data-gallery]').forEach((track) => {
        if (!track.querySelector('a[data-lg-item]')) {
            return
        }

        lightGallery(track, {
            plugins: [lgZoom, lgThumbnail],
            selector: 'a[data-lg-item]',
            speed: 400,
            download: false,
            counter: true,
            thumbnail: true,
            // Ключ-заглушка из документации: гасит предупреждение в консоли.
            licenseKey: '0000-0000-000-0000',
            mobileSettings: {
                controls: true,
                showCloseIcon: true,
            },
        })
    })
}

export default initGallery
