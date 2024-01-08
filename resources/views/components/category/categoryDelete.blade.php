<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3">Delete</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="dnone" id="deleteID" />
            </div>
            <div class="modal-footer justify-content-center">
                <div>
                    <button type="button" id="delete-modal-close" class="btn bg-gradient-secondary mx-2"
                        data-bs-dismiss="modal">Cancel</button>
                    <button onclick="onDelete()" type="button" id="confirmDelete"
                        class="btn bg-gradient-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onDelete() {
        let categoryId = document.getElementById('deleteID').value;
        document.getElementById('delete-modal-close').click();

        showLoader();
        let res = await axios.post("/category-delete", {
            id: categoryId
        })
        hideLoader();

        if (res.data === 1) {
            successToast("Category Deleted");
            await getList();
        } else {
            errorToast("Failed");
        }
    }
</script>
