<template>
<div>
    <nav-component v-bind:isAdmin="agent.isAdmin"></nav-component>
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="card" style="width: 18rem;">
                    <profile-component :curAgent="agent"></profile-component>
                </div>
            </div>
            <div class="col">
                <form>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>Name:</th>
                            <td>{{agent.agent_name}}</td>
                        </tr>
                        <tr>
                            <th>Agent ID:</th>
                            <td>{{agent.id}}</td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td>{{agent.title}}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><input
                                type="text"
                                id="phone"
                                class="form-control form-control-sm"
                                v-model="agent.phone"
                                v-on:input="numOnly"
                                disabled /></td>
                            <td>
                                <div class="text-danger" id="numError"></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><input
                                id="email"
                                type="text"
                                class="form-control form-control-sm"
                                v-model="agent.email"
                                disabled /></td>
                        </tr>
                        <tr>
                            <th>Birthday: <i
                                class="far fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="The year will not be saved, only the month/day"
                            ></i></th>
                            <td>
                                <input
                                    class="form-control form-control-sm"
                                    id="dob"
                                    type="text"
                                    v-model="agent.dob"
                                    v-on:input="bdayField($event)"
                                    disabled />
                            </td>
                        </tr>
                        <tr>
                            <th>Current Split:</th>
                            <td>{{agent.current_split}}%</td>
                        </tr>
                        <tr>
                            <th>Current Quarter Rank:</th>
                            <td>{{quarter_rank}}</td>
                        </tr>
                        <tr>
                            <th>Current YTD Rank:</th>
                            <td>{{ytd_rank}}</td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td>
                                <input
                                    class="form-control form-control-sm"
                                    type="text"
                                    :value="agent.email"
                                    disabled
                                    readonly />
                            </td>
                        </tr>
                        <tr>
                            <th>Password:</th>
                            <td>
                                <input
                                    class="form-control-sm form-control"
                                    type="password"
                                    disabled
                                    placeholder="********"
                                    v-model="agent.password"/>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button
                                    id="editbtn"
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    v-on:click="editFields">Edit</button>
                                <button
                                    id="savebtn"
                                    type="button"
                                    class="btn btn-success btn-sm"
                                    hidden
                                    v-on:click="save">Save</button>
                                <button
                                    id="cancelbtn"
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    hidden
                                    v-on:click="cancel">Cancel</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <div id="errorRow" hidden>
                    <p class="text-danger" v-for="(item, index) in errors" :key="index">{{item}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import ProfileComponent from "./admin/ProfileComponent";

    export default {
        components:{
            ProfileComponent,
        },
        name: "AgentProfileComponent",
        beforeCreate() {
            this.$loading(true);
        },
        mounted() {
            this.getAgent();
        },
        data(){
            return{
                agent:{
                    id: '',
                    agent_name: '',
                    title: '',
                    phone: '',
                    email: '',
                    dob: '',
                    current_split: '',
                    password: null,
                    isAdmin: ''
                },
                ytd_rank: '',
                quarter_rank: '',
                errors: []
            }
        },
        methods: {
            getAgent: function () {
                let token = this.getCookie('token');

                $.ajax({
                    type: 'GET',
                    url: '/api/agent_profile',
                    headers:{
                        Authorization: 'Bearer ' + token
                    }
                }).done(resp => {
                    this.agent = resp.agent;
                    this.ytd_rank = resp.ytd_rank;
                    this.quarter_rank = resp.cur_quarter_rank;
                    this.$loading(false);
                })
            },
            editFields: function () {
                this.errors = [];
                this.agent.password = '';
                $('input').attr('disabled', false);

                // Remove dashes from phone number for editing
                this.agent.phone = this.agent.phone.replace(/-/g, '');

                $('#editbtn').attr('hidden', true);
                $('#savebtn').attr('hidden', false);
                $('#cancelbtn').attr('hidden', false);
            },
            save: function () {
                this.$loading(true);
                $('input').attr('disabled', true);
                $('#editbtn').attr('hidden', false);
                $('#savebtn').attr('hidden', true);
                $('#cancelbtn').attr('hidden', true);
                $.ajax({
                    type: 'POST',
                    url: '/api/update_profile',
                    headers: {
                        Authorization: 'Bearer ' + this.getCookie('token')
                    },
                    data: {
                        agent: this.agent
                    }
                }).done(resp => {
                    this.$loading(false);
                    alert(resp.msg);
                }).fail(err => {
                    this.$loading(false);
                    this.errors = err.responseJSON.errors;
                    $('#errorRow').attr('hidden', false);
                })
            },
            numOnly: function(){
                $('div#numError').text('');
                if ($('#phone').val().match(/[^0-9]/g)){
                    $('div#numError').text('Numbers only');
                }
            },
            cancel: function () {
                $('input').attr('disabled', true);
                let tmp = this.agent.phone;
                tmp = tmp.substr(0, 3) + '-' +
                    tmp.substr(3, 3) + '-' +
                    tmp.substr(6);
                this.agent.phone = tmp;
                $('input[type=password]').attr('placeholder', '********');
                $('#editbtn').attr('hidden', false);
                $('#savebtn').attr('hidden', true);
                $('#cancelbtn').attr('hidden', true);
            },
            bdayField: function (event) {
                if (event.inputType !== "deleteContentBackward"){
                    let x = event.target;
                    this.errors = [];
                    if (x.value.length === 2){
                        this.agent.dob = x.value + '/';
                    }
                    else if (x.value.length > 5){
                        this.errors.push("Just the month and day mm/dd");
                        $('#errorRow').attr('hidden', false);
                        this.agent.dob = this.agent.dob.substr(0, 5);
                    }
                }
                else if (event.inputType === "deleteContentBackward" && event.target.value.length === 2){
                    this.agent.dob = this.agent.dob.substr(0, 1);
                }
            }
        },
    }
</script>

<style scoped>
th{
    width: 200px;
}
    input{
        max-width: 250px;
    }
</style>
