<template>
    <RecipeDetails/>
    <NuxtLink :to="{ path: '/', query: route.query }">Back</NuxtLink>
</template>
<script setup>
import RecipeDetails from "~/components/RecipeDetails.vue";
import { useRoute } from "vue-router";

const route = useRoute();
const id = route.params.id;
</script>
