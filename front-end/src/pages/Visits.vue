<template>
  <q-page padding>
    <div class="row">
      <div class="col" v-if="visits">
        <q-table title="Patient Visit" :data="visits" :columns="columns" row-key="name"/>
      </div>
    </div>
  </q-page>
</template>

<style></style>

<script>
import Vuex from "vuex";

export default {
  name: "Visits",
  data() {
    return {
      columns: [
        {
          name: "visitid",
          label: "Ref ID",
          field: "visitid",
          sortable: true,
          required: true
        },
        {
          name: "cardnumber",
          label: "Card Number",
          align: "left",
          field: "cardnumber",
          sortable: true
        },
        {
          name: "patientname",
          align: "left",
          label: "Patients Name",
          field: "patientname",
          sortable: true
        },
        {
          name: "staffname",
          label: "Reception who booked",
          field: "staffname",
          sortable: true
        },
        {
          name: "notes",
          label: "Reason for visit",
          field: "notes",
          sortable: true
        }
      ]
    };
  },
  mounted() {
    this.$store.dispatch("loadvisits");
  },
  components: {},
  computed: {
    ...Vuex.mapState({
      stageurl: "stageurl",
      department: "$department",
      userdata: "$userdata",
      visits: "$visits"
    })
  }
};
</script>
