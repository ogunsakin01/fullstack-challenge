<script>
import iziToast from "izitoast";
import Weather from "./Weather.vue";
export default {
  components: { Weather },

  data: () => ({
    users: [],
    weatherList: [],
    baseUrl: "http://localhost/",
    showModal: false,
    modalWeather: {},
    modalUser: {},
  }),

  created() {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      const url = this.baseUrl + "users";
      const response = await (await fetch(url)).json();
      if (response.success) {
        this.users = response.data;
        iziToast.success({
          title: "Success",
          message: response.message,
        });
      } else if (!response.success) {
        iziToast.error({
          title: "Error",
          message: response.message,
        });
      }
    },

    async fetchUserWeather(user) {
      const userId = user.id;
      const url = this.baseUrl + "get-user-weather/" + userId;
      const response = await (await fetch(url)).json();
      if (response.success && response.code === 200) {
        this.weatherList.forEach((weather, index) => {
          if (weather.user_id === userId) {
            this.weatherList.splice(index, 1);
          }
        });
        let weather = {
          user_id: userId,
          details: response.data,
        };
        this.weatherList.push(weather);
        this.modalWeather = weather;
        this.modalUser = user;
        this.showModal = true;
        iziToast.success({
          title: "Success",
          message: response.message,
        });
      } else if (response.success && response.code === 201) {
        iziToast.info({
          title: "Message",
          message: response.message,
        });
      } else if (!response.success) {
        iziToast.error({
          title: "Error",
          message: response.message,
        });
      }
    },

    closeModal() {
      this.showModal = false;
      this.modalWeather = {};
      this.modalUser = {};
    },
  },
};
</script>

<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6">
    <div
      class="w-full mt-6 px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
    >
      <div class="mx-auto">
        <div class="bg-white p-4 rounded-lg shadow-md">
          <div class="overflow-auto">
            <table class="table-auto w-full">
              <thead>
                <tr class="bg-gray-200">
                  <th class="px-4 py-2">ID</th>
                  <th class="px-4 py-2">User</th>
                  <th class="px-4 py-2">Action</th>
                </tr>
              </thead>
              <tbody v-if="users.length > 0">
                <tr class="bg-white" v-bind:key="user.id" v-for="user in users">
                  <td class="border px-4 py-2">{{ user.id }}</td>
                  <td class="border px-4 py-2">{{ user.name }}</td>
                  <td class="border px-4 py-2">
                    <button
                      class="bg-blue-500 hover:bg-blue-600 text-sm px-2 py-1 text-white rounded-md"
                      @click="fetchUserWeather(user)"
                    >
                      View Weather
                    </button>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr class="bg-white">
                  <td class="border px-4 py-2 text-center" colspan="3">
                    No user data yet
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <weather
      v-if="showModal"
      :modal-user="modalUser"
      :modal-weather="modalWeather"
      @closeModal="closeModal"
    ></weather>
  </div>
</template>
