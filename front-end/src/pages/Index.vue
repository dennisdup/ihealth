<template>
  <q-page padding>
    <div class="row">
      <div class="col" v-if="userdata">
        <div v-if="userdata.deptid==6">
          <reception></reception>
        </div>
        <div v-else>
          <general></general>
        </div>
      </div>
    </div>
  </q-page>
</template>

<style></style>

<script>
import Vuex from "vuex";
import Reception from "../components/Reception.vue";
import General from "../components/General.vue";

export default {
  name: "Visits",
  mounted() {
    this.$store.dispatch("loaddata");
  },
  components: {
    Reception,
    General
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
