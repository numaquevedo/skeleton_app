<template>
    <div v-if="recipe" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="mx-auto max-w-2xl items-center justify-between gap-x-8 lg:mx-0 lg:max-w-none">
            <h1 class="text-4xl font-bold mb-6">{{ recipe.name }}</h1>
            <div class="flex items-center mb-6">
                <!-- Gravatar -->
                <img
                    :src="recipe.author.gravatar"
                    :alt="recipe.author.name"
                    class="w-7 h-7 rounded-full mr-4"
                />
                <div>
                    <p class="font-medium text-gray-900">{{ recipe.author.name }}</p>
                    <p class="text-sm text-gray-500">{{ recipe.author.email }}</p>
                </div>
            </div>
            <p class="mb-6 text-gray-700">{{ recipe.description }}</p>
            <div v-if="recipe.ingredients" class="mb-6">
                <h2 class="text-2xl font-semibold mb-2">Ingredients</h2>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li v-for="(item, index) in recipe.ingredients" :key="index">
                        {{ item.ingredient_details }}
                    </li>
                </ul>
            </div>
            <div v-if="recipe.steps">
                <h2 class="text-2xl font-semibold mb-2">Steps</h2>
                <ol class="list-decimal list-inside space-y-1 text-gray-700">
                    <li v-for="step in recipe.steps" :key="step.id">
                        {{ step.step }}
                    </li>
                </ol>
            </div>
            <div class="mt-4">
                <NuxtLink :to="{ path: '/', query: route.query }" class="mt-4 pt-2 text-blue-500 hover:text-blue-700">Back</NuxtLink>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from 'vue-router';

const route = useRoute();
const recipe = ref(null);

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
        //console.error(error);
    }
});

</script>
