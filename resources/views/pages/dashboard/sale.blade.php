@extends('layouts.sidenav_layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    {{-- invoice header --}}
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-sm mx-0 my-1">Name:
                                <span id="cust_name"></span>
                            </p>
                            <p class="text-sm mx-0 my-1">Email:
                                <span id="cust_email"></span>
                            </p>
                            <p class="text-sm mx-0 my-1">User ID:
                                <span id="cust_id"></span>
                            </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{ 'images/logo.png' }}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />

                    {{-- invoice body --}}
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                    <tr class="text-sm">
                                        <td>Name</td>
                                        <td>Qty</td>
                                        <td>Total</td>
                                        <td>Remove</td>
                                    </tr>
                                </thead>
                                <tbody class="w-100" id="invoiceList">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />

                    {{-- invoice footer --}}
                    <div class="row">
                        <div class="col-12">
                            <p class="text-bold text-sm my-1 text-dark"> TOTAL: <i class="fas fa-dollar-sign"></i> <span
                                    id="total"></span></p>
                            <p class="text-bold text-sm my-2 text-dark"> PAYABLE: <i class="fas fa-dollar-sign"></i>
                                <span id="payable"></span>
                            </p>
                            <p class="text-bold text-sm my-1 text-dark"> VAT(5%): <i class="fas fa-dollar-sign"></i>
                                <span id="vat"></span>
                            </p>
                            <p class="text-bold text-sm my-1 text-dark"> Discount: <i class="fas fa-dollar-sign"></i>
                                <span id="discount"></span>
                            </p>
                            <span class="text-sm">Discount(%):</span>
                            <input onkeydown="return false" value="0" min="0" type="number" step="0.25"
                                onchange="discountChange()" class="form-control w-40 " id="discountPercent" />
                            <p>
                                <button onclick="createInvoice()"
                                    class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                            </p>
                        </div>
                        <div class="col-12 p-2">
                        </div>
                    </div>
                </div>
            </div>

            {{-- product --}}
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                            <tr class="text-sm text-bold">
                                <td class="text-center">Products</td>
                                <td class="text-center">Pick</td>
                            </tr>
                        </thead>
                        <tbody class="w-100" id="productList">
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- product --}}

            {{-- customer --}}
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                            <tr class="text-sm text-bold">
                                <td class="text-center">Customers</td>
                                <td class="text-center">Pick</td>
                            </tr>
                        </thead>
                        <tbody class="w-100" id="customerList">
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- customer --}}
        </div>
    </div>

    {{-- add product modal --}}
    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="productId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="productName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="productPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="productQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button onclick="addProduct()" id="save-btn" class="btn bg-gradient-success">Add</button>
                </div>
            </div>
        </div>
    </div>
    {{-- add product modal --}}

    <script>
        (async () => {
            showLoader();
            await customerList();
            await productList();
            hideLoader();
        })()

        let InvoiceItemList = [];

        // invoice item
        function showInvoiceItems() {
            let invoiceList = $("#invoiceList");

            invoiceList.empty();

            InvoiceItemList.forEach(function(item, index) {
                let row = `<tr class="text-xs">
                        <td>${item['name']}</td>
                        <td>${item['quantity']}</td>
                        <td>${item['sale_price']}</td>
                        <td>
                            <a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a>
                        </td>
                    </tr>`
                invoiceList.append(row)
            })

            calculateGrandTotal();

            $(".remove").on('click', async function() {
                let index = $(this).data('index');
                removeItem(index);
            })
        }

        function removeItem(index) {
            InvoiceItemList.splice(index, 1);
            showInvoiceItems()
        }

        function discountChange() {
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            let vat = 0;
            let payable = 0;
            let discount = 0;
            let discountPercentage = (parseFloat(document.getElementById('discountPercent').value));

            // update price if new product added
            InvoiceItemList.forEach((item, index) => {
                total = total + parseFloat(item['sale_price'])
            })

            // no discount vat
            if (discountPercentage === 0) {
                vat = ((total * 5) / 100).toFixed(2);
            } else {
                // discount vat
                discount = ((total * discountPercentage) / 100).toFixed(2);
                total = (total - ((total * discountPercentage) / 100)).toFixed(2);
                vat = ((total * 5) / 100).toFixed(2);
            }

            payable = (parseFloat(total) + parseFloat(vat)).toFixed(2);

            document.getElementById('total').innerText = total;
            document.getElementById('payable').innerText = payable;
            document.getElementById('vat').innerText = vat;
            document.getElementById('discount').innerText = discount;
        }

        // Product Create
        function addProduct() {
            let pId = document.getElementById('productId').value;
            let pName = document.getElementById('productName').value;
            let pPrice = document.getElementById('productPrice').value;
            let pQty = document.getElementById('productQty').value;
            let pTotalPrice = (parseFloat(pPrice) * parseFloat(pQty)).toFixed(2);

            if (pId.length === 0 || pName.length === 0 || pPrice.length === 0 || pQty.length === 0) {
                errorToast("All fields are required");
            } else {
                let items = {
                    name: pName,
                    product_id: pId,
                    sale_price: pTotalPrice,
                    quantity: pQty
                }
                InvoiceItemList.push(items);
                $('#create-modal').modal('hide')
                showInvoiceItems();
            }
        }

        function addModal(id, name, price) {
            document.getElementById('productId').value = id
            document.getElementById('productName').value = name
            document.getElementById('productPrice').value = price
            $('#create-modal').modal('show')
        }

        // Product List
        async function productList() {
            let res = await axios.get("/product-list");
            let productList = $("#productList");
            let productTable = $("#productTable");

            productTable.DataTable().destroy();
            productList.empty();

            res.data.forEach(function(item, index) {
                let row = `<tr >
                        <td class="text-sm"> <img class="w-15 me-2" src="${item['img_url']}"/> ${item['name']} ($ ${item['price']})</td>
                        <td>
                            <a data-name="${item['name']}" data-price="${item['price']}" data-id="${item['id']}" class="btn btn-outline-dark text-xs ms-5 px-2 py-1 addProduct  btn-xs m-0">Add</a>
                        </td>
                    </tr>`
                productList.append(row)
            })

            // product modal
            $('.addProduct').on('click', async function() {
                let productName = $(this).data('name');
                let productPrice = $(this).data('price');
                let productId = $(this).data('id');
                addModal(productId, productName, productPrice)
            })

            new DataTable('#productTable', {
                order: [
                    [0, 'desc']
                ],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }

        // customer
        async function customerList() {
            let res = await axios.get("/customer-list");
            let customerList = $("#customerList");
            let customerTable = $("#customerTable");

            customerTable.DataTable().destroy();
            customerList.empty();

            res.data.forEach(function(item, index) {
                let row = `<tr>
                            <td><i class="bi bi-person me-2"></i> ${item['name']}</td>
                            <td>
                                <a data-name="${item['name']}" data-email="${item['email']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer  text-xs px-2 py-1  btn-xs ms-4 m-0">Add</a>
                            </td>
                    </tr>`

                customerList.append(row)
            })

            $('.addCustomer').on('click', async function() {
                let customerName = $(this).data('name');
                let customerEmail = $(this).data('email');
                let customerId = $(this).data('id');

                // filling billed to data
                $("#cust_name").text(customerName)
                $("#cust_email").text(customerEmail)
                $("#cust_id").text(customerId)
            })

            new DataTable('#customerTable', {
                order: [
                    [0, 'desc']
                ],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }

        // create invoice
        async function createInvoice() {
            let Total = document.getElementById('total').innerText;
            let Discount = document.getElementById('discount').innerText;
            let Vat = document.getElementById('vat').innerText;
            let Payable = document.getElementById('payable').innerText;
            let Cust_id = document.getElementById('cust_id').innerText;

            let data = {
                "total": Total,
                "discount": Discount,
                "vat": Vat,
                "payable": Payable,
                "customer_id": Cust_id,
                "products": InvoiceItemList
            }

            if (Cust_id.length === 0) {
                errorToast("Customer Required")
            } else if (InvoiceItemList.length === 0) {
                errorToast("Product Required")
            } else {
                showLoader();
                let res = await axios.post("/invoice-create", data)
                hideLoader();

                if (res.data === 1) {
                    window.location.href = '/invoice'
                    successToast("Invoice Created");
                } else {
                    errorToast("Something Went Wrong")
                }
            }
        }
    </script>
@endsection
