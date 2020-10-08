const state = {
  cart: localStorage.getItem('cart') || 0
};

const getters = {
  getCart: state => {
    return state.cart;
  },
};

const mutations = {
  updateCart: (state, playload) => {
    state.cart = playload
  },
  emptyCart: (state) => {
    state.cart = 0
  }
};

const actions = {
  updateCart: ({ commit }, playload) => {
    localStorage.setItem('cart', playload)
    commit('updateCart', playload)
  },
  emptyCart: ({ commit }) => {
    localStorage.removeItem('cart')
    commit('emptyCart')
  },
};

export default {
  state,
  getters,
  mutations,
  actions
}