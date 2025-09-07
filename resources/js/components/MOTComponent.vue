<template>
  <div class="bg-gray-800 p-4 rounded mb-4 shadow">
    <h2 class="text-xl font-bold mb-2">MOT Info</h2>

    <div v-if="latestTest">
      <p><strong>Next MOT Due:</strong> {{ latestTest.expiryDate }}</p>
      <p><strong>Last MOT Result:</strong> {{ latestTest.testResult }}</p>
      <ul class="mt-2 space-y-1">
        <li
          v-for="(defect, index) in latestTest.defects"
          :key="index"
          :class="defect.dangerous ? 'text-red-400' : 'text-yellow-300'"
        >
          {{ defect.text }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: { type: Object, required: true }
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
  }
};
</script>
