import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from "../views/auth/Login.vue";
import Register from "../views/auth/Register.vue";
import Dashboard from "../views/Dashboard.vue";
import ListPosts from "../views/components/posts/ListPosts";
import ShowPost from "../views/components/posts/ShowPost";
import EditPost from "../views/components/posts/EditPost"
import ListVideos from "../views/components/videos/ListVideos"
import ShowVideo from "../views/components/videos/ShowVideo"

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { guestOnly: true }
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { authOnly: true }
  },
  {
    path: "/posts",
    name: "ListPosts",
    component: ListPosts,
    meta: { authOnly: true }
  },
  {
    path: "/posts/show/:id",
    name: "ShowPost",
    component: ShowPost,
    meta: { authOnly: true }
  },
  {
    path: "/posts/edit/:id",
    name: "EditPost",
    component: EditPost,
    meta: { authOnly: true }
  },
  {
    path: "/videos",
    name: "ListVideos",
    component: ListVideos,
    meta: { authOnly: true }
  },
  {
    path: "/videos/show/:id",
    name: "ShowVideo",
    component: ShowVideo,
    meta: { authOnly: true }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

function isLoggedIn() {
  return localStorage.getItem("token");
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.authOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!isLoggedIn()) {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.guestOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (isLoggedIn()) {
      next({
        path: "/dashboard",
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router
