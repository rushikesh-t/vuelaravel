const state = {
  userlog: localStorage.getItem('userName') || ''
};

const getters= {
  userlog: state=>{
    return state.userlog;
  },
};

const mutations = {
  login: (state, playload) => {
    state.userlog = playload
  },
  logout: (state) => {
    state.userlog = ''
  }
};

const actions = {
  login: ({ commit }, playload) => {
    localStorage.setItem('userName', playload)
    commit('login', playload)
  },
  logout: ({ commit }) => {
    localStorage.removeItem('userName')
    commit('logout')
  },
};

export default {
  state,
  getters,
  mutations,
  actions
}