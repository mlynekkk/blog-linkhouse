
import { createRouter, createWebHistory} from 'vue-router';
import articleList from '../components/articles/articleList.vue';
import notFound from '../components/NotFound.vue';


const routes = [
  {
    path: '/',
    component: articleList
  },
  {
    path: '/:pathMatch(.*)*',
    component: notFound
  },
];

const router = new createRouter({
  history: createWebHistory(),
  routes
});

export default router;
