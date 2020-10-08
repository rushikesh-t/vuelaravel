import Vue from 'vue'
import Vuex from 'vuex'

import Logged from './modules/Logged';
import Permission from './modules/Permission';
import Cart from './modules/Cart';

Vue.use(Vuex);

export const store = new Vuex.Store({
  modules:{
    Logged,
    Permission,
    Cart
  }
})