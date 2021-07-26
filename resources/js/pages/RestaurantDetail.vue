<template>
    <div class="main">

        <div v-show="loader" class="hungry-4">
        </div>

        <div v-show="!loader" class="container">

            <h1 >Details: {{ restaurant.results.name }}</h1>

            <div class="mar">
                <h5 class="mb">Type of cuisines: </h5>
                <Cuisines :cuisines="restaurant.results.type" />
            </div>

            <div class="row mt-3">

                <div class="col-md-6">
                <img class="img-fluid rounded" :src="restaurant.results.image" :alt="restaurant.name" />
                </div>

                <div class="col-md-6">
                <p class="mar">{{ restaurant.results.description }}</p>
            </div>

            <div>
                <h3 class="my-5 text-center">Our Menù</h3>

                <!-- ADD PLATE -->
                <div class="row">

                    <div class="col-md-3 offset-md-1 col-sm-12 dish d-flex justify-content-between card bg-light my-2"  v-for="(plate, index) in plates" :key="`plate-${index}`">

                        <div v-if="plate.image" class="img my-2">
                            <img class="img-fluid"  :src="plate.image" :alt="plate.name"/>
                        </div>

                       <div v-else class="not-av">
                           <img src="https://redi.it/wp-content/uploads/2015/08/not-available.png" alt="not-avaiable">
                       </div>

                            <div class="card-header">
                                <p><strong>Name: </strong>{{plate.name}}</p>
                            </div>

                            <div class="d-flex flex-column justify-content-center card-body">
                                <p><strong>Description: </strong>{{plate.description}}</p>

                                <div v-if="plate.visibility" class="d-flex align-items-baseline mt-3">
                                    <div>€ {{plate.price.toFixed(2)}}</div>
                                    <p class="btn btn-primary mx-3" @click="addCart($event,plate)" >Add to Cart</p>
                                </div>

                                <div v-else>
                                    <button class="btn btn-danger" disabled>Not Avaiable</button>
                                </div>

                            </div>
                    </div>

                </div>

                </div>

            </div>


            <!-- Cart -->
            <div class="cart mt-5 card bg-light col-5 offset-4 text-center">

                <div class="card-header">
                    <h2>Your Cart <i class="fas fa-shopping-basket"></i></h2>
                </div>

                <div class="card-body">

                    <!-- Products -->
                    <div v-show="Object.keys(cart).length>0" >

                        <div v-for="(item, index) in cart" :key="index">
                            {{index}}
                            <input class="inputNum my-1 col-1" type="number" min="1" v-model="item.quantity" @change="updateQuantity($event, item.name, item.unit)">
                            <span class="name">{{item.name}}</span>

                            <!--<span>€ {{item.unitPrice}}</span>-->
                            <span class="remove" @click="removeAll(item.name, item.price)"><i class=" click fas fa-trash-alt"></i></span>

                        </div>

                        <!-- Tot -->
                        <h3 class="mt-3">Total: €{{tot.toFixed(2)}}</h3>

                        <!-- Delete Button -->
                        <button class="btn btn-danger my-2" @click="deleteCart()">Delete Cart</button>

                        <!-- CheckOut Button -->
                        <div class="mar">
                            <router-link class="btn btn-warning" :to="{name: 'checkout'}">Go to Checkout</router-link>
                        </div>

                    </div>

                    <div v-show="Object.keys(cart).length===0">Your cart is empty
                    </div>

                </div>

            </div>
</div>
            <div class="mar contacts">
                <h3>Contacts</h3>
                <ul>
                    <li><strong>Address: </strong>{{ restaurant.results.address }}</li>
                    <li><strong>City: </strong>{{ restaurant.results.city }}</li>
                    <li><strong>Cap: </strong>{{ restaurant.results.cap }}</li>
                    <li><strong>Phone number: </strong>{{ restaurant.results.phone_number }}</li>
                </ul>
            </div>


    </div>


</template>

