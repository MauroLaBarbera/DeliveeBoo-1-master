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
    scrollBehavior (to, from, savedPosition) {
        console.log('to ',to);
        console.log('from ',from);
        console.log('savedPosition ',savedPosition);
        if (to.hash) {
          return {
            selector: to.hash,
            behavior: 'smooth',
          }
        }
      }
});


export default router;




