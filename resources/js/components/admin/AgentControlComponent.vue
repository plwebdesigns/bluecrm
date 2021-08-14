<template>
    <div class="container increased-width">
        <div class="row">
            <div class="col-9">
                <h1 class="font-fredricka text-center">Current Agents</h1>
                <div v-if="message !== ''" class="col-3 alert alert-success">
                    {{ message }}
                    <button class="btn btn-sm btn-outline-dark ml-1" type="button" @click="message = ''">
                        Close
                    </button>
                </div>
                <div v-if="error !== ''" class="col-3 alert alert-danger">
                    {{ error }}
                    <button class="btn btn-sm btn-outline-danger ml-1" type="button" @click="error = ''">
                        Close
                    </button>
                </div>
                <table class="table table-sm table-borderless">
                    <thead>
                    <tr class="bg-blue-realty text-white">
                        <th>ID</th>
                        <th>NAME</th>
                        <th>TITLE</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th>SPLIT</th>
                        <th>
                            MEMBERSHIP
                        </th>
                        <th>BIRTHDAY</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(agent, index) in formattedAgents" :key="index">
                        <td>
                            <input
                                class="form-check-input"
                                type="radio" name="exampleRadios"
                                :id="'row-' + index" v-on:input="radioSelected($event)" />
                            <label class="form-check p-0">{{agent.id}}</label>
                        </td>
                        <td>
                            <input
                                :id="'agent_name-' + index"
                                class="form-control form-control-sm"
                                type="text"
                                v-model="agent.agent_name" readonly />
                        </td>
                        <td>
                            <select
                                class="custom-select custom-select-sm"
                                v-model="agent.title"
                                :id="'title-' + index"
                                disabled>
                                <option selected>{{agent.title}}</option>
                                <option v-for="(item, index) in titles" :key="index">{{item.title}}</option>
                            </select>
                        </td>
                        <td>
                            <input
                                :id="'email-' + index"
                                class="form-control form-control-sm"
                                type="text"
                                v-model="agent.email" readonly />
                        </td>
                        <td>
                            <input
                                :id="'phone-' + index"
                                class="form-control form-control-sm"
                                type="text"
                                v-model="agent.phone" readonly />
                        </td>
                        <td>
                            <div class="input-group">
                                <input
                                    :id="'current_split-' + index"
                                    class="form-control form-control-sm"
                                    type="text"
                                    style="max-width: 60px"
                                    v-model="agent.current_split" readonly />
                                <div class="input-group-append" style="max-height: 25px">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend" style="max-height: 25px">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input class="form-control form-control-sm"
                                        :id="'membership-' + index"
                                        type="text"
                                        style="max-width: 80px"
                                        v-model="agent.membership_fee"
                                        readonly
                                        />

                            </div>
                        </td>
                        <td>
                            <input
                                :id="'dob-' + index"
                                class="form-control form-control-sm"
                                type="text"
                                v-on:input="bdayField($event, index)"
                                placeholder="MM/DD"
                                v-model="agent.dob" readonly />
                        </td>
                        <td class="pr-0">
                            <span :id="'spinner-' + index" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                            <button
                                type="button"
                                :id="'savebtn-' + index"
                                class="btn btn-sm btn-primary"
                                v-on:click="save(index)"
                                hidden>Save</button>
                        </td>
                        <td>
                            <button
                                type="button"
                                :id="'deletebtn-' + index"
                                class="btn btn-sm btn-danger"
                                @click="deleteAgent(index)"
                                hidden>Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AgentControlComponent",
        data(){
            return {
                agents: {},
                titles: [],
                message: '',
                error: ''
            }
        },
        mounted() {
            this.getAgentTitles();
            this.getAgents();
        },
        methods:{
            getAgentTitles: function(){
              let token = this.getCookie('token');

              $.ajax({
                  type: 'get',
                  url: '/api/agent_titles',
                  headers: {
                      Authorization: 'Bearer ' + token
                  }
              }).done(resp => {
                  this.titles = resp.agent_titles;
              })
            },
            getAgents: function () {
                let token = this.getCookie('token');

                $.ajax({
                    type: 'GET',
                    url: '/api/agent_control',
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                }).done(resp => {
                    this.agents = resp.agents;
                });
            },
            radioSelected: function (event) {
                $('input[type!=radio]').attr('readonly', true);
                $('select').attr('disabled', true);
                $('button').attr('hidden', true);
                let index = event.target.id.substr(4);
                $('input#agent_name-' + index).attr('readonly', false);
                $('input#phone-' + index).attr('readonly', false);
                $('input#email-' + index).attr('readonly', false);
                $('select#title-' + index).attr('disabled', false);
                $('input#current_split-' + index).attr('readonly', false);
                $('input#membership-' + index).attr('readonly', false);
                $('input#dob-' + index).attr('readonly', false);
                $('button#savebtn-' + index).attr('hidden', false);
                $('button#deletebtn-' + index).attr('hidden', false);
            },
            save: function (i) {
                $('button#savebtn-' + i).attr('hidden', true);
                $('button#deletebtn-' + i).attr('hidden', true);
                $('span#spinner-' + i).attr('hidden', false);
                let agent = this.agents[i];
                let token = this.getCookie('token');
                this.message = '';
                this.error = '';

                $.ajax({
                    type: 'post',
                    url: '/api/update_agent',
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    data: {
                        agent: agent
                    }
                }).done(resp => {
                    if (typeof resp.msg !== 'undefined') {
                        this.message = resp.msg;
                    }
                    if (typeof resp.err !== 'undefined') {
                        this.error = resp.err;
                    }
                    $('button#savebtn-' + i).attr('hidden', false);
                    $('button#deletebtn-' + i).attr('hidden', false);
                    $('span#spinner-' + i).attr('hidden', true);
                }).fail(err => {
                    this.errors = err.responseJSON.errors;
                    $('button#savebtn-' + i).attr('hidden', false);
                    $('button#deletebtn-' + i).attr('hidden', false);
                    $('span#spinner-' + i).attr('hidden', true);
                })
            },
            deleteAgent: function (i) {
                let token = this.getCookie('token');
                $('button#savebtn-' + i).attr('hidden', true);
                $('button#deletebtn-' + i).attr('hidden', true);
                $('span#spinner-' + i).attr('hidden', false);

                $.ajax({
                    type: 'post',
                    url: '/api/delete_agent',
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    data: {
                        id: this.agents[i].id
                    }
                }).done(resp => {
                    alert(resp.msg);
                    $('button#savebtn-' + i).attr('hidden', false);
                    $('button#deletebtn-' + i).attr('hidden', false);
                    $('span#spinner-' + i).attr('hidden', true);
                    this.getAgents();
                })
            },
            bdayField: function (event, i) {
                if (event.inputType !== "deleteContentBackward"){
                    let x = event.target;
                    this.errors = [];
                    if (x.value.length === 2){
                        this.agents[i].dob = x.value + '/';
                    }
                    else if (x.value.length > 5){
                        this.errors.push("Just the month and day mm/dd");
                        $('#errorRow').attr('hidden', false);
                        this.agents[i].dob = this.agents[i].dob.substr(0, 5);
                    }
                }
                else if (event.inputType === "deleteContentBackward" && event.target.value.length === 2){
                    this.agents[i].dob = this.agents[i].dob.substr(0, 1);
                }
            }
        },
        computed: {
            formattedAgents: function () {
                let agents = [];

                for (const key in this.agents) {
                    agents.push(this.agents[key]);
                }

                agents.sort((a,b) => {
                    if (a.agent_name < b.agent_name){
                        return -1;
                    }
                    if (a.agent_name > b.agent_name){
                        return 1;
                    }
                    return 0;
                })

                return agents;
            },
        }
    }
</script>

<style scoped>
input {
    height: 25px;
}
select {
    font-size: 11px;
}
button{
    height: 25px;
    font-size: 9px;
}
</style>
