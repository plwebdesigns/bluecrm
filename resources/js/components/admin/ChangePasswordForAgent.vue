<template>
    <div class="mt-5">
        <div class="card">
            <form class="card-body">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" placeholder="Email" v-model="email" />
                    <small class="form-text text-muted">Enter email for Agent whose password you need to change</small>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" class="form-control" placeholder="New Password" v-model="password" />
                </div>
                <div class="form-group">
                    <span id="spinner" class="spinner-border spinner-border-sm" role="status" hidden></span>
                    <button id="subBtn" type="button" class="btn btn-sm btn-primary" @click="changePassword">Submit</button>
                </div>
                <div class="alert alert-success" role="alert" v-if="message === 'Successfully changed'">
                    {{ message }}!!
                </div>
                <div class="alert alert-danger" role="alert" v-for="e in errors">
                    <div v-for="x in e">{{ x }}</div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "ChangePasswordForAgent",
    data() {
        return {
            email: '',
            password: '',
            message: '',
            errors: ''
        }
    },
    methods: {
        changePassword: function () {
            let el = document.getElementById("subBtn");
            el.setAttribute('disabled', '');
            let spinner = document.getElementById("spinner");
            spinner.removeAttribute('hidden');
            let token = this.getCookie("token");
            this.errors = '';
            this.message = '';
            axios({
                method: 'post',
                url: 'api/admin/changeAgentPassword',
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + token
                },
                data: {
                    email: this.email,
                    password: this.password
                }
            }).then(resp => {
                if (resp.data.errors) {
                    this.errors = resp.data.errors;
                }
                else {
                    this.message = resp.data.message;
                }
                spinner.setAttribute('hidden', '');
                el.removeAttribute('disabled');
            });
        }
    }
}
</script>

<style scoped>
.card {
    width: 25rem;
    margin: 0 auto;
}
</style>
