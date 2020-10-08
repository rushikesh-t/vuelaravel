<template>
  <div class="m-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      User delete successfully!
    </div>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showErr">
      Access Denied!
    </div>

    <div class="card">
      <h5 class="card-header bg-success text-white">users</h5>

      <template v-if="isLoading">
        <div class="w-100 bg-light text-center" style="height:200px">
          <div class="lds-circle">
            <div></div>
          </div>
        </div>
      </template>

      <template v-if="!isLoading">
        <div class="card-body">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">User Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Age</th>
                <th scope="col">Gender</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
              <router-link
                :to="'/profile/'+user.username"
                tag="tr"
                v-for="(user, index) in users"
                :key="user.id"
              >
                <th>{{index + 1}}</th>
                <td>{{user.username}}</td>
                <td>{{user.email}}</td>
                <td>
                  <template v-if="user.type == 2">Admin</template>
                  <template v-else-if="user.type == 1">Employee</template>
                  <template v-else>User</template>
                </td>
                <td>{{user.age}}</td>
                <td>{{user.gender}}</td>
                <td>
                  <router-link
                    :to="'/user/edit/'+ user.username"
                    tag="button"
                    class="btn btn-primary"
                    active-class="active"
                  >Edit</router-link>
                </td>
                <td>
                  <button class="btn btn-danger" @click.prevent="deleteuser(user.id)">Delete</button>
                </td>
              </router-link>
            </tbody>
          </table>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      users: [],
      showAlt: false,
      showErr: false,
      isLoading: true,
      permission: 0
    };
  },
  beforeCreate() {
    if (this.$store.getters.userlog == "") {
      this.$router.push("/login");
    }
  },
  mounted() {
    this.permission = this.$store.getters.permission;
    this.loadUser();
  },
  methods: {
    loadUser() {
      let validToken = localStorage.getItem("token");
      let vm = this;
      axios
        .get("/api/data", {
          headers: {
            Authorization: "Bearer " + validToken
          }
        })
        .then(response => {
          this.users = response.data;
          this.isLoading = false;
        })
        .catch(function(error) {
          console.log(error);
          if(error.message == "Request failed with status code 401"){
            vm.showErr = true;
            setTimeout(function() {
              vm.showErr = false;
            }, 3000);

          }
          vm.isLoading = false;
        });
    },
    deleteuser(id) {
      let validToken = localStorage.getItem("token");
      const vm = this;
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