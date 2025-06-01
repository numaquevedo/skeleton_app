<template>
    <div v-if="recipe">
        <h2>{{ recipe.name }}</h2>
        <img :src="recipe.author.gravatar"/>
        <p><strong>Email:</strong> {{ recipe.author.email }}</p>
        <p>{{recipe.description}}</p>
    </div>
</template>
<script setup>
import {onMounted, ref} from 'vue'
import {useRoute} from 'vue-router'

const route = useRoute()
const recipe = ref(null)

onMounted(async () => {
    const id = route.params.id;
    try {
        const response = await fetch(`http://localhost:8888/api/recipes/${id}`,
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            }
        );
        recipe.value = await response.json();
    } catch (error) {
        console.error(error);
    }
});
</script>
