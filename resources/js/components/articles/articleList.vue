<template>
  <div class="container">
    <div class="mt-3 mb-4">
      <button @click="toggleLanguage" class="btn btn-primary">{{ currentLanguage === 'pl' ? 'PL' : 'EN' }}</button>
    </div>
    <h1 class="mt-5 mb-4">Blog Linkhouse</h1>
    <form @submit.prevent="searchArticles" class="mb-4">
      <div class="input-group">
        <input type="text" v-model="searchQuery" class="form-control" :placeholder="currentLanguage === 'pl' ? 'Wyszukaj artykuł' : 'Search for an article'">
        <button type="submit" class="btn btn-primary">{{ currentLanguage === 'pl' ? 'Szukaj' : 'Search' }}</button>
      </div>
    </form>
    <div v-if="loading" class="alert alert-info">{{ currentLanguage === 'pl' ? 'Ładowanie...' : 'Loading...' }}</div>
    <ul v-else-if="!selectedArticle" class="list-group mb-3">
      <li v-for="article in displayedArticles" :key="article.guid" @click="showArticle(article.guid)"
        class="list-group-item article-item">
        <h2 class="article-title">{{ article.title }}</h2>
        <p>{{ currentLanguage === 'pl' ? 'Data publikacji' : 'Date of publication' }}: {{ article.pubDate }}</p>
      </li>
      <li v-if="displayedArticles.length === 0" class="list-group-item">{{ currentLanguage === 'pl' ? 'Brak artykułów pasujących do wyszukiwania.' : 'There are no articles matching your search.' }}</li>
    </ul>
    <div v-if="selectedArticle" class="mt-4">
      <h2>{{ selectedArticle.title }}</h2>
      <p v-html="selectedArticle.description"></p>
      <p>Kategorie: {{ selectedArticle.categories }}</p>
      <a :href="selectedArticle.link" target="_blank" class="btn btn-primary">{{ currentLanguage === 'pl' ? 'Link do artykułu' : 'Link to the article' }}</a>
      <button @click="selectedArticle = null" class="btn btn-secondary">{{ currentLanguage === 'pl' ? 'Powrót do listy' : 'Back to list' }}</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const articles = ref([]);
const filteredArticles = ref([]);
const displayedArticles = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const selectedArticle = ref(null);
const currentLanguage = ref('pl');

const getAllArticles = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/articles?lang=${currentLanguage.value}`);
    articles.value = response.data;
    displayedArticles.value = articles.value;
    loading.value = false;
  } catch (error) {
    console.error('Error', error);
    loading.value = false;
  }
}

const searchArticles = () => {
  filteredArticles.value = articles.value.filter(article => {
    return article.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      article.category.toLowerCase().includes(searchQuery.value.toLowerCase());
  });
  displayedArticles.value = filteredArticles.value;
}

const showArticle = async (guid) => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/article/${guid}?lang=${currentLanguage.value}`);
    selectedArticle.value = response.data;
    loading.value = false;
  } catch (error) {
    console.error('Error', error);
      loading.value = false;
  }
}

const toggleLanguage = () => {
  currentLanguage.value = currentLanguage.value === 'pl' ? 'en' : 'pl';
  getAllArticles();
}

onMounted(getAllArticles);
</script>

<style>
.article-title:hover {
  color: #0d6efd
}
.article-item {
  cursor: pointer;
}
</style>
