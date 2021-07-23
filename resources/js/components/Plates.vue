<template>

    <div class="menu" >
        <div
            v-for="pla in plates"
            :key="`pla-${pla.id}`"
        >

        <div
            class="plate-card"
            v-for="plate in pla"
            :key="`plate-${plate.id}`"
        >
            <div v-if="plate.visibility">
                <img :src="plate.image" :alt="plate.name" />
                
                <p class="mt-2"> <strong >Description: </strong> {{ plate.description }}</p>

                <strong>Price: </strong>{{ plate.price }}€
                <div class="mt-2">
                    <strong>Avaiable: </strong>
                    <span v-if="plate.visibility === 0"> No </span>
                    <span v-else-if="plate.visibility === 1"> Yes </span>
<<<<<<< HEAD
                </div>
                <div class="text-center mt-3" v-if="plate.visibility === 1">
                    <button class="btn btn-primary " @click="addPlate(plate)" >Add to Cart</button>
                </div>
                <div v-else disabled>
                    <button>Not avaiable</button>
                </div>
            </div>
=======
                </li>
                <li v-if=(plate.visibility)>
                    <button @click="addPlate(plate)" >Aggiungi al carrello | TOT: {{plate.price.toFixed(2)}} €</button>
                </li>
                <li v-else disabled>
                    <button>Non disponibile</button>
                </li>
            </ul>
>>>>>>> 46d51febb7b7715effee613b791362fd98993bf9
        </div>
        </div>
    </div>


</template>

<script>
export default {
    name: "Plates",
    props: {
        plates: Object,
        plate: Object,
    },
    data() {
        return {
            cart: {},
            price: this.plate.price,
            quantity: 1,
        };
    },
    methods: {
      addPlate(plate){

      let order={
        restaurant_id: plate.restaurant_id,
        name: plate.name,
        quantità: this.quantity,
        prezzo: this.price,
      }

        this.$emit('addCart', order, plate.name, plate.price);
      },
      more(price){
        this.quantity++;
        this.price += price;
      },
      less(price){
        if(this.quantity != 1){
          this.quantity --;
          this.price -= price;
        }
      }
    }
};
</script>

<style scoped lang="scss">

    .plate-card {
        word-wrap: break-word;
        display: inline-grid;
        width: calc(100% / 4 - 20px);
        margin: 10px;
        margin-right: 10px;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #ccc;
        img {
            width: 100%;
            height: 200px;
        }
    }
</style>
