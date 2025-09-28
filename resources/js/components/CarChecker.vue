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
      vesData: {"registrationNumber":"PY06FTD","taxStatus":"Taxed","taxDueDate":"2025-11-01","motStatus":"Valid","make":"AUDI","yearOfManufacture":2006,"engineCapacity":1986,"co2Emissions":169,"fuelType":"DIESEL","markedForExport":false,"colour":"BLACK","typeApproval":"M1","dateOfLastV5CIssued":"2024-11-01","motExpiryDate":"2026-05-09","wheelplan":"2 AXLE RIGID BODY","monthOfFirstRegistration":"2006-05"},
      motData: {"registration":"PY06FTD","make":"AUDI","model":"A4","firstUsedDate":"2006-05-31","fuelType":"Diesel","primaryColour":"Black","registrationDate":"2006-05-31","manufactureDate":"2006-05-31","engineSize":"1968","hasOutstandingRecall":"No","motTests":[{"registrationAtTimeOfTest":null,"motTestNumber":"859189994891","completedDate":"2025-04-22T12:57:38.000Z","expiryDate":"2026-05-09","odometerValue":"181892","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp lens slightly defective slight faded. (4.1.1 (b) (i))","type":"MINOR"},{"dangerous":false,"text":"Oil leak, but not excessive (8.4.1 (a) (i))","type":"ADVISORY"},{"dangerous":false,"text":"General corrosion \/ off side front bumper slight insecure.","type":"ADVISORY"},{"dangerous":false,"text":"Front Suspension arm pin or bush worn but not resulting in excessive movement both side upper front\/front and front rear arms bushes slight worn. (5.3.4 (a) (i))","type":"ADVISORY"},{"dangerous":false,"text":"Nearside Front Shock absorbers has light misting of oil (5.3.2 (b))","type":"ADVISORY"},{"dangerous":false,"text":"Rear Shock absorbers has slight external damage to the casing  corroded. (5.3.2 (b))","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"871448493969","completedDate":"2024-04-15T11:38:19.000Z","expiryDate":"2025-05-09","odometerValue":"178281","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Undertrays fitted.","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"365091906432","completedDate":"2024-04-15T07:59:43.000Z","expiryDate":null,"odometerValue":"178281","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Stop lamp(s) not working (4.3.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Rear Direction indicator not working (4.4.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim projected beam image is obviously incorrect (4.1.2 (c))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim unable to be tested No beam pattern. (4.1.2 (b))","type":"MAJOR"},{"dangerous":false,"text":"Undertrays fitted.","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"443303162590","completedDate":"2023-05-10T14:23:53.000Z","expiryDate":"2024-05-09","odometerValue":"176436","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"535287868660","completedDate":"2023-05-09T10:17:18.000Z","expiryDate":null,"odometerValue":"176435","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Rear Registration plate inscription illegible (0.1 (b))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp not working on dipped beam (4.1.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Headlamp aim unable to be tested (4.1.2 (b))","type":"MAJOR"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"331974589535","completedDate":"2022-06-28T15:56:01.000Z","expiryDate":"2023-06-27","odometerValue":"171469","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"122215436389","completedDate":"2021-03-22T17:03:22.000Z","expiryDate":"2022-03-21","odometerValue":"160614","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"All rear position lamps are dim","type":"ADVISORY"},{"dangerous":false,"text":"Brake pad warning lamp is on but pads not worn to the sensors","type":"ADVISORY"},{"dangerous":false,"text":"Power steering system worn but functioning as intended","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"581352947619","completedDate":"2021-03-19T11:16:43.000Z","expiryDate":null,"odometerValue":"160613","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp aim too low (4.1.2 (a))","type":"MAJOR"},{"dangerous":false,"text":"All rear position lamps are dim","type":"ADVISORY"},{"dangerous":false,"text":"Brake pad warning lamp is on but pads not worn to the sensors","type":"ADVISORY"},{"dangerous":false,"text":"Offside Front Exhaust emissions likely to be affected by an induction leak Turbo hose not sealed to turbo, possibly split (8.2.2.1 (b))","type":"MAJOR"},{"dangerous":false,"text":"Power steering system worn but functioning as intended","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"661548477420","completedDate":"2020-03-20T09:31:41.000Z","expiryDate":"2021-03-19","odometerValue":"158896","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Rear Brake hose slightly deteriorated (1.1.12 (b) (ii))","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Brake hose slightly deteriorated (1.1.12 (b) (ii))","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Service brake binding but not excessively (1.2.1 (f))","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"351144956053","completedDate":"2019-03-14T16:43:15.000Z","expiryDate":"2020-03-17","odometerValue":"151026","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"492977327213","completedDate":"2019-03-14T10:17:39.000Z","expiryDate":null,"odometerValue":"151026","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Front Headlamp not working on dipped beam (4.1.1 (a) (ii))","type":"MAJOR"},{"dangerous":false,"text":"Offside Front Service brake excessively binding (1.2.1 (f))","type":"MAJOR"},{"dangerous":false,"text":"Nearside Rear Tyre obviously under inflated (5.2.3 (h) (i))","type":"MINOR"},{"dangerous":false,"text":"Nearside Rear Nail in tyre","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"539088525351","completedDate":"2018-03-15T17:20:17.000Z","expiryDate":"2019-03-17","odometerValue":"143600","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Rear Child Seat fitted not allowing full inspection of adult belt","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Child Seat fitted not allowing full inspection of adult belt","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"897537506912","completedDate":"2017-03-18T11:07:02.000Z","expiryDate":"2018-03-17","odometerValue":"134701","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"471472446645","completedDate":"2017-03-18T11:07:01.000Z","expiryDate":null,"odometerValue":"134701","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Windscreen washer provides insufficient washer liquid (8.2.3)","type":"PRS"},{"dangerous":false,"text":"Headlamp aim too high both (1.8)","type":"PRS"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"208115526367","completedDate":"2016-05-16T08:24:28.000Z","expiryDate":"2017-05-15","odometerValue":"133339","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"839464935182","completedDate":"2015-05-14T10:24:51.000Z","expiryDate":"2016-05-14","odometerValue":"126848","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"785654835193","completedDate":"2015-05-14T10:24:51.000Z","expiryDate":null,"odometerValue":"126848","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Stop lamp not working (1.2.1b)","type":"PRS"},{"dangerous":false,"text":"Offside Stop lamp not working (1.2.1b)","type":"PRS"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"699305134143","completedDate":"2014-05-15T11:07:24.000Z","expiryDate":"2015-05-14","odometerValue":"121052","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"396644203025","completedDate":"2013-01-04T11:38:30.000Z","expiryDate":"2014-01-03","odometerValue":"113700","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"445873162141","completedDate":"2012-06-11T12:21:54.000Z","expiryDate":"2013-06-10","odometerValue":"111029","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Front Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Offside Front Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Offside Rear Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"},{"dangerous":false,"text":"Parking brake: parking brake efficiency only just met. It would appear that the braking system requires adjustment or repair. (3.7.B.7)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"608313451195","completedDate":"2011-06-02T14:32:46.000Z","expiryDate":"2012-06-02","odometerValue":"103870","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside front brake juddering slightly (3.7.A.2b)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"749263751178","completedDate":"2011-06-02T13:27:02.000Z","expiryDate":null,"odometerValue":"103868","odometerUnit":"MI","odometerResultType":"READ","testResult":"FAILED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Nearside Front Tyre tread depth below requirements of 1.6mm (4.1.E.1)","type":"FAIL"},{"dangerous":false,"text":"Nearside front brake juddering slightly (3.7.A.2b)","type":"ADVISORY"}]},{"registrationAtTimeOfTest":null,"motTestNumber":"531284850105","completedDate":"2010-06-03T12:40:02.000Z","expiryDate":"2011-06-02","odometerValue":"98275","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[]},{"registrationAtTimeOfTest":null,"motTestNumber":"924173759146","completedDate":"2009-06-02T12:18:17.000Z","expiryDate":"2010-06-01","odometerValue":"91610","odometerUnit":"MI","odometerResultType":"READ","testResult":"PASSED","dataSource":"DVSA","defects":[{"dangerous":false,"text":"Offside Rear Tyre worn close to the legal limit (4.1.E.1)","type":"ADVISORY"}]}]},
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
