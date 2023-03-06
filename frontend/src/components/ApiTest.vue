<script>
export default {
  data: () => ({
    apiResponse: null
  }),

  created() {
    this.fetchData()
  },

  methods: {
    async fetchData() {
      const userId = 1
      const url = "http://localhost/get-user-weather/" + userId;
      this.apiResponse = await (await fetch(url)).json()
    }
  }
}
</script>

<template>
  <div v-if="!apiResponse">
    Pinging the api...
    <h1 class="text-3xl font-light underline">
      Hello world!
    </h1>
  </div>

  <div v-if="apiResponse">
    The api responded with: <br />
    <code>
    {{ apiResponse }}
    </code>
  </div>

  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

    </div>
  </div>
</template>