<template>
  <div class="bg-gray-800 p-4 rounded mb-4 shadow">
    <h2 class="text-xl font-bold mb-2">VES Info</h2>
    <ul class="space-y-1">
      <li><strong>Make:</strong> {{ data.make }}</li>
      <li><strong>Year:</strong> {{ data.yearOfManufacture }}</li>
      <li><strong>Engine:</strong> {{ data.engineCapacity }} cc</li>
      <li><strong>Fuel Type:</strong> {{ data.fuelType }}</li>
      <li><strong>CO2:</strong> {{ data.co2Emissions }} g/km</li>
      <li><strong>Tax Status:</strong> {{ data.taxStatus }}</li>
      <li><strong>Tax Due:</strong> {{ data.taxDueDate }}</li>
      <li><strong>MOT Status:</strong> {{ data.motStatus }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    data: { type: Object, required: true }
  }
};
</script>
