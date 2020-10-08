import { store } from './store/Store'

import User from "./components/user/User.vue"
import EditUser from "./components/user/EditUser.vue"
import ShowUser from "./components/user/ShowUser.vue"
import UserProfile from "./components/user/UserProfile.vue"

import Login from "./components/Auth/Login.vue"
import Registration from "./components/Auth/Registration.vue"
import Forgot from "./components/Auth/ForgotPassword.vue"
import Reset from "./components/Auth/ResetPassword.vue"

import Category from "./components/category/Category.vue"
import showCategory from "./components/category/ShowCategory.vue"
import addCategoty from "./components/category/AddCategory.vue"
import editCategory from "./components/category/EditCategory.vue"

import Product from './components/Product/Product.vue'
import AddProduct from './components/Product/AddProduct.vue'
import EditProduct from './components/Product/EditProduct.vue'
import ShowProduct from './components/Product/ShowProduct.vue'

import Home from './components/Home.vue'
import Cart from './components/Cart.vue'
import Order from './components/Order/Orders.vue'
import OrderDetail from './components/Order/OrderDetails.vue'

export const routes = [
  { path: "/login", component: Login,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog == "") {
        next();
      }
      else {
        next({ path: '/home' })
      }
    },
  },
  { path: "/registration", component: Registration,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog == "") {
        next();
      }
      else {
        next({ path: '/home' })
      }
    },
  },
  { path: "/forgot", component: Forgot },
  { path: "/reset/:token", component: Reset },

  {
    path: "/home", component: Home,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },

  {
    path: "/cart", component: Cart,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },

  {
    path: "/order", component: Order,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },
  {
    path: "/orderdetail/:id", component: OrderDetail,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },

  {
    path: "/profile/:user", component: UserProfile,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },

  {
    path: "/user/Edit/:user", component: EditUser,
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
  },

  {
    path: "/user", components: {
      default: User,
    },
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != 0) {
        next();
      }
      else {
        next({ path: '/login' })
      }
    },
    children: [
      { path: "/user", component: ShowUser },
    ]
  },

  {
    path: "/category", components: {
      default: Category,
    },
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else if (store.getters.permission != "admin") {
        next({ path: '/home' })
      }
      else {
        next({ path: '/login' })
      }
    },
    children: [
      { path: "/category", component: showCategory },
      { path: "/category/add", component: addCategoty },
      { path: "/category/edit/:id", component: editCategory },
    ]
  },

  {
    path: "/product", components: {
      default: Product,
    },
    beforeEnter: (to, from, next) => {
      if (store.getters.userlog != "") {
        next();
      }
      else if (store.getters.permission != "admin") {
        next({ path: '/home' })
      }
      else {
        next({ path: '/login' })
      }
    },
    children: [
      { path: "/product", component: ShowProduct },
      { path: "/product/add", component: AddProduct },
      { path: "/product/edit/:id", component: EditProduct },
    ]
  },

  { path: "*", redirect: "/login" }
];