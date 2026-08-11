import 'flowbite'
import Loader from "./loader"
import MenuEvents from "./menu-events"
import SwiperInit from "./swiper-init"

const body = document.body;
const widthPage = window.innerWidth;
var mobile = widthPage <= 900;

const App = {
	init: () => {
		App.isLoaded();
	},

	isLoaded: () => {
		setTimeout(() => body.className += ' is-loaded', 1000);
	},

	Mobile: () => {
		if (mobile) {
		}
	},

};

window.onload = () => {
	App.init();
	
	/**
	 * Menu and Window events
	 */
	MenuEvents.setBurgerMenuWhenMobile();
	MenuEvents.smoothLinkEvent();

	SwiperInit.init("hero-slider", 1); // Hero Banner Home

	// Loader Stop, open site
	Loader.stopLoading();
};