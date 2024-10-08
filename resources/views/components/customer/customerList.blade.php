<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-3">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Customer</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList" class="text-center">
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
        let res = await axios.get("/customer-list");
        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach(function(item, index) {
            let row = `<tr>
                        <td>${index+1}</td>
                        <td>${item['name']}</td>
                        <td>${item['email']}</td>
                        <td>${item['mobile']}</td>
                        <td>
                            <button data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-info">Edit</button>
                            <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger ms-2">Delete</button>
                        </td>
                    </tr>`
            tableList.append(row)
        })

        $('.editBtn').on('click', async function() {
            let editId = $(this).data('id');
            await oldData(editId)
            $("#update-modal").modal('show');
        });

        $('.deleteBtn').on('click', function() {
            let deleteId = $(this).data('id');
            $("#delete-modal").modal('show');
            $("#deleteID").val(deleteId);
        })

        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            lengthMenu: [5, 10, 20, 30, 50]
        });

    }
</script>
