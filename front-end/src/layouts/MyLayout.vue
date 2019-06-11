<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="bg-purple glossy">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          aria-label="Menu"
          icon="menu"
        />

        <q-toolbar-title>ihealth</q-toolbar-title>
        <q-item-label style="text-transform:Capitalize;" v-text="userdata.staffname"></q-item-label>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" bordered content-class="bg-grey-2">
      <q-list>
        <q-item-label header>Menu</q-item-label>
        <q-item to="/" exact>
          <q-item-section avatar>
            <q-icon name="home"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>Home</q-item-label>
          </q-item-section>
        </q-item>
        <q-item to="/visits" exact>
          <q-item-section avatar>
            <q-icon name="wc"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>Patient Visits</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from "quasar";
import Vuex from "vuex";
export default {
  name: "MyLayout",
  data() {
    return {
      leftDrawerOpen: this.$q.platform.is.desktop
    };
  },
  methods: {
    openURL
  },
  computed: {
    ...Vuex.mapState({
      stageurl: "stageurl",
      department: "$department",
      userdata: "$userdata"
    })
  }
};
</script>

<style></style>
