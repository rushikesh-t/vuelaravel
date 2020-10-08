<template>
  <div>
    <div class="m-2 alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      {{msgAlert}} Cart Successfully!
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <h2 class="offset-1">Home</h2>
    <template v-if="isLoading">
      <div class="w-100 bg-light text-center" style="height:200px">
        <div class="lds-circle">
          <div></div>
        </div>
      </div>
    </template>
    <template v-else>
      <div
        class="card m-2 d-inline-flex"
        style="width: 225px;"
        v-for="product in products"
        :key="product.id"
      >
        <template v-if="product.product_image != null">
          <img
            :src="'upload/image/product/'+product.product_image"
            class="card-img-top"
            height="150px"
          />
        </template>
        <template v-else>
          <img src="upload/image/product/default.png" class="card-img-top" style="height: 150px;" />
        </template>
        <div class="card-body">
          <h5 class="card-title">{{product.product_name}}</h5>
          <div style="max-height: 100px; overflow:hidden;">
            <p class="card-text">{{product.product_description}}</p>
          </div>
          <p class="card-text">{{product.product_price}} Rs.</p>
          <template v-if="carts.includes(product.id)">
            <button class="btn btn-danger col-12" @click="addToCart(product.id)">
              <svg
                width="1em"
                height="1em"
                viewBox="0 0 16 16"
                class="bi bi-cart-dash-fill"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM4 14a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm7 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM6.5 7a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4z"
                />
              </svg>
            </button>
          </template>
          <template v-else>
            <button class="btn btn-primary col-12" @click="addToCart(product.id)">
              <svg
                width="1em"
                height="1em"
                viewBox="0 0 16 16"
                class="bi bi-cart-plus-fill"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM4 14a1 1 0 1 1 2 0 1 1 0 0 1-2 0zm7 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z"
                />
              </svg>
            </button>
          </template>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      products: [],
      carts: [],
      isLoading: false,
      showAlt: false,
      msgAlert: "Add To"
    };
  },
  mounted() {
    this.loadProduct();
  },
  methods: {
    loadProduct() {
      let validToken = localStorage.getItem("token");
      // this.isLoading = true;
      axios
        .get("/api/product", {
          headers: {
            Authorization: "Bearer " + validToken
          }
        })
        .then(response => {
          this.products = response.data;
          this.getcart();
          // this.isLoading = false;
        });
    },
    addToCart(id) {
      let user = this.$store.getters.userlog;
      let validToken = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/addToCart",
          {
            user: user,
            id: id
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          vm.loadProduct();
          if (response.data.status == "added") {
            vm.msgAlert = "Add To";
            vm.showAlt = true;
            setTimeout(function() {
              vm.showAlt = false;
            }, 3000);
          } else if (response.data.status == "removed") {
            vm.msgAlert = "Remove From";
            vm.showAlt = true;
            setTimeout(function() {
              vm.showAlt = false;
            }, 3000);
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getcart() {
      let user = this.$store.getters.userlog;
      let validToken = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/getcart",
          {
            user: user
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          vm.carts = response.data;
          vm.$store.dispatch("updateCart", vm.carts.length);
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
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