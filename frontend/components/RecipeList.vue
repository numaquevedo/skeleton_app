<template>
    <!-- centered container with Tailwind's 'container' class -->
    <div class="mx-auto max-w-[100%] px-4 sm:px-6 lg:px-8">
        <div class="w-full mx-auto">
            <div class="min-w-0 flex-1 w-full">
                <h2 class="text-3xl font-bold">Recipes</h2>
            </div>

            <!-- Filters + Button wrapper -->
            <div class="mt-4 w-full">
                <div class="px-4 py-2">
                    <div class="flex flex-row items-center space-x-4">
                        <input
                            v-model="filters.email"
                            type="email"
                            class="flex-2 py-1.5 pl-2 pr-3 mr-1 text-base text-gray-900 placeholder-gray-400 focus:outline-none sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Filter by Author's email address"
                        />

                        <input
                            v-model="filters.keyword"
                            type="text"
                            class="flex-2 py-1.5 pl-2 pr-3 mr-1 text-base text-gray-900 placeholder-gray-400 focus:outline-none sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Filter by keyword"
                        />

                        <input
                            v-model="filters.ingredient"
                            type="text"
                            class="flex-2 py-1.5 pl-2 pr-3 mr-1 text-base text-gray-900 placeholder-gray-400 focus:outline-none sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Filter by ingredient"
                        />

                        <button
                            @click="applyFilters"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none"
                        >
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Table wrapper: width set to 75% of viewport, centered -->
                <div class="w-full mx-auto inline-block py-2 align-middle sm:px-6 lg:px-8">
                    <table class="w-full divide-y divide-gray-300">
                        <thead>
                        <tr>
                            <th
                                scope="col"
                                class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                            >
                                Recipe Name
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                            >
                                Author Name
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                            >
                                Author Email
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                            >
                                Actions
                            </th>
                            <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr v-for="recipe in data" :key="recipe.id">
                            <td
                                class="py-4 pr-3 pl-4 text-sm text-left font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                            >
                                {{ recipe.name }}
                            </td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">
                                {{ recipe.author.name }}
                            </td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">
                                {{ recipe.author.email }}
                            </td>
                            <td
                                class="relative py-4 pr-4 pl-3 text-left text-sm font-medium whitespace-nowrap sm:pr-0"
                            >
                                <NuxtLink
                                    :to="{ path: `/recipes/${recipe.slug}`, query: { ...route.query } }"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    View
                                </NuxtLink>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="w-full mt-4 flex items-center justify-between px-4">
                    <button
                        @click="prevPage"
                        :disabled="page === 1"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <span class="text-sm text-gray-700">Page {{ page }}</span>
                    <button
                        @click="nextPage"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                    >
                        Next
                    </button>
                </div>
            </div>
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

watch(() => route.query, fetchData, {immediate: true});
</script>
