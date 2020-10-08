<template>
  <div class="m-5">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="showAlt">
      {{altMsg}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="card">
      <h5 class="card-header bg-primary text-white">Registration</h5>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col form-group">
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
                v-if="submitted && !$v.user.userName.alphaNum"
                class="invalid-feedback"
              >Don't use special characters in User Name</div>
              <div
                v-if="submitted && !$v.user.userName.minLength"
                class="invalid-feedback"
              >User Name must have at least {{$v.user.userName.$params.minLength.min}} letters.</div>
              <div
                v-if="submitted && !$v.user.userName.maxLength"
                class="invalid-feedback"
              >User Name must have at most {{$v.user.userName.$params.maxLength.max}} letters.</div>
            </div>
          </div>

          <div class="row">
            <div class="col form-group">
              <label for="email">Email</label>
              <input
                type="text"
                v-model.trim="user.email"
                class="form-control"
                :class="{ 'is-invalid': submitted && $v.user.email.$error }"
              />
              <div
                v-if="submitted && !$v.user.email.required"
                class="invalid-feedback"
              >Email is required</div>
              <div
                v-if="submitted && !$v.user.email.email"
                class="invalid-feedback"
              >Please enter valid Email Address</div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input
                  type="text"
                  v-model.trim="user.firstName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.firstName.$error }"
                />
                <div
                  v-if="submitted && !$v.user.firstName.required"
                  class="invalid-feedback"
                >First Name is required</div>
                <div
                  v-if="submitted && !$v.user.firstName.alpha"
                  class="invalid-feedback"
                >Enter valid First Name</div>
                <div
                  v-if="submitted && !$v.user.firstName.minLength"
                  class="invalid-feedback"
                >First Name must have at least {{$v.user.firstName.$params.minLength.min}} letters.</div>
                <div
                  v-if="submitted && !$v.user.firstName.maxLength"
                  class="invalid-feedback"
                >First Name must have at most {{$v.user.firstName.$params.maxLength.max}} letters.</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input
                  type="text"
                  v-model.trim="user.lastName"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.lastName.$error }"
                />
                <div
                  v-if="submitted && !$v.user.lastName.required"
                  class="invalid-feedback"
                >Last Name is required</div>
                <div
                  v-if="submitted && !$v.user.lastName.alpha"
                  class="invalid-feedback"
                >Enter valid last Name</div>
                <div
                  v-if="submitted && !$v.user.lastName.minLength"
                  class="invalid-feedback"
                >Last Name must have at least {{$v.user.lastName.$params.minLength.min}} letters.</div>
                <div
                  v-if="submitted && !$v.user.lastName.maxLength"
                  class="invalid-feedback"
                >Last Name must have at most {{$v.user.lastName.$params.maxLength.max}} letters.</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
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
            <div class="col">
              <div class="form-group">
                <label for="Gender">Gender</label>
                <div>
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
                </div>
                <div
                  v-if="submitted && !$v.user.gender.required"
                  class="invalid-feedback"
                  style="display: block"
                >Gender is required</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  v-model.trim="user.password"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.password.$error }"
                />
                <div
                  class="invalid-feedback"
                  v-if="!$v.user.password.required"
                >Password is required.</div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.user.password.minLength"
                >Password must have at least {{ $v.user.password.$params.minLength.min }} letters.</div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.user.password.maxLength"
                >Password must have at most {{ $v.user.password.$params.maxLength.max }} letters.</div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input
                  type="password"
                  v-model.trim="user.confirmPassword"
                  class="form-control"
                  :class="{ 'is-invalid': submitted && $v.user.confirmPassword.$error }"
                />
                <div
                  class="invalid-feedback"
                  v-if="!$v.user.confirmPassword.sameAsPassword"
                >Passwords must be identical.</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col align-self-center">
              <label for="userImage">User Image</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                </div>
                <div class="custom-file">
                  <input
                    type="file"
                    class="custom-file-input form-control"
                    id="userImage"
                    aria-describedby="inputGroupFileAddon01"
                    @change="fileselected"
                    accept=".png, .jpg, .jpeg"
                  />
                  <label class="custom-file-label" for="userImage">{{selectedImage}}</label>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-2 mt-2">
            <div class="col-3">
              <button class="btn btn-primary col-11">Register</button>
            </div>
            <div class="col-3">
              <button type="button" class="btn btn-danger col-11" @click="clear">Clear</button>
            </div>
            <div class="col-3">
              <template v-if="this.$store.getters.userlog == ''">
                <router-link to="/login" tag="button" class="btn btn-danger col-10">
                  <div>Login</div>
                </router-link>
              </template>
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
  alphaNum,
  alpha,
  minLength,
  maxLength,
  email,
  between,
  sameAs
} from "vuelidate/lib/validators";
import axios from "axios";

export default {
  data() {
    return {
      user: {
        userName: "",
        firstName: "",
        lastName: "",
        age: null,
        gender: null,
        email: "",
        password: "",
        confirmPassword: "",
        image: ""
      },
      submitted: false,
      showAlt: false,
      selectedImage: "Choose file",
      altMsg: ""
    };
  },
  validations: {
    user: {
      userName: {
        required,
        alphaNum,
        minLength: minLength(5),
        maxLength: maxLength(15)
      },
      email: {
        required,
        email
      },
      firstName: {
        required,
        alpha,
        minLength: minLength(3),
        maxLength: maxLength(15)
      },
      lastName: {
        required,
        alpha,
        minLength: minLength(5),
        maxLength: maxLength(12)
      },
      age: {
        required,
        between: between(15, 60)
      },
      gender: {
        required
      },
      password: {
        required,
        minLength: minLength(6),
        maxLength: maxLength(15)
      },
      confirmPassword: {
        sameAsPassword: sameAs("password")
      }
    }
  },
  methods: {
    clear() {
      this.submitted = false;

      this.user.userName = "";
      this.user.firstName = "";
      this.user.lastName = "";
      this.user.age = null;
      this.user.gender = null;
      this.user.email = "";
      this.user.password = "";
      this.user.confirmPassword = "";
    },
    submit() {
      this.submitted = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      } else {
        const vm = this;
        axios
          .post("/api/adduser", {
            user: vm.user
          })
          .then(function(response) {
            if (response.data.status == "success") {
              if (localStorage.getItem("userName") == null) {
                vm.$store.dispatch("login", vm.user.userName);

                localStorage.setItem("token", response.data.accessToken);

                vm.$store.dispatch("setPermission", 0);
              }
              vm.$router.push("/home");
            } else if (response.data == "userNameTaken") {
              vm.altMsg = "User Name Already Taken!";
              vm.showAlt = true;
              setTimeout(function() {
                vm.showAlt = false;
              }, 3000);
            } else if (response.data == "emailTaken") {
              vm.altMsg = "Email Address Already Taken!";
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
    fileselected(event) {
      let imageFile = event.target.value.split("\\").pop();
      if (imageFile != "") {
        this.selectedImage = imageFile;
        let fileReader = new FileReader();
        let file = fileReader.readAsDataURL(event.target.files[0]);
        fileReader.onload = e => {
          this.user.image = e.target.result;
        };
      } else {
        this.selectedImage = "Choose file";
      }
    }
  }
};
</script>