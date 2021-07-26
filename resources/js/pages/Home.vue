<template>
    <div>
        <img class="jumbo" src="../img/jumbo.jpeg" alt="" />
        <div class="container">
            <h1>Homepage</h1>

            <!-- CHECKBOX CUISINES -->
            <div class="cuisines mb-5">
                <ul v-for="cuisine in cuisines" :key="cuisine.id">
                    <li>
                        <label class="btn btn-success" :for="cuisine.type">{{ cuisine.type }}</label>
                        <input
                            type="checkbox"
                            :id="cuisine.type"
                            :value="cuisine.type"
                            v-model="temp"
                            @change="getRestaurants($event)"
                        />
                    </li>
                </ul>
            </div>

            <!-- RESTAURANT LIST -->

                <div class="rest" v-for="restaurant in restaurants.results"
                    :key="`res-${restaurant.id}`">
                    <div class="card-rest card bg-light" >
                            <h2>{{ restaurant.name }}</h2>

                            <img
                                class="img-fluid img my-2"
                                :src="restaurant.image"
                                alt=""
                            />
                            <router-link class="btn btn-warning"
                                :to="{
                                    name: 'restaurant-detail',
                                    params: { name: restaurant.id },
                                    hash: '#inizio'
                                }"
                                >Restaurant Detail</router-link
                            >

                    </div>
                </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Cuisines from "../components/Cuisines.vue";
export default {
    name: "Home",
    components: {
        Cuisines
    },
    data() {
        return {
            apiURL: "http://127.0.0.1:8000/api/restaurants",
            restaurants: [],
            listRestaurant: [],
            searchText: "",
            results: [],
            cuisines: [],
            temp: [],
            query: ""
        };
    },
    created() {
        this.getRestaurants();
        this.getCuisines();
        window.scrollTo(0,0);
    },
    methods: {
        getRestaurants: function(e) {
            axios
                .get(this.apiURL + "/" + (this.temp.join("-")) )
                .then(res => {
                    this.restaurants = res.data;

                    /* this.restaurants.forEach(restaurant => {
                        restaurant.types.forEach(type => {
                            if(!this.types.includes(type.name)){
                                this.types.push(type.name);
                            }
                        });
                    }); */
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getCuisines() {
            axios
                .get("http://127.0.0.1:8000/api/cuisines/")
                .then(res => {
                    this.cuisines = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
};
</script>

<style scoped lang="scss">
.cuisines {
    border: 1px solid #ccc;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

h1 {
    margin: 20px;
    display: flex;
    justify-content: center;
}

h2 {
    margin: 10px 0;
}

.jumbo {
    width: 100%;
}

.rest {
    display: inline;
}
    .card-rest {
        display: inline-grid;
        width: calc(100% / 4 - 20px);
        margin: 10px;
        padding: 1rem;

        img {
            border-radius: 5px;
        }
    }

</style>
