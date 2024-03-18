import Vue from 'vue'
import VueRouter from 'vue-router'
// import HomeView from '../views/HomeView.vue'
Vue.use(VueRouter)
function guardMyroute(to, from, next) {
  let isAuthenticated = false;
  if (localStorage.getItem('TOKEN')){
  isAuthenticated = true;
    }  else{
  isAuthenticated = false;
    }if (isAuthenticated) {
    next('/dashboard'); // allow to enter route
  }
  else {
    next(); // go to '/login';
  }
}
function guardMyroutee(to, from, next) {
  let isAuthenticated = false;

  if (localStorage.getItem('TOKEN')){
    isAuthenticated = true;
  }
  else{
    isAuthenticated = false;
    }  if (isAuthenticated) {
    next(); // allow to enter route
  }
  else {
    next('/'); // go to '/login';
  }
}
const routes = [
  // {
  //   path: '/',
  //   name: 'Dashboard',
  //   component: () => import('./../views/Dashboard')
  // },
  {
    path: '/dashboard',
    name: 'Dashboard',
    beforeEnter: guardMyroutee,

    component: () => import('./../views/Dashboard')
  },
  {
    path: '/reputation',
    beforeEnter: guardMyroutee,
    name: 'Reputation',
    component: () => import('./../views/Reputation')
  },
  {
    path: '*',
  name : "Errorpage",
  beforeEnter: guardMyroutee,

  component: () => import('./../views/Errorpage')


  },
  {
    path: '',
    name: 'login',
    beforeEnter: guardMyroute,
    component: () => import('./../views/Login')
  },
  {
    path: '/userlist',
    name: 'Userlist',
    beforeEnter: guardMyroutee,

    component: () => import('./../views/Userlist')
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
