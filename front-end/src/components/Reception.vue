<template>
  <div class>
    <div class="row q-mb-lg">
      <div class="col">Choose whether to add new patient or search existing patient</div>
      <div class="order-last">
        <q-badge color="blue" v-text="userdata.deptname"></q-badge>
      </div>
    </div>
    <div class="row q-mb-lg q-mt-lg">
      <q-select
        v-model="optSelected"
        class="col"
        :options="patientOptions"
        label="New patient or search existing patient"
      />
    </div>
    <div v-if="optSelected == 'Existing Patient'">
      <div class="row q-mb-lg q-mt-lg">
        <q-input v-model="cardNumber" class="col" label="Search patient by card number"/>
        <q-btn color="primary" size="sm" @click.prevent="getPatient" label="Search"/>
      </div>
      <div class="q-pa-md q-gutter-sm" v-if="searchResponse">
        <q-banner inline-actions rounded class="bg-orange text-white" v-text="searchResponse"></q-banner>
      </div>
    </div>
    <div class="row q-mb-lg q-mt-lg" v-if="optSelected == 'New Patient'">
      <q-input v-model="newpatient" class="col" label="Patient's Name"/>
      <q-btn color="primary" size="sm" label="Add"/>
    </div>
    <div class="row q-mb-lg q-mt-lg" v-if="patientDetails.name!= undefined">
      <div class="col-12">Patient Details</div>
      <q-markup-table class="q-mb-lg q-mt-lg col-12">
        <thead>
          <tr>
            <th class="text-left">Card Number</th>
            <th class="text-right">Name</th>
            <th class="text-right">Gender</th>
            <th class="text-right">Phone</th>
            <th class="text-right">Email</th>
            <th class="text-right">Location</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" v-text="patientDetails.cardnumber"></td>
            <td class="text-right" v-text="patientDetails.name"></td>
            <td class="text-right">Male</td>
            <td class="text-right">+254-545-545-454</td>
            <td class="text-right">Something</td>
            <td class="text-right">Nairobi</td>
          </tr>
        </tbody>
      </q-markup-table>
      <div class="col-12">Book Patient</div>
      <q-select
        v-if="listDepartment"
        v-model="selectedDepartment"
        :options="listDepartment"
        label="Departments"
        class="mt-lg col-6 q-mb-lg q-mt-lg"
      />
      <q-select
        v-if="selectedDepartment"
        v-model="selectedStaff"
        :options="staffDeptList"
        label="Medic Practitioners"
        class="mt-lg col-6 q-mb-lg q-mt-lg"
      />
    </div>
    <div class="col-md-12" v-if="selectedStaff">
      <q-input v-model="reasonVisit" label="Rason for visit" class="col-12 q-mt-lg"/>
      <q-btn color="primary q-mt-lg" label="Book Patient" @click.prevent="bookPatient"/>
    </div>
  </div>
</template>

<script>
import Vuex from "vuex";
import axios from "axios";
export default {
  data() {
    return {
      checkOption: "",
      cardNumber: "",
      patientOptions: ["New Patient", "Existing Patient"],
      optSelected: "",
      newpatient: "",
      patientDetails: {},
      searchResponse: "",
      staffDeptList: [],
      selectedDepartment: "",
      selectedStaff: "",
      reasonVisit: ""
    };
  },
  watch: {
    optSelected: function() {
      this.searchResponse = "";
      this.patientDetails = {};
      this.cardNumber = "";
      this.selectedStaff = "";
      this.reasonVisit = "";
      this.selectedDepartment = "";
    },
    selectedDepartment: function(val) {
      let $this = this;
      $this.selectedStaff = "";
      $this.reasonVisit = "";
      console.log("dept", val);
      $this.staff.forEach(function(staff) {
        if (staff.deptid == val.value) {
          $this.staffDeptList.push({
            label: staff.staffname,
            value: staff.staffid
          });
        }
      });
    }
  },
  methods: {
    addPatient() {
      let $this = this;
      axios
        .post($this.stageurl + "users/eventOptions", {
          name: $this.newpatient
        })
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getPatient() {
      let $this = this;
      $this.patientDetails = {};
      $this.searchResponse = "";
      axios
        .get($this.stageurl + "getpatient/" + $this.cardNumber, {
          cardNumber: $this.cardNumber
        })
        .then(function(response) {
          if (
            response.data.patient != undefined &&
            response.data.patient.length > 0
          ) {
            console.log(response.data.patient[0]);
            $this.patientDetails = response.data.patient[0];
          } else {
            $this.searchResponse = "Patient not found.";
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    bookPatient() {
      let $this = this;
      this.$q
        .dialog({
          title: "Confirm",
          message: "Proceed to booking patient?",
          cancel: true,
          persistent: true
        })
        .onOk(() => {
          console.log(">>>> OK");
          axios
            .post($this.stageurl + "book", {
              patientid: $this.patientDetails.patientid,
              deptid: $this.selectedDepartment.value,
              staffid: $this.selectedStaff.value,
              notes: $this.reasonVisit,
              receptionid: $this.userdata.staffid
            })
            .then(function(response) {
              console.log("post success", response);
            })
            .catch(function(error) {
              console.log("post error", error);
            });
        })
        .onOk(() => {
          $this.optSelected = "";
          console.log(">>>> second OK catcher");
          this.$q.notify("Booked successfully");
        })
        .onCancel(() => {
          console.log(">>>> Cancel");
        })
        .onDismiss(() => {
          console.log("I am triggered on both OK and Cancel");
        });
    }
  },
  computed: {
    ...Vuex.mapState({
      stageurl: "stageurl",
      department: "$department",
      userdata: "$userdata",
      staff: "$staffs"
    }),
    listDepartment() {
      let results = [];
      if (this.department.length > 0) {
        this.department.forEach(function(item) {
          if (item.deptid != 6) {
            results.push({ label: item.deptname, value: item.deptid });
          }
        });
      }
      return results;
    }
  }
};
</script>

<style>
</style>
