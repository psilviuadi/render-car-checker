<template>
  <div class="bg-gray-800 p-4 rounded mb-4 shadow text-white">
    <h2 class="text-xl font-bold mb-4">MOT
      <span :class="getResultClass(latestTest)">{{ latestTest.testResult }}</span>
    </h2>

    <div v-if="latestTest" class="mb-6">
      <p><strong>Next MOT Due:</strong> {{ latestTest.expiryDate }}</p>
    </div>

    <!-- Accordion for MOT History -->
    <div>
      <h3 class="text-lg font-semibold mb-2">MOT History</h3>
      <div v-for="(motTest, motIndex) in data.motTests" :key="motTest.motTestNumber"
        class="border border-gray-700 rounded mb-2">
        <button @click="expandIndex = expandIndex === motIndex ? null : motIndex"
          class="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 focus:outline-none flex justify-between items-center">
          <span :class="getResultClass(motTest)">
            {{ new Date(motTest.completedDate).toLocaleDateString() }} — {{ motTest.testResult }}
            <span v-if="motTest.defects.length > 0"> ({{ motTest.defects.length }} Defects)</span>
          </span>
          <span>
            {{ expandIndex === motIndex ? '▲' : '▼' }}
          </span>
        </button>

        <div v-if="expandIndex === motIndex" class="px-4 py-2 bg-gray-900">
          <p><strong>Expiry:</strong> {{ motTest.expiryDate || 'N/A' }}</p>
          <p><strong>Odometer:</strong> {{ motTest.odometerValue }} {{ motTest.odometerUnit }}</p>
          <ul class="mt-2 space-y-1">
            <li v-for="(defect, index) in motTest.defects" :key="index"
              :class="defect.dangerous ? 'text-red-400' : 'text-yellow-300'">
              {{ defect.text }}
            </li>
            <li v-if="motTest.defects.length === 0" class="text-green-400">
              No defects reported
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: { type: Object, required: true }
  },
  data() {
    return {
      expandIndex: 0,
    };
  },
  computed: {
    latestTest() {
      if (!this.data.motTests || !this.data.motTests.length) return null;

      // Sort by completedDate descending
      return this.data.motTests
        .slice()
        .sort(
          (a, b) => new Date(b.completedDate) - new Date(a.completedDate)
        )[0];
    }
  },
  methods: {
    getResultClass(motTest) {
      if (motTest.testResult === 'FAILED') {
        return 'text-red-400 font-semibold';
      }
      const hasAdvisories = motTest.defects.some(defect => defect.type === 'ADVISORY');
      return hasAdvisories ? 'text-orange-400 font-semibold' : 'text-green-400 font-semibold';
    }
  }
};
</script>
