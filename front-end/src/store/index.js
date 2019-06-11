import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
// import example from './module-example'

Vue.use(Vuex);

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation
 */

export default function(/* { ssrContext } */) {
  const Store = new Vuex.Store({
    modules: {
      // example
    },
    state: {
      stageurl: "http://127.0.0.1:8000/api/",
      $department: [],
      $userdata: [],
      $staffs: [],
      $visits: []
    },
    mutations: {
      SET_DATA(state, response) {
        state.$department = response.departments;
        state.$staffs = response.staffs;
        state.$userdata = response.user[0];
      },
      SET_VISITS(state, response) {
        state.$visits = response.visits;
      }
    },
    actions: {
      loaddata({ commit, state }) {
        axios.get(`${state.stageurl}user`).then(
          response => {
            if (response.data != undefined) {
              commit("SET_DATA", response.data);
            }
            console.log("response", response);
          },
          error => {
            console.log("Unable to fetch data, give error on page", error);
          }
        );
      },
      loadvisits({ commit, state }) {
        axios.get(`${state.stageurl}visits`).then(
          response => {
            if (response.data != undefined) {
              commit("SET_VISITS", response.data);
            }
            console.log("response", response);
          },
          error => {
            console.log("Unable to fetch data, give error on page", error);
          }
        );
      }
    },
    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEV
  });

  return Store;
}
