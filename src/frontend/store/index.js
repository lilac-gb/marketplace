export const state = () => ({
  message: '',
  cart: [],
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

  setCart({commit}, object) {
    commit('updateCart', object);
  },

  clearCart({commit}) {
    commit('clearCart');
  },
};

export const mutations = {
  updateMessage(state, message) {
    state.message = message;
  },

  clearMessage(state) {
    state.message = '';
  },

  updateCart(state, object) {
    state.cart = object;
  },

  clearCart(state) {
    state.cart = [];
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

  cart: (state) => state.cart,
};
