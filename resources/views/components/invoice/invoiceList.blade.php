<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h5>Invoices</h5>
                    </div>
                    <div class="align-items-center col">
                        <a href="{{ route('sales') }}" class="float-end btn m-0 bg-gradient-primary">Create Sale</a>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Vat</th>
                            <th>Discount</th>
                            <th>Payable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    getList();

    async function getList() {
        showLoader();
        let res = await axios.get("/invoice-list");
        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach(function(item, index) {
            let row = `<tr>
                        <td>${index+1}</td>
                        <td>${item['customer']['name']}</td>
                        <td>${item['customer']['mobile']}</td>
                        <td>${item['total']}</td>
                        <td>${item['vat']}</td>
                        <td>${item['discount']}</td>
                        <td>${item['payable']}</td>
                        <td>
                            <button data-id="${item['id']}" data-cust="${item['customer']['id']}" class="viewBtn btn btn-outline-dark text-sm px-3 py-1 btn-sm m-0">
                                <i class="fa text-sm fa-eye"></i>
                            </button>
                            <button data-id="${item['id']}" data-cust="${item['customer']['id']}" class="deleteBtn btn btn-outline-dark text-sm ms-2 px-3 py-1 btn-sm m-0">
                                <i class="fa text-sm  fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`
            tableList.append(row)
        })

        $('.viewBtn').on('click', async function() {
            let id = $(this).data('id');
            let cust = $(this).data('cust');
            await InvoiceDetails(id, cust)
        })

        $('.deleteBtn').on('click', function() {
            let deleteId = $(this).data('id');
            document.getElementById('deleteID').value = deleteId;
            $("#delete-modal").modal('show');
        })

        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            lengthMenu: [5, 10, 20, 30, 50]
        });
    }
</script>
