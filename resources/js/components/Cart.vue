<template>
  <div>
    <h2 class="offset-1">
      Your Cart
      <svg
        width="1em"
        height="1em"
        viewBox="0 0 16 16"
        class="bi bi-cart4"
        fill="currentColor"
      >
        <path
          fill-rule="evenodd"
          d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"
        />
      </svg>
    </h2>
    <div class="m-2 alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      {{msgAlert}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <template v-if="isLoading">
      <div class="w-100 bg-light text-center" style="height:200px">
        <div class="lds-circle">
          <div></div>
        </div>
      </div>
    </template>
    <template v-else>
      <template v-if="products.length == 0">
        <h3 class="p-5 bg-light">
          No Item in Cart!
          <svg
            width="1em"
            height="1em"
            viewBox="0 0 16 16"
            class="bi bi-cart4"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"
            />
          </svg>
        </h3>
      </template>
      <template v-else>
        <div class="m-3 ml-5">
          <h5>Item: {{products.length}}</h5>
          <h5>Total price: {{price}} Rs.</h5>
          <button class="btn btn-primary col-3" @click="placeOrder">Place Order</button>
        </div>
        <table class="table table-striped">
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td>
                <template v-if="product.product_image != null">
                  <img :src="'upload/image/product/'+product.product_image" style="width: 100px;" />
                </template>
                <template v-else>
                  <img src="upload/image/product/default.png" style="width: 100px;" />
                </template>
              </td>
              <td>{{product.product_name}}</td>
              <td>
                <div
                  style="max-width: 150px; max-height: 100px; overflow: hidden;"
                >{{product.product_description}}</div>
              </td>
              <td>{{product.product_price}} Rs.</td>
              <td>{{product.category_name}}</td>
              <td>
                <select
                  class="form-control"
                  id="exampleFormControlSelect1"
                  v-model="product.quantity"
                  @change="quantitychange(product.quantity,product.id)"
                >
                  <option v-for="n in 10" :key="n">{{n}}</option>
                </select>
              </td>
              <td>
                <button class="btn btn-danger col-12" @click="removeCart(product.id)">
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
              </td>
            </tr>
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
      products: [],
      price: 0,
      isLoading: false,
      showAlt: false,
      msgAlert: ""
    };
  },
  beforeCreate() {
    if (this.$store.getters.userlog == "") {
      console.log("userlog");
      this.$router.push("/login");
    }
  },
  mounted() {
    this.getproduct();
  },
  methods: {
    getproduct() {
      this.isLoading = true;
      let validToken = localStorage.getItem("token");
      let user = this.$store.getters.userlog;
      const vm = this;
      axios
        .post(
          "/api/cartproduct",
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
          vm.products = response.data;
          vm.isLoading = false;
          vm.$store.dispatch("updateCart", vm.products.length);
          vm.totalPrice();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    removeCart(id) {
      this.totalPrice();
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
          if (response.data.status == "removed") {
            vm.getproduct();
            vm.msgAlert = "Remove From Cart Successfully!";
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
    quantitychange(quantity, product) {
      this.totalPrice();
      let user = this.$store.getters.userlog;
      let validToken = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/changequantity",
          {
            user: user,
            id: product,
            quantity: quantity
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {})
        .catch(function(error) {
          console.log(error);
        });
    },
    placeOrder() {
      let user = this.$store.getters.userlog;
      let validToken = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/placeOrder",
          {
            user: user,
            price: vm.price,
            products: vm.products
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          if (response.data == "success") {
            vm.getproduct();
            vm.$store.dispatch("emptyCart");
            vm.msgAlert = "Order Place Successfully!";
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
    totalPrice() {
      let value = this.products;
      let output = 0;
      for (var i = 0; i < value.length; i++) {
        let row = value[i];
        output += row.product_price * row.quantity;
      }
      this.price = output;
    }
  }
  // watch: {
  //   products: {
  //     handler: function(value) {
  //       let output = 0;
  //       console.log("watch");
  //       for (var i = 0; i < value.length; i++) {
  //         let row = value[i];
  //         output += row.product_price * row.quantity;
  //       }
  //       this.price = output;
  //     }
  //   }
  // }
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