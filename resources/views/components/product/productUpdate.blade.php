<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">


                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <br />
                                <img class="w-15" id="oldImg" src="{{ asset('images/default.jpg') }}" />
                                <br />
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="productImgUpdate">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="filePath">


                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-center">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="onUpdate()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>

        </div>
    </div>
</div>


<script>
    async function dropdown() {
        let res = await axios.get("/category-list")

        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}"> ${item['name']} </option>`
            $("#productCategoryUpdate").append(option);
        })
    }

    async function oldData(editId, filePath) {
        document.getElementById('updateID').value = editId;
        document.getElementById('filePath').value = filePath;
        document.getElementById('oldImg').src = filePath;

        showLoader();
        await dropdown();

        let res = await axios.post("/product-unique", {
            id: editId,
        });
        hideLoader();

        document.getElementById('productCategoryUpdate').value = res.data['category_id'];
        document.getElementById('productNameUpdate').value = res.data['name'];
        document.getElementById('productPriceUpdate').value = res.data['price'];
        document.getElementById('productUnitUpdate').value = res.data['unit'];
    }

    async function onUpdate() {
        let productCategory = document.getElementById('productCategoryUpdate').value;
        let productName = document.getElementById('productNameUpdate').value;
        let productPrice = document.getElementById('productPriceUpdate').value;
        let productUnit = document.getElementById('productUnitUpdate').value;
        let updateID = document.getElementById('updateID').value;
        let filePath = document.getElementById('filePath').value;
        let productImage = document.getElementById('productImgUpdate').files[0];

        if (productCategory.length === 0 || productName.length === 0 || productPrice.length === 0 || productUnit
            .length === 0) {
            errorToast("All fields are required");

        } else {
            document.getElementById('update-modal-close').click();

            let formData = new FormData();
            formData.append('img', productImage)
            formData.append('id', updateID)
            formData.append('name', productName)
            formData.append('price', productPrice)
            formData.append('unit'.productUnit)
            formData.append('category_id', productCategory)
            formData.append('file_path', filePath)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/product-update", formData, config)
            hideLoader();

            if (res.status === 200 && res.data === 1) {
                successToast("Product Updated");
                document.getElementById("update-form").reset();
                await getList();

            } else {
                errorToast("Failed");
            }
        }
    }
</script>
