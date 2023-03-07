<script>
import iziToast from "izitoast";
export default {
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
    // const pusher = new Pusher("6b16e5b60b40dc1cd51b", {
    //   cluster: "eu",
    // });
    //
    // const channel = pusher.subscribe("weather");
    // channel.bind("weather", function (data) {
    //   console.log(data);
    //   console.log("log lol");
    // });
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
      }
      iziToast.error({
        title: "Error",
        message: response.message,
      });
    },

    async fetchUserWeather(user) {
      const userId = user.id
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
    <div
      class="relative z-10"
      aria-labelledby="modal-title"
      role="dialog"
      v-if="showModal"
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
  </div>
</template>
