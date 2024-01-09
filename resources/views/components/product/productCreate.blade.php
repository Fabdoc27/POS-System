<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPrice">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit">

                                <img class="w-20 my-3 d-block" id="newImg" src="{{ asset('images/default.jpg') }}" />

                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="productImg">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="save()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    dropdown();

    async function dropdown() {
        let res = await axios.get("/category-list")

        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}"> ${item['name']} </option>`
            $("#productCategory").append(option);
        })
    }

    async function save() {
        let productCategory = document.getElementById('productCategory').value;
        let productName = document.getElementById('productName').value;
        let productPrice = document.getElementById('productPrice').value;
        let productUnit = document.getElementById('productUnit').value;
        let productImage = document.getElementById('productImg').files[0];

        if (productCategory.length === 0 || productName.length === 0 || productPrice.length === 0 || productUnit
            .length === 0) {
            errorToast("All fields are required");
        } else if (!productImage) {
            errorToast("Product image required");

        } else {
            document.getElementById('modal-close').click();

            let formData = new FormData();
            formData.append('img', productImage)
            formData.append('name', productName)
            formData.append('price', productPrice)
            formData.append('unit', productUnit)
            formData.append('category_id', productCategory)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/product-create", formData, config)
            hideLoader();

            if (res.status === 201) {
                successToast("Product Created");
                document.getElementById("save-form").reset();
                await getList();

            } else {
                errorToast("Failed");
            }
        }
    }
</script>
