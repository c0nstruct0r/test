<template>

    <div class="panel" style="visibility: visible; margin-top:80px">
        <div class="panel-heading">
            <h3 class="box-title">Операции</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body">
            <div class="col-md-12 form-group">
                <div class="col-md-2">
                    <label for="isLinked">Привязаны</label>
                    <select class="form-control" id="isLinked" name="isLinked" v-on:change="queryOnChange">
                        <option :selected="selectedParam('isLinked', value)" :value="value.value"
                                v-for="value in isLinked">
                            {{value.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type">Тип операции</label>
                    <select class="form-control" id="type" name="type" v-on:change="queryOnChange">
                        <option :selected="selectedParam('type', value)" :value="value.value" v-for="value in types">
                            {{value.name}}
                        </option>
                    </select>
                </div>
            </div>

            <div class="dataTables_wrapper form-inline dt-bootstrap" id="pays_wrapper">
                <div class="row">
                    <div class="ui basic segment" id="content">
                        <v-server-table :columns="columns" :options="options"
                                        :sort-order="sortOrder"
                                        ref="table"
                                        url="/api/pays">
                            <template scope="props" slot="id">
                                <a @click="delete(props.row.id)" href="">
                                    <span class="fa fa-trash"></span>
                                </a>&nbsp;
                                {{props.row.id}}
                            </template>
                            <template scope="props" slot="op_date_processed">
                                {{props.row.op_date_processed}}
                            </template>
                            <template scope="props" slot="cost">{{props.row.cost}}</template>
                            <template scope="props" slot="payer">{{props.row.payer}}</template>
                            <template scope="props" slot="opLink">{{props.row.opLink}}</template>
                            <template scope="props" slot="orderId">{{props.row.orderId}}</template>
                            <template scope="props" slot="line">
                                <small>{{props.row.comment}}</small><br>
                                <small>{{props.row.line}}</small>
                            </template>
                        </v-server-table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

</template>
<script>
    import {spinner} from "./mixins/spinner";
    import Vue from 'vue';
    import {ServerTable} from 'vue-tables-2';
    import {query} from "./mixins/query";
    import {payStatus} from "./mixins/payStatusesDict";
    import VueMeta from "vue-meta";

    Vue.use(ServerTable);
    Vue.use(VueMeta, {
        refreshOnceOnNavigation: true
    })

    export default {
        mixins: [spinner, query, payStatus],
        components: {VueMeta},
        metaInfo: {
            title: ' | sushi.bz',
            titleTemplate: 'Платежи %s',
            htmlAttrs: {
                lang: 'ru',
                amp: true
            }
        },

        data() {
            return {
                isLinked: [
                    {name: '', value: null},
                    {name: 'привязаны', value: true},
                    {name: 'не привязаны', value: false}
                ],
                types: [
                    {name: '', value: null},
                    {name: 'пополнение', value: true},
                    {name: 'списание', value: false}
                ],
                sortOrder: [
                    {
                        field: 'id',
                        direction: 'desc'
                    }
                ],
                filterBySms: false,
                tableData: [],
                columns: [
                    'id',
                    'opLink',
                    'orderId',
                    'op_date_processed',
                    'cost',
                    'payer',
                    'line',
                ],
                options: {
                    customFilters: ['isLinked', 'type'],
                    initFilters: {
                        isLinked: this.$router.currentRoute.query.isLinked,
                        type: this.$router.currentRoute.query.type,
                    },
                    headings: {
                        id: '',
                        opLink: 'Связь',
                        orderId: '',
                        cost: 'Сумма',
                        payer: 'Плательщик',
                        op_date_processed: 'Дата',
                    },
                    sortable: [
                        'id',
                        'cost',
                        'opLink',
                        'orderId',
                        'op_date_processed',
                        'line',
                    ],
                    serverMultiSorting: true,
                    multiSorting: {
                        name: [
                            {column: 'op_date_processed', matchDir: true},
                            {column: 'cost', matchDir: true}
                        ],
                    },
                    filterable: ['op_date_processed', 'cost'],
                    perPage: 50,
                    perPageValues: [25, 50, 100, 200, 500, 1000, 10000],
                    responseAdapter({data}) {
                        return {
                            data: data.data,
                            count: data.count
                        }
                    },
                    templates: {
                        created_at(h, row) {
                            return this.formatDate(row.created_at);
                        },
                        updated_at(h, row) {
                            return this.formatDate(row.updated_at);
                        },
                        pushed_at(h, row) {
                            return this.formatDate(row.pushed_at);
                        }
                    }
                },

                reverse: false,
                sortKey: 'id',
                sortKeys: {
                    id: 'num',
                    cost: 'num',
                },
                items: [],
                item: {
                    id: 0,
                    cost: 0,
                },
                queryParamNames: ['op_date_processed'],
                queryParams: [],
            }
        },
        methods: {
            delete(id) {
                e.preventDefault();
                return axios.delete('/api/pay/' + id)
                    .catch(function (e) {
                        this.dispatch('error', e);
                    }.bind(this));
            }
        },
    }
</script>