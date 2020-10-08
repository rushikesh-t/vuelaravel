<template>
  <div class="m-5">
    <div
      class="alert alert-warning alert-dismissible fade show"
      role="alert"
      v-if="showAlt"
    >No Account with This Email Address!</div>
    <div
      class="alert alert-success alert-dismissible fade show"
      role="alert"
      v-if="showMsg"
    >Password Reset Successfully</div>
    <div class="card">
      <h5 class="card-header bg-primary text-white">Reset Password</h5>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  v-model.trim="password"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.password.$error }"
                />
                <div class="invalid-feedback" v-if="!$v.password.required">Password is required.</div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.password.minLength"
                >Password must have at least {{ $v.password.$params.minLength.min }} letters.</div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.password.maxLength"
                >Password must have at most {{ $v.password.$params.maxLength.max }} letters.</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input
                  type="password"
                  v-model.trim="confirmPassword"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.confirmPassword.$error }"
                />
                <div
                  class="invalid-feedback"
                  v-if="!$v.confirmPassword.sameAsPassword"
                >Passwords must be identical.</div>
              </div>
            </div>
          </div>

          <div class="row mb-2 mt-2">
            <div class="col-3">
              <button class="btn btn-success col-10">Reset</button>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-danger col-10" @click="clear">Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {
  required,
  minLength,
  maxLength,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      password: "",
      confirmPassword: "",
      showAlt: false,
      showMsg: false,
      submitted: false
    };
  },
  validations: {
    password: {
      required,
      minLength: minLength(6),
      maxLength: maxLength(15)
    },
    confirmPassword: {
      sameAsPassword: sameAs("password")
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
        let token = this.$route.params.token;
        axios
          .post("/api/reset", {
            password: this.password,
            confirmPassword: this.confirmPassword,
            token: token
          })
          .then(function(response) {
            if (response.data.status == "success") {
              vm.showMsg = true;
              setTimeout(function() {
                vm.showMsg = false;
              }, 3000);
              vm.$router.push("/login");
            } else if (response.data.status == "emailNotFound") {
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