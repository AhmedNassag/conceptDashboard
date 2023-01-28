<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.orderOnline') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.orderOnline') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getOrder" class="needs-validation">
                                            <div class="form-group row align-items-center justify-content-around">

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="fromDate">
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="toDate">
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label>{{ $t('global.orderStatus') }}</label>
                                                    <select v-model="status" class="form-control date-input">
                                                        <option value="">{{$t('global.allStatus')}}</option>
                                                        <option value="1">{{$t('global.New order')}}</option>
                                                        <option value="2">{{$t('global.Order confirmed')}}</option>
                                                        <option value="3">{{$t('global.In the processing stage')}}</option>
                                                        <option value="4">{{$t('global.The request came out with the delegate')}}</option>
                                                        <option value="8">{{$t('global.hanging')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.sellInvoiceNumber')}}</label>
                                                    <input type="number" class="form-control date-input" v-model="order_id">

                                                </div>

                                                <div class="col-md-2 mt-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-md-2 row justify-content-end">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{$t('global.InvoiceNumber')}}</th>
                                        <th>{{ $t('sidebar.client') }}</th>
                                        <th>{{ $t('global.Store') }}</th>
                                        <th>{{ $t('global.TotalPrice') }}</th>
                                        <th>{{ $t('global.Date') }}</th>
                                        <th>{{ $t('global.orderStatus') }}</th>
                                        <th>{{ $t('global.representative') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="orders.length">
                                    <tr v-for="(item,index) in orders"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td>{{ item.store.name }}</td>
                                        <td>{{ item.total }}</td>
                                        <td>{{  dateFormat(item.created_at) }}</td>
                                        <td>{{ item.order_status.name }}</td>
                                        <td>{{ item.representative ?item.representative.name: '---' }}</td>

                                        <td>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                               :data-bs-target="'#edit-category'+item.id" :title="$t('global.Print')">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            <router-link
                                                :to="{name: 'showOrderOnline',params:{id:item.id}}"
                                                v-if="permission.includes('orderOnline read')"
                                                class="btn btn-sm btn-info me-2" :title="$t('global.Show')">
                                                <i class="fas fa-book-open"></i>
                                            </router-link>

                                            <router-link
                                                :to="{name: 'editOrderOnline', params: {id:item.id}}"
                                                v-if="permission.includes('orderOnline edit') && parseInt(item.order_status_id) == 1"
                                                class="btn btn-sm btn-success me-2" :title="$t('global.Edit')">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <a href="#" @click="deleteOrder(item.id,index)"
                                               v-if="permission.includes('orderOnline delete') && parseInt(item.order_status_id) == 1"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2" :title="$t('global.Delete')">
                                                <i class="far fa-trash-alt"></i>
                                            </a>

                                            <a href="javascript:void(0);"
                                               data-bs-toggle="modal"
                                               :data-bs-target="'#edit-status-'+item.id"
                                               @click="dataChangeOrder(item.id)"
                                                class="btn btn-sm btn-warning me-2"
                                                :title="$t('global.changeOrderStatus')"
                                            >
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>

                                        </td>

                                        <!-- Edit Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-status-'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.changeOrderStatus') }}</h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
                                                                <div class="tab-content pt-0">
                                                                    <div role="tabpanel" class="tab-pane fade active show">
                                                                        <loader v-if="loading"/>
                                                                        <div class="alert alert-danger text-center" v-if="errors['order_status_id']">{{ errors['order_status_id'][0] }}<br /> </div>
                                                                        <div class="alert alert-danger text-center" v-if="errors['order_id']">{{ errors['order_id'][0] }}<br /> </div>
                                                                        <div class="alert alert-danger text-center" v-if="errors['type_invoice']">{{ errors['type_invoice'][0] }}<br /> </div>
                                                                        <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                                                        <div class="alert alert-danger text-center" v-if="errors['treasury_amount']">{{ errors['treasury_amount'][0] }}<br /> </div>
                                                                        <div class="alert alert-danger text-center" v-if="errors['sender_amount']">{{ errors['sender_amount'][0] }}<br /> </div>
                                                                        <div class="table-responsive">
                                                                            <form @submit.prevent="storeChangeOrder" class="needs-validation">

                                                                                <div class="form-row row">
                                                                                    <div class="col-md-4 mb-3">
                                                                                        <label>{{$t('global.orderStatus')}}</label>
                                                                                        <select @change="dataOrderReturn" v-model="data.order_status_id" class="form-select" :class="{'is-invalid':v$.order_status_id.$error,'is-valid':!v$.order_status_id.$invalid}">
                                                                                            <option v-for="orderSt in orderStatus" :kay="orderSt.id" :value="orderSt.id">{{orderSt.name}}</option>
                                                                                        </select>

                                                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                        <div class="invalid-feedback">
                                                                                            <span v-if="v$.order_status_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-4 mb-3" v-if="parseInt(data.order_status_id) == 4">
                                                                                        <label>{{$t('global.choseRepresentative')}}</label>
                                                                                        <select v-model="data.representative_id" class="form-select" :class="{'is-valid':!v$.representative_id.$invalid}">
                                                                                            <option v-for="representative in representatives" :kay="representative.id" :value="representative.id">{{representative.name}}</option>
                                                                                        </select>
                                                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                    </div>

                                                                                    <div class="form-row row" v-for="(it,index) in data.products" v-if="parseInt(data.order_status_id) == 7">

                                                                                        <div class="col-md-12 mb-1">
                                                                                            <label>{{$t('global.productName')}} :
                                                                                                {{ data.products[index].product_name }} </label>
                                                                                        </div>

                                                                                        <div class="col-md-3 mb-3" v-if="data.products[index].quantity">
                                                                                            <label>{{$t('global.RequiredQuantity')}} {{$t('global.full')}}</label>
                                                                                            <input type="number" class="form-control" @input="changeQuantity(index)"
                                                                                                   v-model.number="v$.products[index].quantity.$model"
                                                                                                   :placeholder="$t('global.RequiredQuantity')"
                                                                                                   :class="{'is-invalid':v$.products[index].quantity.$error,'is-valid':!v$.products[index].quantity.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.products[index].quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.products[index].quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-3 mb-3" v-if="data.products[index].quantity">
                                                                                            <label>{{$t('global.quantityReturn')}} {{$t('global.full')}}</label>
                                                                                            <input type="number" class="form-control" @input="changeReturnQuantity(index)"
                                                                                                   v-model.number="v$.products[index].return_quantity.$model"
                                                                                                   :placeholder="$t('global.quantityReturn') + $t('global.full')"
                                                                                                   :class="{'is-invalid':v$.products[index].return_quantity.$error,'is-valid':!v$.products[index].return_quantity.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.products[index].return_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.products[index].return_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="col-md-3 mb-3" v-if="data.products[index].sub_quantity">
                                                                                            <label>{{$t('global.RequiredQuantity')}} {{$t('global.partial')}}</label>
                                                                                            <input type="number" class="form-control" @input="changeSubQuantity(index)"
                                                                                                   v-model.number="v$.products[index].sub_quantity.$model"
                                                                                                   :placeholder="$t('global.RequiredQuantity')"
                                                                                                   :class="{'is-invalid':v$.products[index].sub_quantity.$error,'is-valid':!v$.products[index].sub_quantity.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.products[index].sub_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.products[index].sub_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-3 mb-3" v-if="data.products[index].sub_quantity">
                                                                                            <label>{{$t('global.quantityReturn')}} {{$t('global.partial')}}</label>
                                                                                            <input type="number" class="form-control" @input="changeReturnSubQuantity(index)"
                                                                                                   v-model.number="v$.products[index].return_sub_quantity.$model"
                                                                                                   :placeholder="$t('global.quantityReturn') + $t('global.partial')"
                                                                                                   :class="{'is-invalid':v$.products[index].return_sub_quantity.$error,'is-valid':!v$.products[index].return_sub_quantity.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.products[index].return_sub_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.products[index].return_sub_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-12">
                                                                                            <hr>
                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="form-row row" v-if="parseInt(data.order_status_id) == 5 || parseInt(data.order_status_id) == 7">

                                                                                        <div class="col-md-12 mb-5 text-center">
                                                                                            <h4 class="text-decoration-underline">{{$t('global.receiveCash')}}</h4>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label>{{ $t('global.CashReceiptMethod') }}</label>

                                                                                            <select v-model="data.type_invoice" :class="['form-select',{'is-invalid':v$.type_invoice.$error,'is-valid':!v$.type_invoice.$invalid}]">
                                                                                                <option value="0">{{$t('global.AddToClientAccount')}}</option>
                                                                                                <option value="1">{{$t('global.receiptInTheSafe')}}</option>
                                                                                                <option value="2">{{$t('global.ReceiptInTheSafeAndTheClientAccount')}}</option>
                                                                                            </select>
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.type_invoice.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 1 || data.type_invoice == 2">
                                                                                            <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Balance')}} : {{parseFloat(Math.round(treasury_amount))}}</span> </label>
                                                                                            <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                                                                <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                                                            </select>

                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 1 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.AddedToTheTreasury')}}</label>
                                                                                            <input type="number" class="form-control"
                                                                                                   step="0.01"
                                                                                                   v-model.number="v$.treasury_amount.$model"
                                                                                                   :disabled="data.type_invoice == 1"
                                                                                                   :max="item.total_price"
                                                                                                   :class="{'is-invalid':v$.treasury_amount.$error,'is-valid':!v$.treasury_amount.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.treasury_amount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.treasury_amount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 0 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.client')}}</label>
                                                                                            <input type="text" class="form-control"
                                                                                                   v-model="data.name_supplier" disabled>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 0 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.AddedToTheClient')}}</label>
                                                                                            <input type="number" class="form-control"
                                                                                                   step="0.01"
                                                                                                   v-model.number="v$.sender_amount.$model"
                                                                                                   :disabled="data.type_invoice == 0"
                                                                                                   :max="item.total_price"
                                                                                                   :class="{'is-invalid':v$.sender_amount.$error,'is-valid':!v$.sender_amount.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.sender_amount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.sender_amount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Edit Modal -->

                                        <!-- invoice big Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.InvoiceDetails') }}</h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
                                                                <div class="tab-content pt-0">
                                                                    <div role="tabpanel" :id="'tab-4-'+item.id" class="tab-pane fade active show">
                                                                        <loader v-if="loading"/>
                                                                        <div class="row justify-content-between">
                                                                            <div class="col-5">
                                                                                <button @click="printData(item.id)" class="btn btn-secondary print-button head-button">
                                                                                    {{$t('global.Print')}}
                                                                                    <i class="fa fa-print"></i>
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-4 d-flex w-25 justify-content-end">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive" :id="'printData-'+item.id">
                                                                            <div class="head-data row">

                                                                                <div class="col-md-6 invoice-head">
                                                                                    <h5>{{$t('global.InvoiceNumber')}} : {{item.id}}</h5>
                                                                                </div>

                                                                                <div class="col-md-6 image-div">
                                                                                    <img src="/web/img/logo.png" alt="Logo">
                                                                                </div>


                                                                                <div class="col-md-6">
                                                                                    <p>{{$t('global.DateOrder')}} : {{dateFormat(item.created_at)}}</p>
                                                                                    <p>{{$t('global.store')}} : {{ item.store.name }}</p>
                                                                                    <p>
                                                                                        {{$t('global.TotalPriceBeforeDiscount')}} :
                                                                                        {{item.sub_total }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                    <p>{{$t('global.discountValue')}} : {{ item.discount }} {{ item.currency }}</p>

                                                                                    <p v-if="item.order_other_offer">
                                                                                        {{$t('global.otherDiscount')}} :
                                                                                        {{ item.order_other_offer.offer }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <p>
                                                                                        {{$t('global.TotalPriceAfterDiscount')}} :
                                                                                        {{
                                                                                            item.order_other_offer?
                                                                                                parseFloat( item.sub_total - item.order_other_offer.offer - item.discount).toFixed(2) :
                                                                                                parseFloat( item.sub_total - item.discount).toFixed(2)
                                                                                        }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                    <p v-if="item.tax">{{$t('global.taxValue')}} : {{ item.tax }} {{ item.currency }}</p>
                                                                                    <p v-if="item.tax">{{$t('global.TotalPriceAfterTax')}} : {{ parseFloat(item.total- item.shippingPrice) }} {{ item.currency }}</p>
                                                                                    <p v-if="item.shippingPrice">{{$t('global.shipping')}} : {{ item.shippingPrice }} {{ item.currency }}</p>
                                                                                    <p>{{$t('global.TotalPriceAfterDiscountAndShipping')}} : {{ item.total }} {{ item.currency }}</p>
                                                                                </div>

                                                                            </div>

                                                                            <table class="table table-center table-hover mb-0 datatable">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>{{ $t('global.Products') }}</th>
                                                                                    <th>{{ $t('global.full') }}</th>
                                                                                    <th>{{ $t('global.partial') }}</th>
                                                                                    <th>{{ $t('global.fullPrice') }}</th>
                                                                                    <th>{{ $t('global.partialPrice') }}</th>
                                                                                    <th>{{ $t('global.TotalPrice') }}</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody v-if="item.order_details.length">
                                                                                <tr v-for="(it,index) in item.order_details" :key="it.id">
                                                                                    <td>{{ index +1}}</td>
                                                                                    <td>{{ it.product.name }}</td>
                                                                                    <td>{{ it.quantity ? it.quantity + ' ( '+it.main_measurement_unit.name+' ) ' : '-'}}</td>
                                                                                    <td>{{ it.sub_quantity ? it.sub_quantity + ' ( '+it.sub_measurement_unit.name+' ) ' : '-' }} </td>
                                                                                    <td>{{ it.quantity ? it.price : '-'}}</td>
                                                                                    <td>{{ it.sub_quantity ? it.sub_price : '-'}}</td>
                                                                                    <td>
                                                                                        {{
                                                                                            parseFloat((it.quantity * it.price) + (it.sub_quantity * it.sub_price))
                                                                                                .toFixed(2)
                                                                                        }}
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                                <tbody  v-else>
                                                                                <tr>
                                                                                    <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /invoice big Modal-->

                                    </tr>

                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- start Pagination -->
            <Pagination :limit="2" :data="ordersPaginate" @pagination-change-page="getOrder">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref, computed, reactive, toRefs} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";
import {numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import {notify} from "@kyvg/vue3-notification";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let orders = ref([]);
        let orderStatus = ref([]);
        let representatives = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let order_id = ref('');
        let status = ref('');
        let loading = ref(false);
        const search = ref('');
        let ordersPaginate = ref({});
        let productValidation = ref([]);
        let treasuries = ref([]);
        let treasury_amount = ref(0);
        let order_amount = ref(0);
        let total = ref(0);

        let getOrder = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/orderOnline?page=${page}&search=${search.value}&status=${status.value}&order_id=${order_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    ordersPaginate.value = l.orders;
                    orders.value = l.orders.data;
                    treasuries.value = l.treasuries;
                    treasury_amount.value = l.treasuries[0].amount;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }

        let getOrderStatus = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/orderStatus`)
            .then((res) => {
                let l = res.data.data;
                orderStatus.value = l.orderStatus;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        let getRepresentatives = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeRepresentative`)
                .then((res) => {
                    let l = res.data.data;
                    representatives.value = l.representatives;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getOrder();
            getRepresentatives();
            getOrderStatus();
        });

        let close=(id)=>{
            document.getElementById('close-'+id).click();
        }

        let dateFormat = (item) => {
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        }

        function deleteOrder(id,  index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/orderOnline/${id}`)
                        .then((res) => {
                            orders.value.splice(index,1);

                            Swal.fire({
                                icon: 'success',
                                title: `${t("global.DeletedSuccessfully")}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotDelete')}`,
                            });
                        });
                }
            });
        }

        let printData = (id) => {
            var printContents = document.getElementById('printData-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        let printDataSmall = (id) => {
            var printContents = document.getElementById('printData-small-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getOrder();
            }
        });

        //change status

        let changeStatus =  reactive({
            data:{
                order_status_id:'',
                representative_id:'',
                order_id:'',

                treasury_id:1,
                type_invoice:1,
                name_supplier:'',
                sender_id:0,
                treasury_amount:0,
                sender_amount:0,

                products:[]
            },
        });

        const rules = computed(() => {
            return {
                products:[
                    ...productValidation.value
                ],
                order_status_id:{
                    required
                },
                representative_id:{
                },
                treasury_id:{
                    required
                },
                type_invoice:{
                    required
                },
                treasury_amount:{
                    required,
                    numeric
                },
                sender_amount:{
                    required,
                    numeric
                },
            }
        });

        let dataChangeOrder = (id) =>{
            let v = orders.value.find((el)=> el.id == id);
            changeStatus.data.order_status_id = v.order_status_id ;
            changeStatus.data.representative_id = v.representative_id ;
            changeStatus.data.order_id = id ;
            order_amount.value = v.total;
        };

        let dataOrderReturn = () =>{
            let v = orders.value.find((el)=> el.id == changeStatus.data.order_id);

            if (changeStatus.data.order_status_id == 7){
                v.order_details.forEach((el,index) =>{
                    productValidation.value.push({
                        return_quantity: {
                            required,
                            numeric
                        },
                        return_sub_quantity: {
                            required,
                            numeric
                        },
                        quantity: {
                            required,
                            numeric
                        },
                        sub_quantity: {
                            required,
                            numeric
                        },
                        order_details_id:{
                            required
                        }
                    });
                    changeStatus.data.products.push({
                        order_details_id:el.id,
                        product_name:el.product.name,
                        quantity:el.quantity,
                        sub_quantity:el.sub_quantity,
                        price:el.price,
                        sub_price:el.sub_price,
                        return_quantity:0,
                        return_sub_quantity:0,
                    });
                });
                changeStatus.data.name_supplier = v.user.name ;
                changeStatus.data.treasury_amount = v.total ;
                changeStatus.data.sender_id = v.user_id ;
                order_amount.value = v.total;
            }else if(changeStatus.data.order_status_id == 5) {
                productValidation.value = [];
                changeStatus.data.products =[];
                changeStatus.data.name_supplier = v.user.name ;
                changeStatus.data.treasury_amount = v.total ;
                changeStatus.data.sender_id = v.user_id ;
                order_amount.value = v.total;
            }else {
                productValidation.value = [];
                changeStatus.data.products =[];
            }
        };

        let accountOrderAmount = () =>{

            if (changeStatus.data.order_status_id == 7){
                let subTotal = 0;
                total.value = 0;
                changeStatus.data.products.forEach((el,index) =>{
                    subTotal +=  el.return_quantity * el.price;
                    subTotal +=   el.return_sub_quantity * el.sub_price;

                });

                total.value = subTotal;

                if (changeStatus.data.type_invoice == 1){
                    changeStatus.data.treasury_amount = order_amount.value - total.value;
                }else if(changeStatus.data.type_invoice == 0) {
                    changeStatus.data.sender_amount = order_amount.value - total.value;
                }else{
                    changeStatus.data.treasury_amount = order_amount.value - total.value;
                }
            }
        };

        watch(()=>changeStatus.data.type_invoice,(after,before) =>{
            accountOrderAmount();
            changeStatus.data.sender_amount = 0 ;
            changeStatus.data.treasury_amount = 0 ;
            if (after == 1){
                changeStatus.data.treasury_amount = order_amount.value;
            }else if(after == 0) {
                changeStatus.data.sender_amount = order_amount.value;
            }else{
                changeStatus.data.treasury_amount = order_amount.value;
            }
        });

        watch(()=>changeStatus.data.treasury_amount,(after,before) =>{
            changeStatus.data.sender_amount = order_amount.value - changeStatus.data.treasury_amount;
        });

        watch(()=>changeStatus.data.sender_amount,(after,before) =>{
            changeStatus.data.treasury_amount = order_amount.value - changeStatus.data.sender_amount;
        });

        watch(()=>changeStatus.data.treasury_id,(after,before) =>{
            let v = treasuries.value.filter((el)=> el.id == changeStatus.data.treasury_id);
            treasury_amount.value = v[0].amount;
        });

        let changeQuantity = (index) =>{
            let v = orders.value.find((el)=> el.id == changeStatus.data.order_id);
            let details = v.order_details.find((ele)=> ele.id == changeStatus.data.products[index].order_details_id);
            changeStatus.data.products[index].return_quantity = details.quantity - changeStatus.data.products[index].quantity ;
            accountOrderAmount();
        };

        let changeReturnQuantity = (index) =>{
            let v = orders.value.find((el)=> el.id == changeStatus.data.order_id);
            let details = v.order_details.find((ele)=> ele.id == changeStatus.data.products[index].order_details_id);
            changeStatus.data.products[index].quantity = details.quantity - changeStatus.data.products[index].return_quantity ;
            accountOrderAmount();
        };

        let changeSubQuantity = (index) =>{
            let v = orders.value.find((el)=> el.id == changeStatus.data.order_id);
            let details = v.order_details.find((ele)=> ele.id == changeStatus.data.products[index].order_details_id);
            changeStatus.data.products[index].return_sub_quantity = details.sub_quantity - changeStatus.data.products[index].sub_quantity ;
            accountOrderAmount();
        };

        let changeReturnSubQuantity = (index) =>{
            let v = orders.value.find((el)=> el.id == changeStatus.data.order_id);
            let details = v.order_details.find((ele)=> ele.id == changeStatus.data.products[index].order_details_id);
            changeStatus.data.products[index].sub_quantity = details.sub_quantity - changeStatus.data.products[index].return_sub_quantity ;
            accountOrderAmount();
        };

        const v$ = useVuelidate(rules,changeStatus.data);

        return {
            ...toRefs(changeStatus),
            v$,
            t,
            representatives,
            treasuries,
            treasury_amount,
            changeQuantity,
            changeReturnQuantity,
            changeSubQuantity,
            changeReturnSubQuantity,
            dataChangeOrder,
            dataOrderReturn,
            ordersPaginate,
            search,
            status,
            orderStatus,
            deleteOrder,
            printDataSmall,
            permission,
            order_id,
            fromDate,
            toDate,
            orders,
            loading,
            dateFormat,
            printData,
            getOrder,
            close
        };

    },
    methods: {
        storeChangeOrder(){
            this.v$.$validate();
            if(!this.v$.$error){
                this.loading = true;
                this.errors = {};
                adminApi.post(`/v1/dashboard/orderStatus`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.changeStatusSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        document.getElementById('close-'+this.data.order_id).click();
                        this.resetForm();
                        this.getOrder();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        console.log(err);
                        // this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.order_status_id = '';
            this.data.representative_id = '';
            this.data.order_id = '';
            this.data.treasury_id = 1;
            this.data.type_invoice = 1;
            this.data.name_supplier = '';
            this.data.sender_id = 0;
            this.data.treasury_amount = 0;
            this.data.sender_amount = 0;
            this.data.products = [];
        }
    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
            errors:{}
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 100%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}


.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.submit-margin{
    margin-top: 38px !important;
}
.date-input{
    width: 175px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}
.total-head{
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background-color: #fcb00c !important;
    border-radius: 10px;
}
.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}
.head-data-table{
    display: flex;
    width: 100%;
    justify-content: space-around;
}
.head-button{
    margin-top: 5px;
    margin-bottom: 5px;
}
.edit-envoice .modal-dialog{
    max-width: 500px !important;
}

/*======== print ===========*/

.head-data{
    margin: 0px 0 15px 0;
}
.image-div{
    display: flex;
    flex-direction: row-reverse;
}
.image-div img {
    width: 35%;
}
.invoice-head{
    display: flex;
    align-items: center;
}

td.description,
th.description {
    width: 150px;
    max-width: 150px;
}

td.index,
th.index {
    width: 50px;
    max-width: 50px;
}

td.quantity,
th.quantity {
    width: 50px;
    max-width: 50px;
    word-break: break-all;
}

td.price1,
th.price1 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
}

td.price2,
th.price2 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    margin: auto;
    width: 300px;
    max-width: 300px;
}

img {
    max-width: inherit;
    width: inherit;
}


</style>
