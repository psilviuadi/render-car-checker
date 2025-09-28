<template>
  <div class="min-h-screen bg-gray-900 text-gray-100 p-4">
    <h1 class="text-3xl font-bold mb-4 text-center">Car Checker</h1>

    <form @submit.prevent="fetchCarData" class="mb-6 flex flex-col space-y-2">
      <input v-model="plate" type="text" placeholder="Enter registration plate"
        class="p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <button v-if="!loading" type="submit" class="p-2 bg-blue-600 hover:bg-blue-700 rounded font-bold">
        Check Cars
      </button>
      <button v-else class="p-2 bg-gray-600 rounded font-bold" disabled>
        Loading...
      </button>
    </form>

    <div v-if="error" class="bg-red-600 text-white p-3 rounded mb-4">
      {{ error }}
    </div>

    <div v-if="vesData && motData" class="bg-gray-800 p-4 rounded mb-4 shadow">
      <ul class="space-y-1">
        <li>
          <strong>CAR: </strong>
          <span>{{ motData.make }} - {{ motData.model }} ({{ vesData.yearOfManufacture }}) {{ motData.primaryColour }}</span>
        </li>
        <li>
          <strong>Engine: </strong>
          <span>{{ vesData.fuelType }} ({{ engineSize }}L)</span>
        </li>
        <li>
          <strong>TAX: </strong>
          <strong v-if="vesData.taxStatus == 'Taxed'" class="text-green-600">Until {{ taxDueDate }}</strong>
          <strong v-else class="text-red-400">{{ taxDueDate }}</strong>
        </li>
        <li>
          <strong>MOT: </strong>
          <strong v-if="!latestMOTPassed" class="text-red-400">Failed</strong>
          <strong v-else-if="latestMOTAdvisoriesCount > 0" class="text-amber-600">Until {{ motDue }} ({{ latestMOTAdvisoriesCount }} Advisories)</strong>
          <strong v-else="latestMOT" class="text-green-600">Until {{ motDue }}</strong>
        </li>
      </ul>

    </div>

    <MOTComponent v-if="motData" :data="motData" />
  </div>
</template>

<script>
import axios from 'axios';
import VESComponent from './VESComponent.vue';
import MOTComponent from './MOTComponent.vue';

export default {
  components: { VESComponent, MOTComponent },
  data() {
    return {
      loading: false,
      plate: '',
      vesData: null,
      motData: null,
      error: null,
    };
  },
  computed: {
    engineSize() {
      if (this.vesData?.engineCapacity) {
        return (this.vesData?.engineCapacity / 1000).toFixed(1);
      }
      return "?.?";
    },
    taxDueDate() {
      if (!this.vesData?.taxDueDate) {
        return this.vesData.taxStatus ?? "NO DATA";
      }
      return new Date(this.vesData.taxDueDate).toLocaleString('en-GB', { month: 'numeric', year: 'numeric' });
    },
    latestMOT() {
      if (!this.motData?.motTests.length) {
        return null;
      }
      return this.motData.motTests[0];
    },
    latestMOTPassed() {
      return this.latestMOT.testResult == "PASSED";
    },
    latestMOTAdvisoriesCount() {
      return this.latestMOT.defects.length;
    },
    motDue() {
      return new Date(this.latestMOT.expiryDate).toLocaleString('en-GB', { month: 'numeric', year: 'numeric' });
    }
  },
  methods: {
    async fetchCarData() {
      this.error = null;
      this.vesData = null;
      this.motData = null;

      if (!this.plate) {
        this.error = 'Please enter a registration plate.';
        return;
      }

      try {
        this.loading = true;
        const [vesRes, motRes] = await Promise.all([
          axios.get(`/api/ves/${this.plate}`),
          axios.get(`/api/mot/${this.plate}`)
        ]);

        this.vesData = vesRes.data;
        this.motData = motRes.data;
      } catch (err) {
        console.error(err);
        this.error = 'Failed to fetch car data.';
      }
      this.loading = false;
    }
  }
};
</script>
<style scoped>
.loader {
  font-size: 1.2em;
  text-align: center;
  padding: 20px;
  animation: pulse 1s infinite;
}

@keyframes pulse {
  0% { opacity: 0.3; }
  50% { opacity: 1; }
  100% { opacity: 0.3; }
}
</style>
