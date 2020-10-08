<template>
  <div>
    <div class="m-2 alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      Status Change Successfully
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="card m-5">
      <h3 class="card-header bg-primary text-white">
        Orders Details
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
      </h3>
      <div class="card-body">
        <template v-if="isLoading">
          <div class="w-100 bg-light text-center" style="height:200px">
            <div class="lds-circle">
              <div></div>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="row">
            <div class="col">
              <div
                :class="'rounded-circle row mx-auto '+ [order.status == 'Cancel' ? 'bg-warning' : 'bg-success']"
                style="width: 10px; height: 10px"
                for="place"
              />
              <div class="text-center">Place</div>
            </div>
            <div class="col">
              <div
                :class="'rounded-circle row mx-auto ' + [order.status == 'Cancel' ? ' bg-warning' : ['Accepted','Inprocess','Pickup','Deliverd'].includes(order.status) ? 'bg-success' : 'bg-dark']"
                style="width: 10px; height: 10px"
                for="accepted"
              />
              <div class="text-center">Accepted</div>
            </div>
            <div class="col">
              <div
                :class="'rounded-circle row mx-auto ' + [order.status == 'Cancel' ? ' bg-warning' : ['Inprocess','Pickup','Deliverd'].includes(order.status) ? 'bg-success' : 'bg-dark']"
                style="width: 10px; height: 10px"
                for="inprocess"
              />
              <div class="text-center">In process</div>
            </div>
            <div class="col">
              <div
                :class="'rounded-circle row mx-auto ' + [order.status == 'Cancel' ? ' bg-warning' : ['Pickup','Deliverd'].includes(order.status) ? 'bg-success' : 'bg-dark']"
                style="width: 10px; height: 10px"
                for="pickup"
              />
              <div class="text-center">Pickup</div>
            </div>
            <div class="col">
              <div
                :class="'rounded-circle row mx-auto ' + [order.status == 'Cancel' ? ' bg-warning' : ['Deliverd'].includes(order.status) ? 'bg-success' : 'bg-dark']"
                style="width: 10px; height: 10px"
                for="deliverd"
              />
              <div class="text-center">Deliverd</div>
            </div>
          </div>

          <p>Order ID: {{order.id}}</p>
          <p>Order Status: {{order.status}}</p>
          <p>Order Amount: {{order.total_price}} Rs.</p>
          <p v-if="order.status == 'Deliverd'">Delivery Date: {{order.delivered_at}}</p>

          <div class="row" v-if="permission >= 1">
            <h5 class="col-2">Status:</h5>
            <div class="col">
              <select
                class="form-control"
                v-model="order.status"
                @change="statusChange(order.id,order.status)"
                :disabled="order.status == 'Cancel' || order.status  == 'Deliverd'"
              >
                <option value="Place">Place</option>
                <option value="Accepted">Accepted</option>
                <option value="Inprocess">Inprocess</option>
                <option value="Pickup">Pickup</option>
                <option value="Deliverd">Deliverd</option>
                <option value="Cancel" disabled>Cancel</option>
              </select>
            </div>
          </div>
          <template v-else>
            <template v-if="order.status  != 'Deliverd' && order.status  != 'Cancel'">
              <button class="btn btn-danger" @click="statusChange(order.id,'Cancel')">Cancel Order</button>
            </template>
          </template>
          <div class="m-2 p-1 border">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">Product</th>
                  <th scope="col">Price</th>
                  <th scope="col">Quantity</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="products in order_details" :key="products.id">
                  <td>{{products.product_name}}</td>
                  <td>{{products.product_price}} Rs.</td>
                  <td>{{products.quantity}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      order: [],
      order_details: [],
      isLoading: false,
      permission: 0,
      showAlt: false
    };
  },
  // created(){
  //   this.getorder();
  // },
  mounted() {
    this.getorder();
    this.permission = this.$store.getters.permission;
  },
  methods: {
    getorder() {
      this.isLoading = true;
      let id = this.$route.params.id;
      let validToken = localStorage.getItem("token");
      let user = this.$store.getters.userlog;
      const vm = this;
      axios
        .post(
          "/api/getorderdetails",
          {
            id: id,
            user: user
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          vm.order = response.data.order;
          vm.order_details = response.data.order_details;
          vm.isLoading = false;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    statusChange(id, status) {
      this.order.status = status;
      let validToken = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/changestatus",
          {
            id: id,
            status: status
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          if (response.data == "success") {
            vm.showAlt = true;
            setTimeout(function() {
              vm.showAlt = false;
            }, 3000);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style>
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