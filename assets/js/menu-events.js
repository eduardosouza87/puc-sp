const mainHeader = document.querySelector(".header-main");
const burger = document.querySelector(".menu__burger");
const mobile = !(window.innerWidth > 1024);

class MenuEvents {
  static setBurgerMenuWhenMobile() {
    if (mobile) {
      burger.addEventListener("click", () => {
        burger.classList.toggle("is-active");
        document.body.classList.toggle("is-menu-open");
        mainHeader.classList.toggle("h-screen");
      });
    }
  }

  static scrollPageEvent() {
    if (mainHeader) {
      window.addEventListener("scroll", () => {
        const currentScroll = window.scrollY;
        if (currentScroll > 0) {
          mainHeader.classList.add("fixed");
        } else {
          mainHeader.classList.remove("fixed");
        }
      });
    }
  }

  static smoothLinkEvent() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault();

        if (mobile) {
          burger.dispatchEvent(new Event("click"));
        }

        document.querySelector(this.getAttribute("href")).scrollIntoView({
          behavior: "smooth",
        });
      });
    });
  }
}

export default MenuEvents;
