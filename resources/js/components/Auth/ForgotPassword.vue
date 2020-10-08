<template>
  <div class="m-5">
    <div
      class="alert alert-success alert-dismissible fade show"
      role="alert"
      v-if="showMsg"
    >Password Reset Link Send Successfully!</div>
    <div
      class="alert alert-warning alert-dismissible fade show"
      role="alert"
      v-if="showAlt"
    >No Account with This Email Address!</div>
    <div class="card">
      <h5 class="card-header bg-primary text-white">Forgot Password</h5>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row col">
            <label for="email">Email Address</label>
            <input
              type="text"
              v-model.trim="email"
              class="form-control"
              :class="{ 'is-invalid': submitted && $v.email.$error }"
            />
            <div
              v-if="submitted && !$v.email.required"
              class="invalid-feedback"
            >Email Address is required</div>
            <div
              v-if="submitted && !$v.email.email"
              class="invalid-feedback"
            >Enter Valid Email Address</div>
          </div>

          <div class="row mb-2 mt-2">
            <div class="col-3">
              <button class="btn btn-success col-10">Send Email</button>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-danger col-10" @click="clear">Clear</button>
            </div>
            <div class="col-3">
              <router-link to="/login" tag="button" class="btn btn-danger col-10">
                <div>Back</div>
              </router-link>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { required, email } from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      showAlt: false,
      submitted: false,
      showMsg: false
    };
  },
  validations: {
    email: {
      required,
      email
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
          .post("/api/forgot", {
            email: this.email
          })
          .then(function(response) {
            if (response.data.status == "success") {
              vm.showMsg = true;
              setTimeout(function() {
                vm.showMsg = false;
              }, 3000);
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