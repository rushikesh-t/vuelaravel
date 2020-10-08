<template>
  <div class="m-5">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <div class="row">
          <h5 class="col-10">User Profile</h5>
          <template class="col" v-if="$store.getters.premission == 'admin'">
            <router-link to="/user" tag="button" class="btn btn-success">
              <strong>Back</strong>
            </router-link>
          </template>
        </div>
      </div>
      <div class="card-body">
        <template v-if="isLoading">
          <div class="w-100 bg-light text-center" style="height:300px">
            <div class="lds-circle">
              <div></div>
            </div>
          </div>
        </template>

        <template v-else>
          <div class="m-3">
            <div class="image-bg rounded-circle">
              <img :src="'/upload/image/user/'+ user.image" class="w-100 h-100" />
            </div>
            <h3 class="text-center">{{user.userName}}</h3>
          </div>
          <hr />
          <div class="m-3">
            <div class="offset-1">
              <div class="row">
                <p class="col-2 mt-2 text-right inline">Name:</p>
                <h3 class="col inline">{{user.name}}</h3>
              </div>
              <div class="row">
                <p class="col-2 mt-2 text-right inline">Email:</p>
                <h3 class="col inline">{{user.email}}</h3>
              </div>
              <div class="row">
                <p class="col-2 mt-2 text-right inline">Age:</p>
                <h3 class="col inline">{{user.age}}</h3>
              </div>
              <div class="row">
                <p class="col-2 mt-2 text-right inline">Gender:</p>
                <h3 class="col inline">{{user.Gender}}</h3>
              </div>
              <div class="row">
                <router-link
                  :to="'/user/edit/'+ user.userName"
                  tag="button"
                  class="offset-2 col-2 mt-2 btn btn-primary"
                  active-class="active"
                >Edit</router-link>
                <button
                  class="btn btn-danger offset-1 col-2 mt-2"
                  @click.prevent="deleteuser(user.id)"
                >Delete</button>
              </div>
            </div>
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
      user: {
        id: null,
        userName: "UserName",
        name: "FirstName LastName",
        email: "",
        age: "",
        Gender: "",
        image: "default.png"
      },
      isLoading: false
    };
  },
  mounted() {
    this.loaduser();
  },
  methods: {
    loaduser() {
      this.isLoading = true;
      this.user.userName = this.$route.params.user;
      if (
        this.$store.getters.permission == 2 ||
        this.user.userName == this.$store.getters.userlog
      ) {
        let validToken = localStorage.getItem("token");
        const vm = this;
        axios
          .post(
            "/api/getuser",
            {
              user: vm.user.userName
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
            vm.user.email = data.email;
            vm.user.Gender = data.gender;
            vm.isLoading = false;
            if (data.avatar != null) {
              vm.user.image = data.avatar;
            }
          })
          .catch(function(error) {
            console.log(error);
            vm.isLoading = false;
          });
      } else {
        this.$router.push({ path: "/home" });
      }
    },
    deleteuser(id) {
      const vm = this;
      let validToken = localStorage.getItem("token");
      axios
        .post(
          "/api/deleteuser",
          {
            id: id,
            userlog: vm.$store.getters.userlog
          },
          {
            headers: {
              Authorization: "Bearer " + validToken
            }
          }
        )
        .then(function(response) {
          if (response.data.status == "success") {
            if (!response.data.log) {
              vm.$store.dispatch("logout");
              vm.$store.dispatch("removePermission");
              vm.$router.push("/login");
            }
            vm.loadUser();
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

<style scoped>
.image-bg {
  height: 150px;
  width: 150px;
  margin: auto;
  overflow: hidden;
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