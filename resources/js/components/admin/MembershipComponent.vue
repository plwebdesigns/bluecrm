<style scoped>

select {
    border-width: .1em;
}

button {
    height: 27px;
    font-size: 10px;
}

</style>

<template>

<div class="container increased-width">
    <h1 class="font-fredricka">Membership Dues</h1>
    <table class="table table-sm">
        <thead class="bg-blue-realty">
            <tr>
                <th>NAME</th>
                <th>JAN</th>
                <th>FEB</th>
                <th>MAR</th>
                <th>APR</th>
                <th>MAY</th>
                <th>JUN</th>
                <th>JULY</th>
                <th>AUG</th>
                <th>SEP</th>
                <th>OCT</th>
                <th>NOV</th>
                <th>DEC</th>
                <th>BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(agent, index) in agents" :key="index">
                <th class="table-primary">
                    <input :id="'radio-' + index" name="radioInput" class="form-check-input" type="radio" v-on:input="selectionChange(index)" />
                    <label class="form-check-label" :for="'radio-' + index">{{agent.agent_name}}</label>
                </th>
                <td>
                    <i :id="'i1-' + index" v-bind:class="{'far fa-check-circle text-success': agent.jan, 'fas fa-times text-danger': !agent.jan}"></i>
                    <input :id="'1-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.jan">
                </td>
                <td>
                    <i :id="'i2-' + index" v-bind:class="{'far fa-check-circle text-success': agent.feb, 'fas fa-times text-danger': !agent.feb}"></i>
                    <input :id="'2-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.feb">
                </td>
                <td>
                    <i :id="'i3-' + index" v-bind:class="{'far fa-check-circle text-success': agent.mar, 'fas fa-times text-danger': !agent.mar}"></i>
                    <input :id="'3-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.mar">
                </td>
                <td>
                    <i :id="'i4-' + index" v-bind:class="{'far fa-check-circle text-success': agent.apr, 'fas fa-times text-danger': !agent.apr}"></i>
                    <input :id="'4-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.apr">
                </td>
                <td>
                    <i :id="'i5-' + index" v-bind:class="{'far fa-check-circle text-success': agent.may, 'fas fa-times text-danger': !agent.may}"></i>
                    <input :id="'5-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.may">
                </td>
                <td>
                    <i :id="'i6-' + index" v-bind:class="{'far fa-check-circle text-success': agent.jun, 'fas fa-times text-danger': !agent.jun}"></i>
                    <input :id="'6-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.jun">
                </td>
                <td>
                    <i :id="'i7-' + index" v-bind:class="{'far fa-check-circle text-success': agent.july, 'fas fa-times text-danger': !agent.july}"></i>
                    <input :id="'7-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.july">
                </td>
                <td>
                    <i :id="'i8-' + index" v-bind:class="{'far fa-check-circle text-success': agent.aug, 'fas fa-times text-danger': !agent.aug}"></i>
                    <input :id="'8-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.aug">
                </td>
                <td>
                    <i :id="'i9-' + index" v-bind:class="{'far fa-check-circle text-success': agent.sep, 'fas fa-times text-danger': !agent.sep}"></i>
                    <input :id="'9-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.sep">
                </td>
                <td>
                    <i :id="'i10-' + index" v-bind:class="{'far fa-check-circle text-success': agent.oct, 'fas fa-times text-danger': !agent.oct}"></i>
                    <input :id="'10-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.oct">
                </td>
                <td>
                    <i :id="'i11-' + index" v-bind:class="{'far fa-check-circle text-success': agent.nov, 'fas fa-times text-danger': !agent.nov}"></i>
                    <input :id="'11-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.nov">
                </td>
                <td>
                    <i :id="'i12-' + index" v-bind:class="{'far fa-check-circle text-success': agent.dec, 'fas fa-times text-danger': !agent.dec}"></i>
                    <input :id="'12-' + index" hidden type="checkbox" class="custom-checkbox" v-model="agent.dec">
                </td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            ${{agent.bal}}
                        </div>
                        <div class="col-1">
                            <button @click="saveRow(index)" :id="'btn-' + index" type="button" class="btn btn-sm btn-danger" hidden>
                                Save
                            </button>
                            <span :id="'spinner-' + index" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                        </div>
                    </div>

                </td>
            </tr>
        </tbody>
    </table>
    <message-modal></message-modal>
</div>

</template>

<script>

export default {
    name: "MembershipComponent",
    mounted() {
        this.getMembershipData();
    },
    data() {
        return {
            agents: {},

        }
    },
    methods: {
        getMembershipData: function() {
            let token = this.getCookie('token');

            $.ajax({
                url: '/api/membership_details',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + token
                }
            }).done(resp => {
                this.agents = resp.agents;
            })
        },
        selectionChange: function(i) {
            $('button').attr('hidden', true);
            $('input[type=checkbox]').attr('hidden', true);
            $('i').attr('hidden', false);
            $('button#btn-' + i).attr('hidden', false);
            for (let j = 1; j < 13; j++) {
                $('input[type=checkbox]#' + j + '-' + i).attr('hidden', false);
                $('#i' + j + '-' + i).attr('hidden', true);
            }
        },
        saveRow: function(i) {
            $('button').attr('hidden', true);
            $('span#spinner-' + i).attr('hidden', false);
            let token = this.getCookie('token');
            $.ajax({
                url: '/api/membership_details',
                type: 'post',
                headers: {
                    Authorization: 'Bearer ' + token
                },
                data: {
                    agent_membership: this.agents[i]
                }

            }).done(resp => {
                this.getMembershipData();
                this.$modal.show('messageModal', {
                    data: resp.msg,
                    style: {
                        class: 'text-info'
                    }
                });
                $('input[type=checkbox]').attr('hidden', true);
                $('i').attr('hidden', false);
                $('#radio-' + i).prop('checked', false);
                $('button').attr('hidden', true);
                $('#spinner-' + i).attr('hidden', true);
            });
        }
    },
}

</script>
