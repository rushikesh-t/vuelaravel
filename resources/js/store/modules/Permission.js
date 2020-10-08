const state = {
  permission: localStorage.getItem('permission') || 0
};

const getters= {
  permission: state=>{
    return state.permission;
  },
};

const mutations = {
  setPermission: (state, playload) => {
    state.permission = playload
  },
  removePermission: (state) => {
    state.permission = 0
  }
};

const actions = {
  setPermission: ({ commit }, playload) => {
    localStorage.setItem('permission', playload)
    commit('setPermission', playload)
  },
  removePermission: ({ commit }) => {
    localStorage.removeItem('permission')
    commit('removePermission')
  },
};

export default {
  state,
  getters,
  mutations,
  actions
}