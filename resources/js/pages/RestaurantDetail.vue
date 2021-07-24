<template>
    <div>
        <div class="container">
            <h1 >Details: {{ restaurant.results[0].name }}</h1>
            <div class="mar">
                <h2 class="mb">Type of cuisines:</h2>
                <Cuisines :cuisines="restaurant.results[0].cuisines" />
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                <img class="img-fluid rounded" :src="restaurant.results[0].image" :alt="restaurant.name" />
                </div>
                <div class="col-md-6">
                <p class="mar">{{ restaurant.results[0].description }}</p>
                </div>
                <div>
                    <h3 class="mar">Our Menù</h3>
                    <!-- ADD PLATE -->
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-sm-12 dish d-flex justify-content-between"  v-for="(plate, index) in plates" :key="`plate-${index}`">
                            <div class="d-flex flex-column justify-content-center">
                                <div v-if="plate.visibility" class="d-flex">
                                    <div>€ {{plate.price.toFixed(2)}}</div>
                                    <i class="fas fa-plus-circle add" @click="addCart(plate)"></i>
                                </div>
                                <div v-else>Non Disponibile</div>
                            </div>
                            <div class="img"><img class="img-fluid" v-if="plate.image" :src="plate.image" :alt="plate.name"/></div>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Cart -->
        <div class="cart mt-5 card bg-light col-5">
            <div class="card-header">
                <h2>Your Cart</h2>
            </div>
            <div class="card-body">
                <!-- Products -->
                <div v-if="Object.keys(cart).length" >
                    <div v-for="(item, index) in cart" :key="index">
                        <input class="inputNum" type="number" min="1" v-model="item.quantità" @change="updateQuantity($event, item.name, item.unit)">
                        <span class="name">{{item.name}}</span>
                        <span>€ {{item.prezzo.toFixed(2)}}</span>
                        <span class="remove" @click="removeAll(item.name, item.prezzo)"><i class="fas fa-times"></i></span>
                    </div>
                </div>
                <div v-else>Your cart is empty</div>
                <!-- Tot -->
                <h3 class="mt-3">Total: €{{tot.toFixed(2)}}</h3>
            <!-- Delete Button -->
            <button class="btn btn-danger" @click="deleteCart()">Delete Cart</button>
            <!-- CheckOut Button -->
            <div class="mar">
            <router-link class="btn btn-warning" :to="{name: 'checkout'}">Go to Checkout</router-link>
            </div>
            </div>
        </div>
        </div>
            <div class="mar contacts">
                <h3>Contacts</h3>
                <ul>
                    <li><strong>Address: </strong>{{ restaurant.results[0].address }}</li>
                    <li><strong>City: </strong>{{ restaurant.results[0].city }}</li>
                    <li><strong>Cap: </strong>{{ restaurant.results[0].cap }}</li>
                    <li>
                        <strong>Phone number: </strong
                        >{{ restaurant.results[0].phone_number }}
                    </li>
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
            visibility: false,
            cart: {},
            tot: 0,
        };
    },
    created() {
        this.getRestaurantDetail();

        this.getPlate();

        this.popCart();
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
            }
        },

        addCart(plate) {
            if(this.checkId(plate)) {
                if(this.cart[plate.name]){
                    this.cart[plate.name].quantità++;
                    this.cart[plate.name].price += plate.price;
                } else {
                    this.cart[plate.name] = {
                        restaurant_id: plate.restaurant_id,
                        name: plate.name,
                        quantità: 1,
                        prezzo: plate.price,
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
            }else{
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
                this.cart[name].quantità = value;
                this.cart[name].prezzo = (value * unit);
                this.tot = 0;
                this.setTotal();
                this.store();
            } else {
                this.cart[name].quantità = 1;
                this.cart[name].prezzo = (value * unit);
                this.tot = this.cart[name].prezzo;
                this.setTotal();
                this.store();
            }
        },

        /**
         * Set Total Amount
         */
        setTotal(){
            for(let item in this.cart){
                this.tot+=this.cart[item].prezzo;
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
.contacts {
    h3 {
        padding-left: 2rem;
    }

    background: #007e8a;
    color: #fff;
    margin: 30px 0; 
    padding: 20px 0; 
}

.mar {
    margin: 20px 0;
    img {
        border-radius: 5px;
    }
}
.mb {
    margin-bottom: 10px;
}
</style>
