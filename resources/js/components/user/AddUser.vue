<template>
  <div class="m-5">
    <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="showAlt">
      User added successfully!
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
      <h5 class="card-header bg-primary text-white">Add User</h5>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input
                  type="text"
                  v-model.trim="user.firstName"
                  id="firstName"
                  name="firstName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.firstName.$error }"
                />
                <div
                  v-if="submitted && !$v.user.firstName.required"
                  class="invalid-feedback"
                >First Name is required</div>
                <div
                  v-if="submitted && !$v.user.firstName.minLength"
                  class="invalid-feedback"
                >Name at last {{$v.user.firstName.$params.minLength.min}}</div>
                <div
                  v-if="submitted && !$v.user.firstName.maxLength"
                  class="invalid-feedback"
                >Name at most {{$v.user.firstName.$params.maxLength.max}}</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input
                  type="text"
                  v-model.trim="user.lastName"
                  id="firstName"
                  name="firstName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.lastName.$error }"
                />
                <div
                  v-if="submitted && !$v.user.lastName.required"
                  class="invalid-feedback"
                >First Name is required</div>
                <div
                  v-if="submitted && !$v.user.lastName.minLength"
                >Name at last {{$v.user.lastName.$params.minLength.min}}</div>
                <div
                  v-if="submitted && !$v.user.lastName.maxLength"
                >Name at most {{$v.user.lastName.$params.maxLength.max}}</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="age">age</label>
              <input
                type="text"
                v-model.number="user.age"
                id="firstName"
                name="firstName"
                class="form-control"
                :class="{ 'is-invalid': submitted && $v.user.age.$error }"
              />
              <div
                v-if="submitted && !$v.user.age.required"
                class="invalid-feedback"
              >Age is required</div>
              <div
                v-if="submitted && !$v.user.age.between"
                class="invalid-feedback"
              >Age should between {{$v.user.age.$params.between.min}} to {{$v.user.age.$params.between.max}}</div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <label for="Gender">Gender</label>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  value="Male"
                  v-model="user.gender"
                  :class="{ 'is-invalid': submitted && $v.user.gender.$error }"
                />
                <label class="form-check-label" for="Male">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  value="Female"
                  v-model="user.gender"
                  :class="{ 'is-invalid': submitted && $v.user.gender.$error }"
                />
                <label class="form-check-label" for="Female">Female</label>
              </div>
              <div
                v-if="submitted && !$v.user.gender.required"
                class="invalid-feedback"
                style="display: block"
              >Gender is required</div>
            </div>
          </div>

          <div class="row mb-2 mt-2">
            <div class="col-3">
              <button class="btn btn-primary col-11">Register</button>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-danger col-11" @click="clear">Clear</button>
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
  between
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      user: {
        firstName: "",
        lastName: "",
        age: null,
        gender: null
      },
      submitted: false,
      showAlt: false
    };
  },
  validations: {
    user: {
      firstName: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(15)
      },
      lastName: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(12)
      },
      age: {
        required,
        between: between(15, 60)
      },
      gender: {
        required
      }
    }
  },
  methods: {
    clear() {
      this.submitted = false;

      this.user.firstName = "";
      this.user.lastName = "";
      this.user.age = null;
      this.user.gender = null;
    },
    submit() {
      this.submitted = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      } else {
        let validToken = localStorage.getItem("token");
        const vm = this;
        axios
          .post(
            "/api/adduser",
            {
              user: this.user
            },
            {
              headers: {
                Authorization: "Bearer " + validToken
              }
            }
          )
          .then(function(response) {
            if (response.data == "success") {
              vm.submitted = false;
              vm.user.firstName = "";
              vm.user.lastName = "";
              vm.user.age = "";
              vm.user.gender = "";
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
  }
};
</script>