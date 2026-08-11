import Swiper from 'swiper'
import { Autoplay, Navigation, Pagination } from 'swiper/modules'

class SwiperInit {
  static init (idSlider, slidesPerView = 1, options = {}) {
    const hasSlider = _checkSliderExists(idSlider)

    if (hasSlider) {
      const swiper = new Swiper(`#${idSlider}`, {
        modules: [Autoplay, Navigation, Pagination],
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        centeredSlidesBounds: true,
        navigation: {
          nextEl: `#${idSlider} .swiper-button-next`,
          prevEl: `#${idSlider} .swiper-button-prev`,
        },
        pagination: {
          el: `#${idSlider} .swiper-pagination`,
          clickable: true
        },
        breakpoints: {
          320: {
            slidesPerView: 1
          },
          1024: {
            slidesPerView: slidesPerView,
          }
        },
        ...options
      })

      swiper.init()
    }
  }
}

const _checkSliderExists = (idSlider) => (document.getElementById(idSlider) !== null)

export default SwiperInit