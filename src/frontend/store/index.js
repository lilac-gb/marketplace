export const state = () => ({
  message: '',
});

export const actions = {
  setMessage({ commit }, message) {
    if (typeof message === 'string') {
      commit('updateMessage', message);
    }
  },

  clearMessage({ commit }) {
    commit('clearMessage');
  },
};

export const mutations = {
  updateMessage(state, message) {
    state.message = message;
  },

  clearMessage(state) {
    state.message = '';
  },
};

export const getters = {
  isAuthenticated(state) {
    return state.auth.loggedIn;
  },

  loggedInUser(state) {
    return state.auth.user;
  },

  message: (state) => state.message,
};
