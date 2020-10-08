<template>
  <div>
    <h2 class="offset-1">
      Orders
      <svg
        width="1em"
        height="1em"
        viewBox="0 0 16 16"
        class="bi bi-archive-fill"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"
        />
      </svg>
    </h2>
    <template v-if="isLoading">
      <div class="w-100 bg-light text-center" style="height:200px">
        <div class="lds-circle">
          <div></div>
        </div>
      </div>
    </template>
    <template v-else>
      <template v-if="orders.length == 0">
        <h3 class="p-5 bg-light">No Order History Found!</h3>
      </template>
      <template v-else>
        <table class="table table-striped table-hover">
          <tbody>
            <router-link
              :to="'/orderdetail/'+order.id"
              tag="tr"
              v-for="order in orders"
              :key="order.id"
            >
              <td>Order ID: {{order.id}}</td>
              <td>Order Amount: {{order.total_price}}</td>
              <td>Order Status: {{order.status}}</td>
              <td class="text-right">
                <svg
                  width="1em"
                  height="1em"
                  viewBox="0 0 16 16"
                  class="bi bi-info-square-fill"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.93 4.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
                  />
                </svg>
              </td>
            </router-link>
          </tbody>
        </table>
      </template>
    </template>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      orders: [],
      isLoading: false
    };
  },
  mounted() {
    this.getorder();
  },
  methods: {
    getorder() {
      this.isLoading = true;
      let validToken = localStorage.getItem('token');
      let user = this.$store.getters.userlog;
      const vm = this;
      axios
        .post("/api/getorder", {
          user: user
        },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          })
        .then(function(response) {
          vm.orders = response.data;
          vm.isLoading = false;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
tbody tr {
  cursor: pointer;
}

.lds-circle {
  display: inline-block;
  transform: translateZ(1px);
  padding: 70px 0;
  text-align: center;
}
.lds-circle > div {
  display: inline-block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  background: #777;
  animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
@keyframes lds-circle {
  0%,
  100% {
    animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
  }
  0% {
    transform: rotateY(0deg);
  }
  50% {
    transform: rotateY(1800deg);
    animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
  }
  100% {
    transform: rotateY(3600deg);
  }
}
</style>