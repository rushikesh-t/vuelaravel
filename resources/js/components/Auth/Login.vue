<template>
  <div class="m-5">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="showAltWar">
      {{Warning}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showAltDan">
      Internal Server Error!
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="card">
      <h5 class="card-header bg-primary text-white">Login</h5>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row col">
            <label for="userName">User Name</label>
            <input
              type="text"
              v-model.trim="user.userName"
              class="form-control"
              :class="{ 'is-invalid': submitted && $v.user.userName.$error }"
            />
            <div
              v-if="submitted && !$v.user.userName.required"
              class="invalid-feedback"
            >User Name is required</div>
            <div
              v-if="submitted && !$v.user.userName.minLength"
              class="invalid-feedback"
            >User Name at last {{$v.user.userName.$params.minLength.min}}</div>
            <div
              v-if="submitted && !$v.user.userName.maxLength"
              class="invalid-feedback"
            >User Name at most {{$v.user.userName.$params.maxLength.max}}</div>
          </div>

          <div class="row col">
            <label for="password">Password</label>
            <input
              type="password"
              v-model.trim="user.password"
              class="form-control"
              :class="{ 'is-invalid': submitted && $v.user.password.$error }"
            />
            <div
              v-if="submitted && !$v.user.password.required"
              class="invalid-feedback"
            >Password is required</div>
            <div
              v-if="submitted && !$v.user.password.minLength"
              class="invalid-feedback"
            >Password at last {{$v.user.password.$params.minLength.min}}</div>
            <div
              v-if="submitted && !$v.user.password.maxLength"
              class="invalid-feedback"
            >Password at most {{$v.user.password.$params.maxLength.max}}</div>
              <router-link to="/forgot" tag="a" >
                <div>Forgot Password?</div>
              </router-link>
          </div>

          <div class="row mb-2 mt-2">
            <div class="col-3">
              <button class="btn btn-primary col-10">Login</button>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-danger col-10" @click="clear">Clear</button>
            </div>
            <div class="col-3">
              <router-link to="/registration" tag="button" class="btn btn-danger col-10">
                <div>Registration</div>
              </router-link>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      user: {
        userName: "",
        password: ""
      },
      Warning: "",
      submitted: true,
      showAltWar: false,
      showAltDan: false
    };
  },
  validations: {
    user: {
      userName: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(15)
      },
      password: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(15)
      }
    }
  },
  methods: {
    submit() {
      this.submitted = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      } else {
        const vm = this;
        axios
          .post("/api/login", {
            username: this.user.userName,
            password: this.user.password
          })
          .then(function(response) {
            if (response.data.status == "success") {
              vm.$store.dispatch("login", vm.user.userName);
              vm.$store.dispatch("setPermission", response.data.permission);
              
              localStorage.setItem("token", response.data.accessToken);

              vm.$router.push("/home");
            } else if (response.data.status == "userNotFound") {
              vm.Warning = "User Account Not Found!";
              vm.showAltWar = true;
              setTimeout(function() {
                vm.showAltWar = false;
              }, 3000);
            } else {
              vm.showAltDan = true;
              setTimeout(function() {
                vm.showAltDan = false;
              }, 3000);
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    clear() {
      this.submitted = false;
      this.user.userName = "";
      this.user.password = "";
    }
  }
};
</script>

<style scoped>
</style>