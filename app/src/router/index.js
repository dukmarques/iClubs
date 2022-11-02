import { createRouter, createWebHistory } from 'vue-router'
import Cookies from 'js-cookie';
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      requireAuth: true
    }
  },
  {
    path: '/clubs',
    name: 'clubs',
    component: () => import(/* webpackChunkName: "clubs" */ '../views/Clubs.vue'),
    meta: {
      requireAuth: true
    }
  },
  {
    path: '/clubs/:id',
    name: 'clubDetails',
    component: () => import(/* webpackChunkName: "clubDetails" */ '../views/ClubDetails.vue'),
    meta: {
      requireAuth: true
    }
  },
  {
    path: '/users',
    name: 'users',
    component: () => import(/* webpackChunkName: "users" */ '../views/Users.vue'),
    meta: {
      requireAuth: true
    }
  },
  {
    path: '/users/:id',
    name: 'userDetails',
    component: () => import(/* webpackChunkName: "userDetails" */ '../views/UserDetails.vue'),
    meta: {
      requireAuth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import(/* webpackChunkName: "login" */ '../views/Login.vue')
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const authToken = Cookies.get('_adminToken');
  const requireAuth = to.matched.some(record => record.meta.requireAuth);

  if(requireAuth && !authToken) {
    next('/login');
  } else {
    if(!requireAuth && authToken) {
      next('/');
    } else {
      next();
    }
  }
});

export default router
