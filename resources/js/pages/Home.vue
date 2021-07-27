<template>
    <div>
        <img class="jumbo " src="../img/jumbo.jpeg" alt="" />
        <div class="container">
            <h1>Homepage</h1>

            <!-- CHECKBOX CUISINES -->
            <div class="cuisines mb-5">
                <ul v-for="cuisine in cuisines" :key="cuisine.id">
                    <li>
                        <label class="btn btn-success" :for="cuisine.type">{{
                            cuisine.type
                        }}</label>
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

            <div
                class="rest"
                v-for="restaurant in restaurants.results.data"
                :key="`res-${restaurant.id}`"
            >
                <div class="card-rest card text-center bg-light">
                    <h2>{{ restaurant.name }}</h2>

                    <img
                        v-if="restaurant.image"
                        class="img my-2"
                        :src="restaurant.image"
                        :alt="restaurant.name"
                    />

                    <img
                        v-else
                        class="img my-2"
                        src="https://redi.it/wp-content/uploads/2015/08/not-available.png"
                        :alt="restaurant.name"
                    />

                    <router-link
                        class="btn btn-warning"
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

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <router-link
                        class="page-link"
                        @click.native="getRestaurants($event)"
                        :to="{
                            name: 'homeP',
                            params: {
                                page: page === first ? first : --page
                            }
                        }"
                    >
                        Previous</router-link
                    >
                </li>

                

                <li class="page-item" v-for="i in parseInt(last) " :key="i">
                    <router-link
                        class="page-link"
                        @click.native="getRestaurants($event)"
                        :to="{
                            name: 'homeP',
                            params: {
                                page: i
                            }
                        }"
                    >
                        {{ i }}</router-link
                    >
                </li>

                <li class="page-item">
                    <router-link
                        class="page-link"
                        @click.native="getRestaurants($event)"
                        :to="{
                            name: 'homeP',
                            params: {
                                page: page == last ? last : ++page
                            }
                        }"
                    >
                        Next</router-link
                    >
                </li>
            </ul>
        </nav>
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
            query: "",
            page: 1,
            first: 1,
            last: 1
        };
    },
    mounted() {
        this.getRestaurants();
        this.getCuisines();

    },
    methods: {
        getRestaurants: function(e) {
            let pg;
            if (parseInt(this.$route.params.page)){
                pg = "?page="+parseInt(this.$route.params.page);
            }
            else{
                pg='';
            }



            axios
                .get(this.apiURL + "/" + this.temp.join("-")+pg)
                .then(res => {
                    console.log(res.data);
                    this.restaurants = res.data;

                    this.page = res.data.results.current_page;
                    this.last = res.data.count/4;



                    console.log(parseInt(this.restaurants.results.total/this.restaurants.results.per_page));
                    //console.log(this.apiURL + "/" + this.temp.join("-")+"?page="+parseInt(this.$route));
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
.card {
    img {
        width: 100%;
        height: 176px;
    }
}

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
    min-height: 360px;
    margin: 10px;
    padding: 1rem;

    img {
        border-radius: 5px;
    }
}

.page-link {
    color: #00a396;
}
</style>
