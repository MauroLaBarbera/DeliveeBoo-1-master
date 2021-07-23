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
            <div>
                <p><strong>Name: </strong>{{plate.name}}</p>
                <img class="img-fluid" v-if="plate.image" :src="plate.image" :alt="plate.name" />
                <p v-else>No Image for this Plate</p>
                <p class="mt-2"> <strong >Description: </strong> {{ plate.description }}</p>
                <strong>Price: </strong>{{ plate.price }}€
                <div class="mt-3" v-if="plate.visibility === 1">
                    <button class="btn btn-primary " @click="addPlate(plate)" >Add to Cart</button>
                </div>
            <div v-else>
                <button class="avaino btn btn-danger">Not avaiable</button>
            </div>
            </div>
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
    }

    .avaino {
        cursor: not-allowed;
    }
</style>
