<template>
    <div class="container">
        <div class="col-6 offset-3 my-3">
            <div class="card bg-light ">
                <div class="card-header text-center">
                    <h2>Your Cart <i class="fas fa-shopping-basket"></i></h2>
                </div>
                <div class="card-body">

                    <div class="cart">

                        <div v-if="Object.keys(cart).length" >
                            <div class="text-left" v-for="(item, index) in cart" :key="index">

                                <input  class="inputNum col-md-1 my-1" type="number" min="1" v-model="item.quantity" @change="updateQuantity($event, item.name, item.unit)">

                                <span class="name">{{item.name}}</span>
                                <span>€ {{item.price.toFixed(2)}}</span>
                                <span class="remove" @click="removeAll(item.name, item.price)"><i class=" click fas fa-trash-alt"></i></span>
                            </div>


                            <h3 class="mt-5">Tot: €{{tot.toFixed(2)}}</h3>

                            <div class="text-center">
                                <button class="btn btn-danger" @click="deleteCart()">Delete Cart</button>
                            </div>

                        </div>

                        <div v-else>Your cart is empty



                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="container">
        <div v-if="Object.keys(cart).length" class="col-6 offset-3">
            <div class="card bg-light">
                <div class="card-header">Payment Information</div>
                <div class="card-body">
                    <div class="alert alert-success" v-if="nonce">
                        Thank you for your order!
                    </div>
                    <form>
                        <div class="form-group">
                            <label>Credit Card Number</label>
                            <div
                                id="creditCardNumber"
                                class="form-control"
                            ></div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Expire Date</label>
                                    <div
                                        id="expireDate"
                                        class="form-control"
                                    ></div>
                                </div>
                                <div class="col-6">
                                    <label>CVV</label>
                                    <div id="cvv" class="form-control"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center">
                <button
                    class="btn btn-primary btn-block mt-3"
                    @click="payWithCreditCard"
                >
                    Pay with Credit Card
                </button>
            </div>
            <div class="alert alert-danger mt-3" v-if="error">
                {{ error }}
            </div>
        </div>
    </div>

  </div>
</template>

<script>
import braintree from "braintree-web";

export default {
    name: 'CheckOut',
    data() {
        return {
          tot: 0,
          cart: [],
            hostedFieldInstance: false,
            nonce: "",
            error: ""
        }
    },
    created(){
        this.getBill();
        window.scrollTo(0,0);
    },
     mounted() {
        braintree.client
            .create({
                authorization: "sandbox_4xzwt3mg_6mhmdsyyhy26bc24"
            })
            .then(clientInstance => {
                let options = {
                    client: clientInstance,
                    styles: {
                        input: {
                            "font-size": "14px",
                            "font-family": "Open Sans"
                        }
                    },
                    fields: {
                        number: {
                            selector: "#creditCardNumber",
                            placeholder: "Enter Credit Card"
                        },
                        cvv: {
                            selector: "#cvv",
                            placeholder: "Enter CVV"
                        },
                        expirationDate: {
                            selector: "#expireDate",
                            placeholder: "00 / 0000"
                        }
                    }
                };
                return braintree.hostedFields.create(options);
            })
            .then(hostedFieldInstance => {
                // Use hostedFieldInstance to send data to Braintree
                this.hostedFieldInstance = hostedFieldInstance;
            })
            .catch(err => {
                console.error(err);
                this.error = err.message;
            });
    },
    methods: {
        getBill(){
            if(window.localStorage.getItem('cart')){
                this.cart = JSON.parse(window.localStorage.getItem('cart'));

                for(let item in this.cart){
                    this.tot+=this.cart[item].price;
                };
            }
        },
        removeAll(item, price){
            console.log(item);
            console.log(price);
            delete this.cart[item];
            this.tot -= price;
            this.store();
        },
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
                this.cart[name].price = unit;
                this.tot = 0;
                this.setTotal();
                this.store();
            }
        },
        setTotal(){
            for(let item in this.cart){
                this.tot+=this.cart[item].price;
            };
        },
        store(){
            window.localStorage.setItem('cart', JSON.stringify(this.cart));
        },
        deleteCart(){
            const resp = confirm('Vuoi cancellare il tuo ordine?');
            if(resp){
                this.cart = {};
                this.tot = 0;
                window.localStorage.clear();
            }
        },
         payWithCreditCard() {
            if (this.hostedFieldInstance) {
                this.error = "";
                this.nonce = "";
                this.hostedFieldInstance
                    .tokenize()
                    .then(payload => {
                        console.log(payload);
                        this.nonce = payload.nonce;
                        window.localStorage.clear();
                        location.reload()
                    })
                    .catch(err => {
                        console.error(err);
                        this.error = err.message;
                    });
            }
        }
    }
}
</script>

<style>
    .form-control{
            height: 30px;
        }
    i {
    color: #007e8a;
    }
</style>
