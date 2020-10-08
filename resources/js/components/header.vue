<template>
  <nav class="navbar bg-light sticky-top">
    <ul class="nav nav-pills">
      <template v-if="this.$store.getters.userlog == ''">
        <router-link
          to="/login"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
          exact
        >
          <div>Login</div>
        </router-link>
        <router-link
          to="/registration"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
          exact
        >
          <div>Registration</div>
        </router-link>
      </template>
      <template v-else>
        <router-link
          to="/home"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
        >
          <div>Home</div>
        </router-link>
        <template v-if="this.$store.getters.permission == 0">
          <router-link
            :to="'/profile/' + user.userName"
            tag="li"
            class="nav-item nav-link nav-pointer"
            active-class="active"
          >
            <div>Profile</div>
          </router-link>
        </template>
      </template>
      <template v-if="this.$store.getters.permission != 0">
        <template v-if="this.$store.getters.permission == 2">
          <router-link
            to="/user"
            tag="li"
            class="nav-item nav-link nav-pointer"
            active-class="active"
          >
            <div>User</div>
          </router-link>
        </template>
        <router-link
          to="/category"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
        >
          <div>Category</div>
        </router-link>
        <router-link
          to="/product"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
        >
          <div>Product</div>
        </router-link>
        <router-link
          to="/order"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
        >
          <div>Orders</div>
        </router-link>
      </template>
    </ul>
    <div v-if="this.$store.getters.userlog != ''">
      <ul class="nav nav-pills">
        <router-link
          to="/cart"
          tag="li"
          class="nav-item nav-link nav-pointer"
          style="margin: auto;"
          active-class="active"
        >
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
          </svg> Cart
          <template v-if="cart != 0">
            <span class="badge badge-info">{{cart}}</span>
          </template>
        </router-link>
        <li class="nav-item dropdown">
          <a
            class="nav-link"
            data-toggle="dropdown"
            role="div"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <div style="height: 50px; width: 50px;">
              <img :src="'/upload/image/user/'+ user.image" class="w-100 h-100 rounded-circle" />
            </div>
          </a>
          <div class="dropdown-menu" style="padding: 1px;">
            <template v-if="this.$store.getters.permission == 0">
              <router-link to="/order" tag="div" class="dropdown-item">
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
                </svg> Orders
              </router-link>
              <div class="dropdown-divider"></div>
            </template>
            <button class="btn btn-primary dropdown-item" @click="logout">Logout</button>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      user: {
        id: null,
        userName: "",
        name: "",
        age: "",
        Gender: "",
        image: "default.png"
      }
    };
  },
  mounted() {
    this.getuser();
  },
  computed: {
    username() {
      this.user.image = "default.png";
      this.getuser();
      return this.$store.getters.userlog;
    },
    cart() {
      return this.$store.getters.getCart;
    }
  },
  watch: {
    username(newUser, oldUser) {},
    cart(newCart, oldCart) {}
  },
  methods: {
    getuser() {
      if (this.$store.getters.userlog != "") {
        let user = this.$store.getters.userlog;
        let validToken = localStorage.getItem("token");
        const vm = this;
        axios
          .post(
            "/api/getuser",
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
            let data = response.data;
            vm.user.id = data.id;
            vm.user.userName = data.username;
            vm.user.name = data.name;
            vm.user.age = data.age;
            vm.user.Gender = data.gender;
            if (data.avatar != null) {
              vm.user.image = data.avatar;
            }
          })
          .catch(function(error) {
            console.log(error);
            vm.isLoading = false;
          });
      }
    },
    logout() {
      let token = localStorage.getItem("token");
      const vm = this;
      axios
        .post(
          "/api/logout",
          {
            token: token
          },
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(function(response) {
          if (response.data == "success") {
            vm.$store.dispatch("logout");
            vm.$store.dispatch("emptyCart");
            vm.$store.dispatch("removePermission");
            vm.$router.push("/login");
            localStorage.removeItem("token");
          }
        })
        .catch(function(error) {
          console.log(error);
          vm.isLoading = false;
        });
    }
  }
};
</script>

<style scoped>
li {
  cursor: pointer;
}
.dropdown-menu {
  left: -78px;
}
</style>