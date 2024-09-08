<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3">Delete</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID" />
            </div>
            <div class="modal-footer justify-content-center">
                <div>
                    <button type="button" id="delete-modal-close" class="btn mx-2 bg-gradient-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button onclick="onDelete()" type="button" id="confirmDelete"
                        class="btn  bg-gradient-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onDelete() {
        let customerId = document.getElementById('deleteID').value;
        document.getElementById('delete-modal-close').click();

        showLoader();
        let res = await axios.post("/customer-delete", {
            id: customerId
        })
        hideLoader();

        if (res.data === 1) {
            successToast("Customer Deleted");
            await getList();
        } else {
            errorToast("Failed");
        }
    }
</script>
