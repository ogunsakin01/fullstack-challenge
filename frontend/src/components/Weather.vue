<template>
  <div
      class="relative z-10"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
  >
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
    ></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div
          class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
      >
        <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-2xl font-bold">{{ modalUser.name }} <small class="text-sm font-medium text-gray-600">{{ modalWeather.details.last_updated_at }}</small></h3>
            <blockquote>
              <p class="font-medium text-base text-gray-700">
                {{ modalWeather.details.weather[0].main }}
                <small>({{ modalWeather.details.weather[0].description }})</small>
              </p>
            </blockquote>
            <div class="text-blue-500 text-xs">
              Temp: {{ modalWeather.details.main.temp }}
            </div>
            <div class="text-gray-500 text-xs">
              Min Temp: {{ modalWeather.details.main.temp_min }}
            </div>
            <div class="text-gray-500 text-xs">
              Max Temp: {{ modalWeather.details.main.temp_max }}
            </div>
            <div class="text-xs">
              Lat:{{ modalWeather.details.coord.lat }} Long:{{ modalWeather.details.coord.lon }}
            </div>
          </div>
          <div
              class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
          >

            <button
                class="mt-3 inline-flex w-full justify-center rounded-md border bg-blue px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                @click="closeModal"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Weather",
  props: ["modalWeather", "modalUser"],
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
  },
};
</script>

<style scoped>

</style>