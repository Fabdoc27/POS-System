<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="updateName">
                                <input class="dnone" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="onUpdate()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function oldData(id) {
        document.getElementById('updateID').value = id;

        showLoader();
        let res = await axios.post("/category-unique", {
            id: id
        });

        document.getElementById('updateName').value = res.data['name'];
        hideLoader();
    }

    async function onUpdate() {
        let categoryName = document.getElementById('updateName').value;
        let updateID = document.getElementById('updateID').value;

        if (categoryName.length === 0) {
            errorToast("Catrgory Required")
        } else {
            document.getElementById("update-modal-close").click();

            showLoader();
            let res = await axios.post("/category-update", {
                id: updateID
                name: categoryName
            })
            hideLoader();

            if (res.status === 200 && res.data === 1) {
                successToast("Category Updated");
                await getList();

            } else {
                errorToast("Failed");
            }
        }
    }
</script>
