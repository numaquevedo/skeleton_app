<template>
    <div>
        <div>
            <input v-model="filters.email" type="email" placeholder="Enter the Recipe's author email address"/>
            <input v-model="filters.keyword" type="text" placeholder="Enter a keyword"/>
            <input v-model="filters.ingredient" type="text" placeholder="Enter an ingredient name"/>
            <button @click="applyFilters">Filter</button>
        </div>
        <!-- Table -->
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
            <tr>
                <th>Recipe Name</th>
                <th>Author's Name</th>
                <th>Author's Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="recipe in data" :key="recipe.id">
                <td>{{ recipe.name }}</td>
                <td>{{ recipe.author.name }}</td>
                <td>{{ recipe.author.email }}</td>
                <td>
                    <NuxtLink :to="{path: `/recipes/${recipe.slug}`, query: {...route.query}}">View</NuxtLink>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div style="margin-top: 10px;">
            <button :disabled="page === 1" @click="prevPage">Previous</button>
            <span>Page {{ page }}</span>
            <button @click="nextPage">Next</button>
        </div>
    </div>
</template>
<script setup>
import {useRoute, useRouter} from 'vue-router';
import {ref, watch} from 'vue';

const route = useRoute();
const router = useRouter();

// State
const data = ref([])
const page = ref(1)
const filters = ref({
    email: route.query.email || '',
    keyword: route.query.keyword || '',
    ingredient: route.query.ingredient || '',
});

onMounted(() => {
    fetchData();
});

// Fetch function
const fetchData = async () => {
    try {
        const query = new URLSearchParams({
            email: filters.value.email,
            keyword: filters.value.keyword,
            ingredient: filters.value.ingredient,
            page: page.value,
        }).toString()

        const response = await fetch(`http://localhost:8888/api/recipes?${query}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        const result = await response.json();
        data.value = result.recipes || [];
    } catch (error) {
        //
    }
}

// Button click triggers API
const applyFilters = () => {
    page.value = 1; // Reset to first page
    router.push({
        path: '/',
        query: {
            ...filters.value,
        }
    });
    fetchData();
}

// Pagination controls
const nextPage = () => {
    page.value++
    fetchData()
}

const prevPage = () => {
    if (page.value > 1) {
        page.value--
        fetchData()
    }
}

watch(() => route.query, fetchData, { immediate: true });
</script>