<script>
import Plates from "../components/Plates.vue";
import Cuisines from "../components/Cuisines.vue";
import axios from "axios";
export default {
    name: "RestaurantDetail",
    components: {
        Plates,
        Cuisines,
    },
    data() {
        return {
            restaurant: '',
            plates: [],
            plate: {},
            cart: {},
            tot: 0,
            loader: true

        };
    },
    created() {

        this.getRestaurantDetail();

        this.getPlate();

        this.popCart();


    },
    updated() {

        this.loader= false;

        window.scrollTo(0,0);

    },


    methods: {

        getPlate() {
            axios
                .get(
                    `http://127.0.0.1:8000/api/plates/${parseInt(this.$route.params.name)}`
                )
                .then(res => {
                    this.plates = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getRestaurantDetail() {
            axios
                .get(
                    `http://localhost:8000/api/restaurants/id/${parseInt(this.$route.params.name)}`
                )
                .then(res => {

                    this.restaurant = res.data;
                    console.log(this.restaurant.results.name);
                    this.loader = false;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /**
         * Populate Cart with localStorage
         */
        popCart(){
            if(window.localStorage.getItem('cart')){
                this.cart = JSON.parse(window.localStorage.getItem('cart'));
                this.setTotal();
                console.log('popcart');
            }
        },

        addCart(e,plate) {
            console.log("dentro addcart",Object.keys(this.cart).length);
            if(this.checkId(plate)) {
                if(this.cart[plate.name]){
                    this.cart[plate.name].quantity++;
                    this.cart[plate.name].price += plate.price;
                    console.log(this.cart);
                } else {
                    this.cart[plate.name] = {
                        restaurant_id: plate.restaurant_id,
                        name: plate.name,
                        quantity: 1,
                        price: plate.price,
                        unit: plate.price
                    };
                }
                this.tot += plate.price;

                this.store();
            }
        },

        /**
         * Check for
         */
        checkId(plate){
            if(Object.keys(this.cart).length != 0){
                if(this.cart[Object.keys(this.cart)[0]].restaurant_id == plate.restaurant_id) {
                    return true;
                }else {
                    const resp = confirm('Puoi ordinare da un solo ristorante. Vuoi cancellare il tuo ordine precedente?');
                    if(resp) {
                        this.cart = {};
                        this.tot = 0;
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            else{
                return true;
            }
        },

        /**
         * Remove one record in Cart
         */
        removeAll(item, price){
            console.log(item);
            console.log(price);
            delete this.cart[item];
            this.tot -= price;
            this.store();
        },

        /**
         * Set Quantity from Input in Cart
         */
        updateQuantity(e,name, unit){
            const value = parseFloat(e.target.value);
            if(value>0){
                console.log(value);
                this.cart[name].quantity = value;
                this.cart[name].price = (value * unit);
                this.tot = 0;
                this.setTotal();
                this.store();
            } else {
                this.cart[name].quantity = 1;
                this.cart[name].price = (value * unit);
                this.tot = this.cart[name].price;
                this.setTotal();
                this.store();
            }
        },

        /**
         * Set Total Amount
         */
        setTotal(){
            for(let item in this.cart){
                this.tot+=this.cart[item].price;
            };
        },

        /**
         * Save Cart in localStorage
         */
        store(){
            window.localStorage.setItem('cart', JSON.stringify(this.cart));
        },

        /**
         * Eliminate Entire Cart
         */
        deleteCart(){
            const resp = confirm('Vuoi cancellare il tuo ordine?');
            if(resp){
                this.cart = {};
                this.tot = 0;
                window.localStorage.clear();
            }
        },
    }
};
</script>

<style scoped lang="scss">
.img {
    img {
    width: 100%;
    height: 200px;
    }
}

.not-av {
    img {
    width: 100%;
    height: 200px;
    }
}
.contacts {
    h3 {
        padding-left: 2rem;
    }
    min-width: 1300px;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background: #007e8a;
    color: #fff;
    margin: 30px 0;
    padding: 20px 0;
}

.hungry-4 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50% , -50%);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 8px solid #d1914b;
    --c:radial-gradient(farthest-side, #d64123 94%,#0000);
    --b:radial-gradient(farthest-side, #000 94%,#0000);
    background:
    var(--c) 11px 15px,
    var(--b) 6px 15px,
    var(--c) 35px 23px,
    var(--b) 29px 15px,
    var(--c) 11px 46px,
    var(--b) 11px 34px,
    var(--c) 36px 0px,
    var(--b) 50px 31px,
    var(--c) 47px 43px,
    var(--b) 31px 48px,
    #f6d353;
    background-size: 15px 15px,6px 6px;
    background-repeat: no-repeat;
    animation: hu4 2s infinite;
}
@keyframes hu4 {
  0%     {-webkit-mask:conic-gradient(#0000 0     ,#000 0)}
  16.67% {-webkit-mask:conic-gradient(#0000 60deg ,#000 0)}
  33.33% {-webkit-mask:conic-gradient(#0000 120deg,#000 0)}
  50%    {-webkit-mask:conic-gradient(#0000 180deg,#000 0)}
  66.67% {-webkit-mask:conic-gradient(#0000 240deg,#000 0)}
  83.33% {-webkit-mask:conic-gradient(#0000 300deg,#000 0)}
  100%   {-webkit-mask:conic-gradient(#0000 360deg,#000 0)}
}


i {
        color: #007e8a;
    }

</style>
