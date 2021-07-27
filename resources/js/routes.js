//DIPENDENZE
import Vue from "vue";
import VueRouter from "vue-router";

import Home from "./pages/Home.vue";
import Payment from "./pages/Payment.vue";
import RestaurantDetail from "./pages/RestaurantDetail.vue";
import Checkout from "./pages/Checkout.vue";
import NotFound from "./pages/NotFound.vue";

//ATTIVAZIONE
Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/:page",
            name: "homeP",
            component: Home
        },
        {
            path: "/payment",
            name: "payment",
            component: Payment
        },
        {
            path: "/restaurant/:name",
            component: RestaurantDetail,
            name: "restaurant-detail",
            props: { loader: true }

        },
        {
            path: "/checkout",
            name: "checkout",
            component: Checkout
        },
        {
            path: "*",
            component: NotFound
        },
    ],
     scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
          // savedPosition is only available for popstate navigations.
          return savedPosition
        } else {
          const position = {}

          // scroll to anchor by returning the selector
          if (to.hash) {
            position.selector = to.hash

            // specify offset of the element
            if ((to.hash === '#inizio')||(to.hash === '#start')) {
              position.offset = { y: 0}
            }

            // bypass #1number check
            if (/^#\d/.test(to.hash) || document.querySelector(to.hash)) {
              return position
            }

            // if the returned position is falsy or an empty object,
            // will retain current scroll position.
            return false
          }

          return new Promise(resolve => {
            // check if any matched route config has meta that requires scrolling to top
            if (to.matched.some(m => m.meta.scrollToTop)) {
              // coords will be used if no selector is provided,
              // or if the selector didn't match any element.
              position.x = 0
              position.y = 0
            }

            // wait for the out transition to complete (if necessary)
            this.app.$root.$once('triggerScroll', () => {
              // if the resolved position is falsy or an empty object,
              // will retain current scroll position.
              resolve(position)
            })
          })
        }
      }
});




export default router;




