<template>
  <div class="min-h-screen bg-gray-900 text-gray-100 p-4">
    <h1 class="text-3xl font-bold mb-4 text-center">Car Checker</h1>

    <form @submit.prevent="fetchCarData" class="mb-6 flex flex-col space-y-2">
      <input
        v-model="plate"
        type="text"
        placeholder="Enter registration plate"
        class="p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        type="submit"
        class="p-2 bg-blue-600 hover:bg-blue-700 rounded font-bold"
      >
        Check Cars
      </button>
    </form>

    <div v-if="error" class="bg-red-600 text-white p-3 rounded mb-4">
      {{ error }}
    </div>

    <VESComponent v-if="vesData" :data="vesData" />
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
      plate: '',
      vesData: null,
      motData: null,
      error: null,
    };
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
        const [vesRes, motRes] = await Promise.all([
          axios.get(`/api/ves/${this.plate}`),
          axios.get(`/api/mot/${this.plate}`)
        ]);

        this.vesData = vesRes.data;
        this.motData = motRes.data;
      } catch (err) {
        console.error(err);
        this.error = 'Failed to fetch car data. Please try again later.';
      }
    }
  }
};
</script>
