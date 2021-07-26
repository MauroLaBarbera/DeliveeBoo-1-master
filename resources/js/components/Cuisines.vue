<template>
    <div>
        <span class="badge bg-primary" v-for="cuisine in cuisines" :key="`cuisine-${cuisine.id}`"
            >{{ cuisine}}
        </span>
    </div>
</template>

<script>
export default {
    name: "Cuisines",
    props: ["cuisines"]
};
</script>

<style scoped lang="scss">
div {
    display: flex;

    span {
        margin-right: 5px;
    }
}
</style>
