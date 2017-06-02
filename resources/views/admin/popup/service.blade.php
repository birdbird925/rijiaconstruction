<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/service" method="post" form-type="service">
                {{ csrf_field() }}
                <div class="modal-body">
                    <h5 class="sub-title" id="exampleModalLabel">Add Services</h5>
                    <div class="form-group">
                        <label>Title: </label>
                        <textarea name="service" id="" cols="30" rows="5" class="form-control" autofocus required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Amount: </label>
                        <input type="number" name="price" class="form-control" min="1" required>
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
