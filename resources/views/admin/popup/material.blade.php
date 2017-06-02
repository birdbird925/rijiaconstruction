<div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/meterial" method="post" form-type="material">
                {{ csrf_field() }}
                <div class="modal-body">
                    <h5 class="sub-title" id="exampleModalLabel">Add Material</h5>
                        <div class="form-group">
                            <label>Material: </label>
                            <textarea name="material" id="" cols="30" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label>quantity: </label>
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>unit: </label>
                                <input type="text" name="unit" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" min="1" required>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="create">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add & More</button>
                </div>
            </form>
        </div>
    </div>
</div>
